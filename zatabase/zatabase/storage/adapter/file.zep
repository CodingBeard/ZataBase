/*
 * ZataBase\Storage\Adapter\File
 *
 * @category 
 * @package ZataBase
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace ZataBase\Storage\Adapter;

use ZataBase\Storage\Exception;
use ZataBase\Helper\FileHandler;

class File {

    /**
    * Scope of the database
    * @var string
    */
    protected scope {
        get, set
    };

    /**
    * Constructor
    * @var string directory
    */
    public function __construct(const string! directory) -> void
    {

        /* Make sure there's a trailing slash on the end of the directory */
        if (substr(directory, -1) !== DIRECTORY_SEPARATOR) {
            let this->scope = directory . DIRECTORY_SEPARATOR;
        }
        else {
            let this->scope = directory;
        }

        if !is_dir(this->scope) {
            /* Check we have permissions to do that */
            if is_writable(dirname(this->scope)) {
                mkdir(this->scope);
            }
            else {
                throw new Exception("Cannot create database in dir: '" . dirname(this->scope) . "'. Bad Permissions.");
            }
        }
    }

    /**
    * Get static path
    *
    * @param string path
    * @return string
    */
    public function path(const string! path) -> string
    {
        var absolutePath = "";

        /* Stop self from appending scope if it's present */
        if substr(path, 0, strlen(this->scope)) != this->scope {
            let absolutePath = this->scope . trim(path, DIRECTORY_SEPARATOR);
        }
        else {
            let absolutePath = this->scope . trim(substr(path, strlen(this->scope)), DIRECTORY_SEPARATOR);
        }

        /* Compare the amount of relative parent .. to the depth of the path to check if we leave the scope */
        if unlikely substr_count(path, "..") > 0 {
            if substr_count(path, "..") >= substr_count(trim(path, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR) {
                throw new Exception("Attempting to access files outside of the defined scope.");
            }
        }

        return absolutePath;
    }

    /**
    * Check if a directory/file is writable
    *
    * @param string path
    */
    public function isWritable(const string! path) -> bool
    {
        return is_writable(this->path(path));
    }

    /**
    * Check if a directory exists
    *
    * @param string path
    */
    public function isDir(const string! path) -> bool
    {
        return is_dir(this->path(path));
    }

    /**
    * Create a directory
    *
    * @param string path
    */
    public function addDir(const string! path) -> void
    {
        /* Dir exists, nothing to do here */
        if this->isDir(path) {
            return;
        }

        /* Can't create a dir in a non-existent dir, recursively create parent dirs */
        this->addDir(dirname(path));

        /* Check we have permissions to do that */
        if this->isWritable(dirname(path)) {
            mkdir(this->path(path));
        }
        else {
            throw new Exception("Cannot create folder in dir: '" . dirname(this->path(path)) . "'. Bad Permissions.");
        }
    }

    /**
    * Delete a directory
    *
    * @param string path
    */
    public function removeDir(const string! path) -> void
    {
        /* Dir doesn't exist, nothing to do here */
        if !this->isDir(path) {
            return;
        }

        /* Check for files within the dir */
        var files = [], file = "";
        let files = scandir(this->path(path));

        /* If files, recursively delete  */
        if count(files) {
            for file in files {
                if file == "." || file == ".." {
                    continue;
                }

                let file = this->path(path . DIRECTORY_SEPARATOR . file);

                if this->isDir(file) {
                    this->removeDir(file);
                }
                else {
                    this->removeFile(file);
                }
            }
        }

        /* Check we have permissions to do so */
        if this->isWritable(path) {
            rmdir(this->path(path));
        }
        else {
            throw new Exception("Cannot delete folder: '" . dirname(path) . "'. Bad Permissions.");
        }
    }

    /**
    * Check if a directory exists
    *
    * @param string path
    */
    public function isFile(const string! path) -> bool
    {
        return is_file(this->path(path));
    }

    /**
    * Touch a file
    *
    * @param string path
    */
    public function touch(const string! path) -> void
    {
        /* Make sure we can write the file */
        this->addDir(dirname(path));

        touch(this->path(path));
    }

    /**
    * Set the entire contents of a file
    *
    * @param string path
    * @param string|int content
    */
    public function setFile(const string! path, const var content = "") -> void
    {
        /* Make sure we can write the file */
        this->addDir(dirname(path));

        file_put_contents(this->path(path), content . PHP_EOL, LOCK_EX);
    }

    /**
    * Get the entire contents of a file
    *
    * @param string path
    * @return string
    */
    public function getFile(string! path) -> string
    {
        if !this->isFile(path) {
            throw new Exception("Attempting to read a non-existent file: '" . path . "'.");
        }

        return file_get_contents(this->path(path));
    }

    /**
    * Get a file handle
    *
    * @param string path
    * @return mixed
    */
    public function getHandle(string! path)
    {
        if !this->isFile(path) {
            this->touch(path);
        }

        return new FileHandler(this->path(path), "c+");
    }

    /**
    * Remove a file
    *
    * @param string path
    */
    public function removeFile(const string! path) -> void
    {
        /* Nothing to do here */
        if !this->isFile(path) {
            return;
        }
        /* Check we can delete it */
        if this->isWritable(path) {
            unlink(this->path(path));
        }
        else {
            throw new Exception("Cannot delete file: '" . this->path(path) . "'. Bad Permissions.");
        }

    }

}
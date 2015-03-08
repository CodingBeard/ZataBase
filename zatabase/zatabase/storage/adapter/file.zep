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

class File {

    /**
    * Scope of the database
    * @var string
    */
    protected scope = "";

    /**
    * Constructor
    * @var string directory
    */
    public function __construct(const string directory) -> void
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
    public function absolutePath(const string! path) -> string
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
                throw new Exception("Attempting to access files outside of the defined scope");
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
        return is_writable(this->absolutePath(path));
    }

    /**
    * Check if a directory exists
    *
    * @param string path
    */
    public function isDir(const string! path) -> bool
    {
        return is_dir(this->absolutePath(path));
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
            mkdir(this->absolutePath(path));
        }
        else {
            throw new Exception("Cannot create folder in dir: '" . dirname(this->absolutePath(path)) . "'. Bad Permissions.");
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
        let files = scandir(this->absolutePath(path));

        /* If files, recursively delete  */
        if count(files) {
            for file in files {
                if file == "." || file == ".." {
                    continue;
                }

                let file = this->absolutePath(path . DIRECTORY_SEPARATOR . file);

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
            rmdir(this->absolutePath(path));
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
        return is_file(this->absolutePath(path));
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

        file_put_contents(this->absolutePath(path), content, LOCK_EX);
    }

    /**
    * Get the entire contents of a file
    *
    * @param string path
    * @return string
    */
    public function readFile(string! path) -> string
    {
        if !this->isFile(path) {
            throw new Exception("Attempting to read a non-existent file");
        }

        return file_get_contents(this->absolutePath(path));
    }

    /**
    * Remove a file
    *
    * @param string path
    */
    public function removeFile(const string! path) -> void
    {
        /* Check we can delete it */
        if this->isWritable(path) {
            unlink(this->absolutePath(path));
        }
        else {
            throw new Exception("Cannot delete file: '" . this->absolutePath(path) . "'. Bad Permissions.");
        }

    }

    /**
    * Append a line to a file
    *
    * @param string path
    * @param string|int content
    */
    public function appendLine(const string! path, const var content = "") -> void
    {
        /* Create a new file if one doesn't exist */
        if !this->isFile(this->absolutePath(path)) {
            this->setFile(this->absolutePath(path), content);
        }
        else {
            file_put_contents(this->absolutePath(path), PHP_EOL . content, FILE_APPEND | LOCK_EX);
        }
    }

    /**
    * Add a line in a file
    *
    * @param string path
    * @param int line
    * @param string|int content
    */
    public function addLine(const string! path, const int! line, const var content = "") -> void
    {
        var absolutePath, read, write, count = 0;
        let absolutePath = this->absolutePath(path);

        /* Create a new file if one doesn't exist */
        if !this->isFile(path) {
            file_put_contents(absolutePath, content);
        }
        else {
            let read = fopen(absolutePath, "r"), write = fopen(absolutePath . ".write", "w");

            while !feof(read) {
                let count++;
                if count == line {
                    fputs(write, content . PHP_EOL);
                }

                fputs(write, fgets(read));
            }
            fclose(read);
            fclose(write);

            rename(absolutePath . ".write", absolutePath);
        }
    }

    /**
    * Read a specific line in a file
    *
    * @param string path
    * @param int line
    * @return string
    */
    public function readLine(const string! path, const int! line) -> string
    {
        var read, readline, count = 0;

        if !this->isFile(path) {
            throw new Exception("Attempting to read a non-existent file");
        }

        let read = fopen(this->absolutePath(path), "r");

        while !feof(read) {
            let count++;
            let readline = fgets(read);
            if count == line {
                fclose(read);
                return readline;
            }
        }
        return "";
    }

    /**
    * Remove a specific line in a file
    *
    * @param string path
    * @param int line
    */
    public function removeLine(const string! path, const int! line) -> void
    {
        var absolutePath, read, write, count = 0;
        let absolutePath = this->absolutePath(path);

        if !this->isFile(path) {
            throw new Exception("Attempting to delete from a non-existent file");
        }

        let read = fopen(absolutePath, "r"), write = fopen(absolutePath . ".write", "w");

        while !feof(read) {
            let count++;
            if count != line {
                fputs(write, fgets(read));
            }
            else {
                fgets(read);
            }
        }
        fclose(read);
        fclose(write);

        rename(absolutePath . ".write", absolutePath);
    }

}
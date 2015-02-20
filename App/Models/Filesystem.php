<?php

class Filesystem {

    /** Directories **/
    public static function getDirectories($path) {

        $results = scandir($path);
        $directories = array();

        foreach ($results as $result) {
            if ($result === '.' or $result === '..') continue;

            if (is_dir($path . '/' . $result)) {
                array_push($directories,$result);
            }
        }

        return $directories;
    }

    public static function getDirectoriesCreateIfNotExist($path) {

        if (self::existsDirectory($path)) {
            $directories = self::getDirectories($path);
        } else {
            self::createDirectory($path);
            $directories = array();
        }
        return $directories;

    }

    public static function createDirectory($path) {
        $result = mkdir($path,'0777',true);
        if ($result) {
            return $path;
        }
        return false;
    }

    public static function existsDirectory($path) {

        if (is_dir($path)) {
            return true;
        }

        return false;
    }

    public static function deleteDirectory($path) {
        // Script taken from http://stackoverflow.com/a/3349792
        if (! is_dir($path)) {
            throw new InvalidArgumentException("$path must be a directory");
        }
        if (substr($path, strlen($path) - 1, 1) != '/') {
            $path .= '/';
        }
        $files = glob($path . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDirectory($file);
            } else {
                unlink($file);
            }
        }
        rmdir($path);
    }

}
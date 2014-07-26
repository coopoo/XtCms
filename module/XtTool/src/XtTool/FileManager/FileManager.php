<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-07
 * @Time: 14:22
 * @QQ: 259522
 * @FileName: FileManager.php
 */

namespace XtTool\FileManager;

use XtTool\Tool\Tool;

class FileManager
{
    const DEFAULT_READ_MODE = 'r';
    const DEFAULT_WRITE_MODE = 'a+';

    public static function read($file, $length = null, $mode = self::DEFAULT_READ_MODE)
    {
        if (!is_file($file)) {
            return false;
        }
        if (!$handle = fopen($file, $mode)) {
            return false;
        }
        $length = ($length) ?: filesize($file);
        $string = fread($handle, $length);
        fclose($handle);
        return $string;
    }

    public static function getFileToArray($file)
    {
        if (is_file($file)) {
            $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            return $lines;
        }
        return null;
    }

    public static function write($file, $data, $mode = self::DEFAULT_WRITE_MODE)
    {
        self::makeDir($file);
        if (!$handle = fopen($file, $mode)) {
            return false;
        }
        if (fwrite($handle, $data) === false) {
            return false;
        }
        fclose($handle);
        return true;
    }

    public static function unlink($file)
    {
        if (is_file($file)) {
            return unlink($file);
        }
    }

    public static function listDir($dir, $index = false, $fileType = null)
    {
        if (!is_dir($dir)) {
            return [];
        }

        if (substr(str_replace('\\', '/', $dir), -1) != '/') {
            $dir .= DIRECTORY_SEPARATOR;
        }

        if (!$handle = opendir($dir)) {
            return [];
        }
        $fileList = [];
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
                if (is_dir($dir . $file)) {
                    $fileList[$file] = self::listDir($dir . $file, $fileType);
                } else {
                    $fileType = ($fileType) ?: $fileType;
                    if (is_file($dir . $file)) {
                        if ($fileType !== null) {
                            if ($fileType !== strrchr($file, '.')) {
                                continue;
                            }
                        }
                        $name = mb_convert_encoding($file, 'utf-8', 'gb2312');
                        $time = filemtime($dir . $file);
                        $fileInfo = [
                            'name' => $name,
                            'e_name' => Tool::encode($name),
                            'time' => date('Y-m-d H:i:s', $time),
                            'u_time' => $time,
                        ];
                        if ($index) {
                            $fileHandle = fopen($dir . $file, 'r');
                            $bak = null;
                            if ($fileHandle) {
                                $bak = fgets($fileHandle);
                                fclose($fileHandle);
                            }
                            $fileInfo['bak'] = str_replace('--', '', $bak);
                        }
                        $fileList[] = $fileInfo;
                    }
                    clearstatcache();
                }
            }
        }
        closedir($handle);
        return self::arraySort($fileList);
    }

    public static function arraySort($array, $key = 'u_time', $sort = 'DESC')
    {
        $keyArray = $newArray = [];
        foreach ($array as $k => $item) {
            $keyArray[$k] = $item[$key];
        }
        if (strtoupper($sort) === 'DESC') {
            arsort($keyArray);
        } else {
            asort($keyArray);
        }
        reset($keyArray);
        foreach ($keyArray as $k => $v) {
            $newArray[] = $array[$k];
        }
        unset($keyArray);
        unset($array);
        return $newArray;
    }


    public static function makeDir($dir, $mode = 0777, $recursive = true)
    {
        $dir = dirname($dir);
        if (is_null($dir) || $dir === "") {
            return false;
        }
        if (is_dir($dir) || $dir === "/") {
            return true;
        }

        if (self::makeDir($dir, $mode, $recursive)) {
            return mkdir($dir, $mode);
        }
        return false;
    }
} 
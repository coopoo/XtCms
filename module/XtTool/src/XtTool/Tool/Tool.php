<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-14
 * @Time: 16:51
 * @QQ: 259522
 * @FileName: Tool.php
 */

namespace XtTool\Tool;


class Tool
{
    public static function encode($string)
    {
        return str_replace(['=', '+', '\\', '/'], ['_Ua_', '_Ub_', '_Uc_', '_Ud_'], base64_encode(base64_encode($string)));
    }

    public static function decode($string)
    {
        return str_replace(['_Ua_', '_Ub_', '_Uc_', '_Ud_'], ['=', '+', '\\', '/'], base64_decode(base64_decode($string)));
    }

    public static function getUniqid($namespace = null)
    {
        $data = $namespace;
        $data .= isset($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : null;
        $data .= isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
        $data .= isset($_SERVER['LOCAL_ADDR']) ? $_SERVER['LOCAL_ADDR'] : null;
        $data .= isset($_SERVER['LOCAL_PORT']) ? $_SERVER['LOCAL_PORT'] : null;
        $data .= isset($_SERVER['LOCAL_PORT']) ? $_SERVER['LOCAL_PORT'] : null;
        $data .= isset($_SERVER['REMOTE_PORT']) ? $_SERVER['REMOTE_PORT'] : null;
        return md5(uniqid(mt_rand(), true) . sha1($data));
    }
} 
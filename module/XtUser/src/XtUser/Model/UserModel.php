<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-07-22
 * @Time: 14:06
 * @QQ: 259522
 * @FileName: UserModel.php
 */

namespace XtUser\Model;


/**
 * Class UserModel
 * @package XtUser\Model
 */
class UserModel
{

    /**
     *
     */
    const SALT = 'cOOpoO';

    /**
     *
     */
    const DEFAULT_STATUS = 'Y';

    /**
     *
     */
    const ALLOW_STATUS = 'Y';

    /**
     *
     */
    const NOT_ALLOWED_STATUS_MESSAGE = '该用户不存在或禁止登陆';
    const PASSWORD_ERROR_MESSAGE = '用户名或密码错误!';

    /**
     *
     */
    const USER_TABLE_CLASS = 'XtUser\Table\UserTable';

    /**
     *
     */
    const USER_LOGGER_TABLE_CLASS = 'XtUser\Table\UserLoggerTable';

    /**
     *
     */
    const USER_DETAIL_TABLE_CLASS = 'XtUser\Table\UserDetailTable';

    /**
     *
     */
    const USER_ROUTE = 'Xt_User';

    /**
     *
     */
    const USER_CENTER_ROUTE = 'Xt_User_Center';

    const BAN_USERNAME_MESSAGE = '用户名非法，请重新注册';
    /**
     *
     */
    const PASSWORD_FAIL_COUNT_MESSAGE = '密码连续错误%d次,请在%s秒后在进行尝试';

    public static function banUsername($username = null)
    {
        $banList = [
            'admin', 'manager', 'system', 'window'
        ];
        if ($username === null) {
            return $banList;
        }
        foreach ($banList as $ban) {
            if (strpos(strtolower($username), $ban) !== false) {
                return true;
            }
        }
    }

    /**
     * @param $password
     * @return string
     */
    public static function encryption($password)
    {
        return empty($password) ? null : md5(sha1($password) . self::SALT);
    }


    public static function getStatus($key = null, $default = null)
    {
        $status = [
            'Y' => '正常',
            'N' => '禁用'
        ];

        if ($key && array_key_exists((string)$key, $status)) {
            return $key;
        } else if ($key !== null) {
            return $default;
        }
        return $status;
    }

}
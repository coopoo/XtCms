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
    const DEFAULT_STATUS = 99;

    /**
     *
     */
    const ALLOW_STATUS = 99;

    /**
     *
     */
    const NOT_ALLOWED_STATUS_MESSAGE = '用户禁止登陆';


    /**
     *
     */
    const USER_TABLE_CLASS = 'XtUser\Model\UserTable';

    /**
     *
     */
    const USER_LOGGER_TABLE_CLASS = 'XtUser\Model\UserLoggerTable';

    /**
     *
     */
    const USER_DETAIL_TABLE_CLASS = 'XtUser\Model\UserDetailTable';

    /**
     *
     */
    const USER_ROUTE = 'Xt_User';

    /**
     *
     */
    const USER_CENTER_ROUTE = 'Xt_User_Center';


    /**
     *
     */
    const PASSWORD_FAIL_COUNT_MESSAGE = '密码连续错误%d次,请在%s秒后在进行尝试';

    /**
     * @param $password
     * @return string
     */
    public static function encryption($password)
    {
        return md5(sha1($password) . self::SALT);
    }

}
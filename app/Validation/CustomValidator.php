<?php
/**
 * Created by PhpStorm.
 * User: Lenbo
 * Date: 2015/8/19
 * Time: 9:47
 */

namespace App\Validation;

use App\Models\Options;
use App\Models\Users;
use App\Services\OAuth;
use \Illuminate\Validation\Validator;

class CustomValidator extends Validator {

    /**
     * 验证短信验证码
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validateSmsCode($attribute, $value, $parameters)
    {
        if (empty($parameters[1]))
        {
            $parameters[1] = \Session::getId();
        }

        return OAuth::verifySMSCode($parameters[0], $value, $parameters[1]);
    }

    /**
     * 验证密码是否正确
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validatePassword($attribute, $value, $parameters)
    {
        if (count($parameters) < 2)
        {
            return false;
        }

        // 获取用户信息
        $user = Users::model()->getUserInfo($parameters[1], $parameters[0]);
        if (empty($user))
        {
            return false;
        }

        return $user->getAttribute('password') == OAuth::password($value);
    }

    /**
     * 验证用户密码
     *
     * @param $attribute
     * @param $value
     * @return bool
     */
    public function validateUserPassword($attribute, $value)
    {
        $auth = new OAuth();
        $password = $auth->getUser() ? $auth->getUser()->getAttribute('password') : null;
        return OAuth::password($value) == $password;
    }

    /**
     * 验证手机号与当前登录用户的手机号是否一致
     *
     * @param $attribute
     * @param $value
     * @return bool
     */
    public function validateOauthPhone($attribute, $value)
    {
        $auth = new OAuth();
        $phone = $auth->getUser() ? $auth->getUser()->getAttribute('phone') : null;
        return $value == $phone;
    }

    /**
     * 验证用户是否被删除
     *
     * @param $attribute
     *      可选值：phone，uid
     * @param $value
     * @return bool
     */
    public function validateUserDeny($attribute, $value)
    {
        if ($attribute == 'phone')
        {
            $user = Users::model()->getUserInfo($value);
        }
        elseif ($attribute == 'uid')
        {
            $user = Users::model()->getUserInfo($value, 'uid');
        }
        else
        {
            return false;
        }

        if (empty($user) || $user->getAttribute('status') == 2)
        {
            return false;
        }

        return true;
    }

    /**
     * 验证字符串或数组长度
     *
     * @param $attribute
     * @param $value
     *      如果$value为空(empty)，长度为0；
     *      如果为字符串，长度为mb_strlen($value, 'utf8')；
     *      如果为数组，长度为count($value)
     * @param $parameters
     *      $parameters只有一个值时，验证长度为$parameters[0]
     *      第二个值为空为0时，只验证最小长度，最大长度没上限
     *      两个值都不为空时，验证范围
     *
     * @return bool
     */
    public function validateLength($attribute, $value, $parameters)
    {
        if (is_null($parameters))
        {
            return false;
        }

        if (empty($value))
        {
            $length = 0;
        }
        elseif (is_array($value))
        {
            $length = count($value);
        }
        else
        {
            $length = mb_strlen($value, 'utf8');
        }

        $count = count($parameters);
        if ($count == 1)
        {
            return $parameters[0] == $length;
        }
        elseif (empty($parameters[1]))
        {
            return $length >= $parameters[0];
        }
        else
        {
            return $length >= $parameters[0] && $length <= $parameters[1];
        }
    }

    /**
     * length错误信息占位符
     *
     * @param $message
     * @param $attribute
     * @param $rule
     * @param $parameters
     * @return mixed
     */
    public function replaceLength($message, $attribute, $rule, $parameters)
    {
        if (isset($parameters[0]))
        {
            $message = str_replace(':min', $parameters[0], $message);
        }

        if (isset($parameters[1]))
        {
            $message = str_replace(':max', $parameters[1], $message);
        }

        return $message;
    }

    /**
     * 用户名禁用词
     *
     * @param $attribute
     * @param $value
     * @return bool
     */
    public function validateUnameDeny($attribute, $value)
    {
        // 禁用词或字符
        $deny = Options::getUserNameDeny();
        foreach ($deny as $word)
        {
            if (strpos($value, $word) !== false)
            {
                return false;
            }
        }

        // 空格、空白
        if ($value != preg_replace("/ /", "", preg_replace("/\t/",'', $value)))
        {
            return false;
        }

        return true;
    }

    /**
     * 验证手机格式
     *
     * @param $attribute
     * @param $value
     * @return bool
     */
    public function validatePhone($attribute, $value)
    {
        return preg_match('/^((13[0-9])|147|(15[0-35-9])|178|(18[0-9]))[0-9]{8}$/A', $value) ? true : false;
    }
}
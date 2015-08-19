<?php
/**
 * Created by PhpStorm.
 * User: Lenbo
 * Date: 2015/8/19
 * Time: 9:47
 */

namespace App\Validation;

use App\Models\Options;
use \Illuminate\Validation\Validator;

class CustomValidator extends Validator {

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
        $message = str_replace(':min', $parameters[0], $message);
        $message = str_replace(':max', $parameters[1], $message);
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
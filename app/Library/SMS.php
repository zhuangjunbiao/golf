<?php
/**
 * Created by PhpStorm.
 * User: Lenbo
 * Date: 2015/8/19
 * Time: 10:30
 */

namespace App\Library;

/**
 * 短信发送
 *
 * Class SMS
 * @package App\Library
 */
class SMS {

    public static function send($phone, $content)
    {
        try
        {
            return true;
        }
        catch (\Exception $e)
        {
            throw $e;
        }
    }
}
<?php

namespace App\Http\Controllers\Api1;

class ConfigController extends Controller
{

    /**
     * 初始化
     *
     * 特别说明：
     *      该接口可直接访问，无需做各种验证
     *
     * 请求方式：
     *      GET
     *
     * 地址：
     *      SERVER/v1/config/init
     *
     * 参数：
     *      无
     *
     * @return array
     */
    public function getInit()
    {
        $data = array(
            'android'   => [

            ],
            'ios'       => [

            ]
        );

        return golf_return($data);
    }
}

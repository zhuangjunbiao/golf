<?php namespace App\Library;

use Vinelab\Http\Facades\Client;

class Console {

    /**
     * 远程通信打印
     *
     * @param mixed $obj
     */
    public static function log($obj)
    {
        $req = array('url' => 'http://106.187.102.59:3000/console/msg', 'json' => true);
        if (is_string($obj))
        {
            $req['params'] = ['body' => $obj, 'format' => 'test',];
        }
        else
        {
            $obj = (array)$obj;
            $req['params'] = array('body' => $obj, 'format' => 'json');
        }

        Client::post($req);
    }


}

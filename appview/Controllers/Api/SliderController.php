<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2/27/19
 * Time: 23:51
 */

namespace AppView\Controllers\Api;


class SliderController extends ApiController
{

    public function getIndex($code)
    {
        //Lấy slider từ vị trí
        $result = model('sliders/from_code')->load([
            'code' => $code
        ]);

        return $result['vars'];
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 5/7/18
 * Time: 23:04
 */

namespace AppView\Repository;


class SliderRepository
{

    /**
     * @param $code
     * @param int $limit
     * @return \VatGia\Helpers\Collection
     */
    public function bannersFromCode($code, $limit = 0)
    {
        $result = model('banner_slider/banner_slider')->load([
            'key_slider' => $code,
            'limit' => (int)$limit
        ]);

        return collect_recursive($result['vars']);
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2/26/2019
 * Time: 1:47 PM
 */

namespace AppView\Controllers\Api;


class TopRacingCampaignController extends ApiController
{

    public function getTop($id)
    {

        $result = repository('top_racing_campaign/top')->get([
                'campaign_id' => $id
            ] + $this->input);

        return $result['vars'];
    }

    public function getProducts()
    {

        $result = repository('top_racing_campaign/products')->get($this->input);

        return $result['vars'];

    }
}
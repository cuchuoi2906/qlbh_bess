<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/15/19
 * Time: 00:09
 */

namespace AppView\Controllers\Api;


use Gumlet\ImageResize;

class UploadController extends ApiController
{

    public function postUpload()
    {
        $type = getValue('type', 'str');
        if ($type == 'base64') {

            $image = $this->input['image'];
            $data = base64_decode($image);
            $name = md5(uniqid()) . '.jpg';

            file_put_contents(ROOT . '/public/upload/images/' . $name, $data);

        } else {
            $upload = new \upload('image', ROOT . '/public/upload/images/', 'gif,jpg,jpe,jpeg,png', 5000);
            if ($upload->common_error) {
                throw new \RuntimeException($upload->common_error, 500);
            }
            $name = $upload->file_name;
        }

        //Resize
        // Get new sizes
        $filename = ROOT . '/public/upload/images/' . $name;
        $image = new ImageResize($filename);
        $image->scale(30);
        $image->save($filename);


        return [
            'name' => $name,
            'url' => url() . '/upload/images/' . $name
        ];

    }
}
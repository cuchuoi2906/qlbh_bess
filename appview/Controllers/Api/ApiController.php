<?php

/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2/22/19
 * Time: 23:21
 */

namespace AppView\Controllers\Api;


class ApiController extends \VatGia\Api\ApiController {

    public $addResponse = [];


    public function __construct() {
        parent::__construct();
        $this->parseGetInput();
        $this->parseDateInput();
    }

    public function parseDateInput() {
        if ($_GET['start_date'] ?? false) {
            $this->input['start_date'] = date('Y-m-d', strtotime($_GET['start_date']));
        }
        if ($_GET['end_date'] ?? false) {
            $this->input['end_date'] = date('Y-m-d', strtotime($_GET['end_date']));
        }
    }

    public function parseGetInput() {
        $this->input = $this->input ?? [];
        $this->input = (array)$this->input;
        $this->input = array_merge($this->input, (array)($_GET ?? []));
        //        $this->input = $this->input ?? [];
    }

    public function __call($name, $arguments) {
        $method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
        $method = strtolower($method);

        $methodFunction = $method . ucfirst($name);
        if (method_exists($this, $methodFunction)) {
            try {
                $responseData = call_user_func_array([$this, $methodFunction], $arguments);

                return $this->response($responseData, $this->addResponse);
            } catch (\Exception $e) {
                return $this->error($e->getMessage(), $e->getCode());
            }
        }

        return $this->error('Method not allow', 405);
    }

    public function response($data = [], $add_response = []) {
        http_response_code(200);

        $data = $data ? (array)$data : [];

        if (array_key_exists('meta', $data)) {
            $meta = $data['meta'];
            unset($data['meta']);
        }

        if (array_key_exists('data', $data)) {
            $data_response = $data['data'];
        }
        $response = [
            'code' => 200,
            'version' => (int)config('api.version'),
            'meta' => $meta ?? [],
            'data' => $data_response ?? $data
        ];


        if (config('app.debug')) {
            $response['input'] = $this->input;
            $response['debug'] = $this->getDebugInfo();
        }

        return json_encode($response + $add_response);
    }

    public function error($message, $code = 400) {
        http_response_code((int)$code);
        $response = [
            'code' => (int)$code,
            'error' => $message,
        ];

        if (config('app.debug')) {
            $response['debug'] = $this->getDebugInfo();
        }

        return json_encode($response);
    }
}

<?php

/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2/22/19
 * Time: 23:21
 */

namespace AppView\Controllers\Api;


use AppView\Helpers\User;
use AppView\Repository\UserRepository;
use Firebase\JWT\JWT;

class AuthController extends ApiController {


    /**
     * @var UserRepository
     */
    public $userRepository;

    public function __construct(UserRepository $user_repository) {

        $this->userRepository = $user_repository;

        parent::__construct();
    }

    public function postLogin() {
        $social = false;
        //Đăng nhập Google - Facebook
        if ($this->input['fb_token'] ?? false) {

            //Lấy username và pass
            $result = model('users/login_with_facebook')->load([
                'token' => $this->input['fb_token']
            ]);
            if ($result['vars'] ?? false) {
                $social = true;
                $user_info = $result['vars'];
            } else {
                throw new \RuntimeException('Không thể đăng nhập qua facebook. Bạn hãy thử lại', 400);
            }

            $user = new \user();
        } elseif ($this->input['gg_token'] ?? false) {
            //Lấy username và pass
            $result = model('users/login_with_google')->load([
                'token' => $this->input['gg_token']
            ]);

            if ($result['vars'] ?? false) {
                $social = true;
                $user_info = $result['vars'];
            } else {
                throw new \RuntimeException('Không thể đăng nhập qua google. Bạn hãy thử lại', 400);
            }

            $user = new User();
        } else {
            if (!$this->input['username'] || !$this->input['password']) {
                throw new \RuntimeException('Thông tin đăng nhập không đúng', 401);
            }
            $user = new User($this->input['username'], $this->input['password']);
        }

        if ($user->logged == 1) {
            return [
                'user_id' => $user->u_id,
                'active' => 1,
                'access_token' => JWT::encode([
                    'user_id' => $user->u_id
                ], config('app.jwt_key'), 'HS256')
            ];
        } else {

            if ($user_info ?? false) {
            } else {
                $result = model('users/find_by_username')->load([
                    'username' => $this->input['username'],
                    'password' => $this->input['password']
                ]);
                $user_info = $result['vars'];
            }


            $user = $user_info ?? [];

            if ($user) {
                return $user;
            }

            throw new \RuntimeException('Bạn nhập sai tên đăng nhập hoặc mật khẩu', 401);
        }
    }

    /**
     * Lấy thông tin user
     */
    public function getProfile() {

        if (!app('auth')->logged) {
            throw new \RuntimeException('Access token wrong', 400);
        }

        $user = $this->userRepository->getById(app('auth')->u_id);

        return $user->toArray();
    }

    public function postRegister() {

        $result = model('users/register')->load($this->input);

        return $result['vars'];
    }

    public function postConfirmCode() {
        $result = model('users/confirm_register_code')->load([
            'user_id' => $this->input['user_id'] ?? 0,
            'code' => $this->input['code'] ?? 0
        ]);

        if ($result['vars']) {
            return [
                'user_id' => $this->input['user_id'],
                'access_token' => JWT::encode([
                    'user_id' => $this->input['user_id']
                ], config('app.jwt_key'), 'HS256')
            ];
        } else {
            throw new \Exception('Mã xác thực không đúng', 400);
        }
    }

    public function postForgotPassword() {
        $phone = $this->input['phone'] ?? '';

        $result = model('users/forgot_password')->load([
            'phone' => $phone
        ]);

        if (!$result['vars']) {
            throw new \Exception('Có lỗi xảy ra. Bạn vui lòng thử lại', 500);
        }

        return [];
    }

    public function postConfirmForgotPasswordCode() {

        $code = $this->input['code'] ?? '';
        $phone = $this->input['phone'] ?? '';
        $password = $this->input['password'] ?? '';
        $re_password = $this->input['re_password'] ?? '';

        if (!$password || !$re_password || $password != $re_password) {
            throw new \Exception('Password chưa đúng', 400);
        }

        $result = model('users/confirm_forgot_password_code')->load([
            'phone' => $phone,
            'code' => $code,
            'password' => $password
        ]);

        if (!$result['vars']) {
            throw new \Exception('Có lỗi xảy ra. Bạn vui lòng thử lại', 500);
        }

        return $result['vars'];
    }

    public function putProfile() {

        $result = model('users/update')->load([
            'id' => app('auth')->u_id
        ] + $this->input);

        return $result['vars'];
    }

    public function postResendConfirmCode() {
        $result = model('users/resend_confirm_code')->load([
            'user_id' => $this->input['user_id']
        ]);

        return $result['vars'];
    }

    public function putUpdateRefCode() {

        $result = model('users/update_ref_code')->load([
            'ref_code' => $this->input['ref_code'],
            'id' => $this->input['id']
        ]);

        return $result['vars'];
    }

    public function putChangePhone() {

        $result = model('users/update_phone')->load([
            'user_id' => app('auth')->u_id
        ] + $this->input);

        return $result['vars'];
    }

    public function postConfirmChangePhone() {

        $result = model('users/confirm_update_phone')->load([
            'user_id' => app('auth')->u_id
        ] + $this->input);

        return $result['vars'];
    }

    public function putChangeRefCode() {
        $result = model('users/change_ref_code')->load([
            'user_id' => app('auth')->u_id
        ] + $this->input);

        return $result['vars'];
    }
}

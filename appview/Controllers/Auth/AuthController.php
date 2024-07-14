<?php
/**
 * Created by PhpStorm.
 * User: ntdinh1987
 * Date: 4/24/2017
 * Time: 2:44 PM
 */

namespace AppView\Controllers\Auth;


use AppView\Repository\UserRepository;
use VatGia\ControllerBase;
use VatGia\Helpers\Facade\FlashMessage;
use VatGia\Helpers\IDVGHelpers;
use AppView\Helpers\User;

/**
 * Class AuthController
 * @package AppView\Controllers\Auth
 */
class AuthController extends ControllerBase
{

    protected $useOAuth2 = false;

    protected $idvgHelper;

    /**
     * AuthController constructor.
     *
     * Mặc định VNP Framework sẽ login bằng id.vatgia.com
     */
    public function __construct()
    {
        $this->idvgHelper = new IDVGHelpers(config('auth.idvg'));
    }

    /**
     * @return string
     */
    public function showLoginForm()
    {
        if (property_exists($this, 'useOAuth2') && $this->useOAuth2 == true) {
            return redirect($this->idvgHelper->loginRedirectLink('/'));
        }

        return view('auth/login')->render();
    }

    public function loginCallback()
    {
        $accessCode = getValue('access_code', 'str');

        if (!$accessCode) {
            return redirect('/');
        }

        $accessToken = $this->idvgHelper->getAccessTokenFromAccessCode($accessCode);

        if (!$accessToken) {
            return redirect('/login');
        }

        $loginCheck = model('login/login_with_idvg_access_token')->load([
            'access_token' => $accessToken
        ]);

        if (true === $loginCheck['vars']['success']) {
            return redirect('/');
        } else {
            return redirect('/');
        }

    }

    public function logout()
    {
        app('user')->logout();

        return redirect($this->idvgHelper->logoutLink());
    }

    public function showProfile()
    {
        return redirect('https://id.vatgia.com/v2/thiet-lap');
    }

    public function register($referer_id)
    {

        $parent = (new UserRepository())->getById($referer_id);
        if ($parent) {
            return view('register')->render(get_defined_vars());
        }
    }

    public function postRegister()
    {

        try {
            $result = repository('users/register')->post($_POST);
            return FlashMessage::success('Đăng ký tài khoản thành công', url('/products'));
        } catch (\Exception $e) {
            return FlashMessage::error($e->getMessage(), url_back());
        }

    }
    public function dangkythanhvien()
    {
        if((isset($_POST['g_recaptcha']) && !empty($_POST['g_recaptcha']))){
            $secret = '6LcRnCwfAAAAAB8En9mN2Yi6RRjrbWh7YGP0MHSy'; // Key của site
            $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g_recaptcha']);
            $responseData = json_decode($verifyResponse);
            if($responseData->success)
            {
                $result = repository('users/dangkythanhvien')->post($_POST);
                echo json_encode($result['vars']);die;
            }else{
                $result['suscess'] = 0;
                $result['message'] = 'Recaptcha không đúng';
                echo json_encode($result);die;
            }
        }else{
            $result['suscess'] = 0;
            $result['message'] = 'Bạn phải chọn recaptcha';
            echo json_encode($result);die;
        }
    }
    public function postLogin(){
        try {
            if (!$_POST['username'] || !$_POST['password']) {
                return FlashMessage::error('Thông tin đăng nhập không đúng', url_back());
            }
            $user = new User($_POST['username'], $_POST['password']);
            if ($user->logged == 1) {
                $_SESSION["loggedFe"] = 1;
                $_SESSION["userIdFe"] = $user->u_id;
                $_SESSION["userNameFe"] = $user->use_name;
                $_SESSION["userLoginFe"] = $user->login_name;
                return FlashMessage::error('Đăng nhập thành công', '/products');
                //$_SESSION["password"] = md5($password);
            }else{
                return FlashMessage::error('Bạn nhập sai tên đăng nhập hoặc mật khẩu', url_back());
            }
        } catch (\Exception $e) {
            return FlashMessage::error($e->getMessage(), url_back());
        }
    }
    public function loyalClient(){
        return view('auth/loyalclient')->render();
    }
    public function introduce(){
        return view('static/introduce')->render();
    }
    public function companion(){
        return view('static/companion')->render();
    }
    public function guideOrder(){
        return view('auth/guideOrder')->render();
    }
    public function dieuKhoanSuDung(){
        return view('static/dieukhoansudung')->render();
    }
    public function chinhSachBaoMat(){
        return view('static/chinhSachBaoMat')->render();
    }
    public function chinhSachVanChuyen(){
        return view('static/chinhSachVanChuyen')->render();
    }
    public function chinhSachkhieuNai(){
        return view('static/chinhsachkhieunai')->render();
    }
    public function kiemHangVaDoiTra(){
        return view('static/kiemhangvadoitra')->render();
    }
}
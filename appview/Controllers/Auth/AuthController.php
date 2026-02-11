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
use db_query;

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
    public function showLoginHapu()
    {
        return view('auth/loginHapu')->render();
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
		//@session_start();
		if (isset($_SESSION["loggedFe"])) unset($_SESSION["loggedFe"]);
		if (isset($_SESSION["userIdFe"])) unset($_SESSION["userIdFe"]);
		if (isset($_SESSION["userNameFe"])) unset($_SESSION["userNameFe"]);
		if (isset($_SESSION["userLoginFe"])) unset($_SESSION["userLoginFe"]);
		//session_destroy();
		//session_unset();
		//$_SESSION["productHungdv"] = 1;
		return redirect(url('login'));
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
            $remember = isset($_POST['remember']);
            $user = new User($_POST['username'], $_POST['password']);
            if ($user->logged == 1) {
                ini_set('session.gc_maxlifetime', 21600); // 6 giờ (21600 giây)
                ini_set('session.cookie_lifetime', 21600); // 6 giờ (21600 giây)
                session_start();
                $_SESSION["loggedFe"] = 1;
                $_SESSION["userIdFe"] = $user->u_id;
                $_SESSION["userNameFe"] = $user->use_name;
                $_SESSION["userLoginFe"] = $user->login_name;
                if ($remember) {
                    // Lưu username và password vào cookie (7 ngày)
                    $encodedPassword = base64_encode($_POST['password']);
                    setcookie("username", $_POST['username'], time() + (7 * 24 * 60 * 60), "/");
                    setcookie("password", $encodedPassword, time() + (7 * 24 * 60 * 60), "/");
                } else {
                    // Xóa cookie nếu "Ghi nhớ" không được chọn
                    setcookie("username", "", time() - 3600, "/");
                    setcookie("password", "", time() - 3600, "/");
                }
        
                return FlashMessage::error('Đăng nhập thành công', '/order-fast');
                //$_SESSION["password"] = md5($password);
            }else{
                return FlashMessage::error('Bạn nhập sai tên đăng nhập hoặc mật khẩu', url_back());
            }
        } catch (\Exception $e) {
            return FlashMessage::error($e->getMessage(), url_back());
        }
    }
    public function postLoginHapu(){
        try {
            if (!$_POST['username'] || !$_POST['password']) {
                return FlashMessage::error('Thông tin đăng nhập không đúng', url_back());
            }
            ini_set('session.gc_maxlifetime', 11600); // 6 giờ (21600 giây)
            ini_set('session.cookie_lifetime', 11600); // 6 giờ (21600 giây)
            
            $remember = isset($_POST['remember']);
            $username = $_POST['username'];
            $password = $_POST['password'];
            $user_id = checkLoginHapu($username, $password);
            
            if ($user_id != 0) {
                $isAdmin = 0;
                $db_isadmin = new db_query("SELECT adm_isadmin FROM admin_user WHERE adm_id = " . $user_id);
                $row = $db_isadmin->fetch(true);

                if ($row["adm_isadmin"] != 0) $isAdmin = 1;
                //Set SESSION
                $_SESSION["loggedHapu"] = 1;
                $_SESSION["userHapu_id"] = $user_id;
                $_SESSION["userloginHapu"] = $username;
                $_SESSION["passwordHapu"] = md5($password);
                $_SESSION["isAdminHapu"] = $isAdmin;
                
                $encodedPassword = base64_encode($password);
                setcookie("userloginHapu", $username, time() + (7 * 24 * 60 * 60), "/");
                setcookie("passwordHapu", $encodedPassword, time() + (7 * 24 * 60 * 60), "/");
                    
                
                unset($db_isadmin);
                return FlashMessage::error('Đăng nhập thành công', '/order-list');
            } else {
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
	public function van_chuyen_va_giao_nhan(){
        return view('static/van_chuyen_va_giao_nhan')->render();
    }
	public function chinh_sach_bao_mat_thong_tin(){
        return view('static/chinh_sach_bao_mat_thong_tin')->render();
    }
	public function xu_ly_khieu_nai(){
        return view('static/xu_ly_khieu_nai')->render();
    }
	public function chinh_sach_kiem_hang(){
        return view('static/chinh_sach_kiem_hang')->render();
    }
	
	public function chinh_sach_doi_tra(){
        return view('static/chinh_sach_doi_tra')->render();
    }
	
	public function chinh_sach_thanh_toan(){
        return view('static/chinh_sach_thanh_toan')->render();
    }
    public function cumulativeSales(){
        if (!checkLoginFe()) {
            return redirect(url('login'));
        }
        $start_date = date('Y-m-01', strtotime('-12 months'));
        $end_date = date('Y-m-t');
		
        $response = repository('order/get_order_by_user_id')->get([
            'user_id' => (int)$_SESSION["userIdFe"],
            'end_date'=> $end_date,
            'start_date'=> $start_date,
            'page_size'=> 1000,
        ]);
        $monthlyTotals = [];
        $total_money = 0;
        if(check_array($response['vars']['data'])){
            $monthlyTotals = $this->calculateMonthlyAmounts($response['vars']['data']);
            $total_money = $response['vars']['meta']['total_money_display'];
        }
        return view('auth/cumulativeSales')->render([
            'monthlyTotals'=>$monthlyTotals,
            'total_money'=>$total_money,
        ]);
    }
    
    function calculateMonthlyAmounts($data) {
        $monthlyTotals = [];
        
        $currentDate = new \DateTime(); // Lùi về cuối tháng trước

        // Lặp qua 12 tháng trước
        for ($i = 0; $i < 13; $i++) {
            // Lấy tháng hiện tại với định dạng 'Y-m'
            $formattedMonth = $currentDate->format('Y-m');

            // Gắn dữ liệu giả lập (ví dụ: tổng giá trị đơn hàng là một số ngẫu nhiên)
            $monthlyTotals[$formattedMonth] = 0; // Giá trị ngẫu nhiên từ 1000 đến 5000

            // Lùi về tháng trước
            $currentDate->modify('-1 month');
        }

        foreach ($data as $item) {
            // Kiểm tra xem updated_at có phải là một đối tượng DateTime
            if ($item['updated_at']) {
                $updatedAt = $item['created_at'];
                

                // Lấy tháng và năm từ updated_at
                $month = $updatedAt->format('Y-m'); // Định dạng 'YYYY-MM'

                // Cộng dồn amount cho tháng đó
                if (!isset($monthlyTotals[$month])) {
                    $monthlyTotals[$month] = 0;
                }
                $monthlyTotals[$month] += $item['amount'];
            }
        }

        // Sắp xếp theo tháng (tăng dần)
        ksort($monthlyTotals);

        return $monthlyTotals;
    }
    public function giveaway(){
        return view('static/giveaway')->render();
    }
}
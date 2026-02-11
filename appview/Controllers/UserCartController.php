<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2/26/2019
 * Time: 1:47 PM
 */

namespace AppView\Controllers;

use VatGia\ControllerBase;
use AppView\Repository\UserRepository;

class UserCartController extends FrontEndController
{
    protected $userRepository;
    
    /**
     * ProductController constructor.
     * @param categoryRepository $post
     */
    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
    }

    public function index(){
        if (!checkLoginFe()) {
            return redirect(url('login'));
        }

        $result = model('user_cart/index')->load([
            'user_id' => $_SESSION['userIdFe']
        ]);

        $user = $this->userRepository->getById(intval($_SESSION['userIdFe']));

        return view('products/cart')->render([
            'result'=>$result,
            'user'=>$user
        ]);
    }
	
	public function indexProduct(){
        if (!checkLoginFe()) {
            return redirect(url('login'));
        }

        $result = model('user_cart/index')->load([
            'user_id' => $_SESSION['userIdFe']
        ]);

        $user = $this->userRepository->getById(intval($_SESSION['userIdFe']));

        return view('products/cart2')->render([
            'result'=>$result,
            'user'=>$user
        ]);
    }
    public function indexProductOrderFast(){
        if (!checkLoginFe()) {
            return redirect(url('login'));
        }
        $result = model('user_cart/index')->load([
            'user_id' => $_SESSION['userIdFe']
        ]);

        $user = $this->userRepository->getById(intval($_SESSION['userIdFe']));

        return view('products/orderFast')->render([
            'result'=>$result,
            'user'=>$user
        ]);
    }
    public function orderReview(){
        if (!checkLoginFe()) {
            return redirect(url('login'));
        }
        $result = model('order/get_order_new_by_user_id')->load([
            'user_id' => $_SESSION['userIdFe']
        ]);
        $user = $this->userRepository->getById(intval($_SESSION['userIdFe']));

        return view('products/orderReview')->render([
            'result'=>$result,
            'user'=>$user
        ]);
    }
    public function orderListHapu(){
        if (!checkLoginFeHapu()) {
            return redirect(url('/'));
        }
        $result = model('order/get_order_new_by_user_admin_id')->load([
            'adm_id' => $_SESSION['userHapu_id']
        ]);
        $view = 'products/orderReviewHapu';
        if($_SESSION['userHapu_id'] == 109){
            $view = 'products/orderCheckHapu';
        }
        return view($view)->render([
            'result'=>$result
        ]);
    }
    public function orderDetailHapu($id){
        if (!checkLoginFeHapu()) {
            return redirect(url('/'));
        }
        $result = model('order/get_order_by_id')->load([
            'id' => $id
        ]);
        //$order = App\Models\Order::findByID($id);
        //pre
        $view = 'products/orderReviewDetailHapu';
        if($_SESSION['userHapu_id'] == 109){
            $view = 'products/orderCheckDetailHapu';
        }
        return view($view)->render([
            'result'=>$result,
            'idOrder'=>$id
        ]);
        
    }
    public function orderDetailCheckedHapu($id){
        if (!checkLoginFeHapu()) {
            return redirect(url('/'));
        }
        $result = model('order/get_order_by_id')->load([
            'id' => $id
        ]);
        return view("products/orderCheckedDetailHapu")->render([
            'result'=>$result,
            'idOrder'=>$id
        ]);
    }
    public function myOrder(){
        if (!checkLoginFe()) {
            return redirect(url('/'));
        }
        $data = model('order/orders')->load([
            'user_id' => (int)$_SESSION['userIdFe']
        ]);
        
        $data_new = model('order/orders')->load([
            'user_id' => (int)$_SESSION['userIdFe'],
            'ord_status_code'=>"NEW",
            'page_size'=>1,
        ]);
        $totalNew = 0;
        if($data && isset($data_new['vars']['data'])){
            $totalNew = $data_new['vars']['meta']['pagination']['total'];
        }
        $dataPending = model('order/orders')->load([
            'user_id' => (int)$_SESSION['userIdFe'],
            'ord_status_code'=>"PENDING",
            'page_size'=>1,
        ]);
        $totalPending = 0;
        if($dataPending && isset($dataPending['vars']['data'])){
            $totalPending = $dataPending['vars']['meta']['pagination']['total'];
        }
        $dataSucess = model('order/orders')->load([
            'user_id' => (int)$_SESSION['userIdFe'],
            'ord_status_code'=>"SUCCESS",
            'page_size'=>1,
        ]);
        $totalSucess = 0;
        if($dataSucess && isset($dataSucess['vars']['data'])){
            $totalSucess = $dataSucess['vars']['meta']['pagination']['total'];
        }
        $dataTransported = model('order/orders')->load([
            'user_id' => (int)$_SESSION['userIdFe'],
            'ord_status_code'=>"BEING_TRANSPORTED",
            'page_size'=>1,
        ]);
        $totalTransported = 0;
        if($dataTransported && isset($dataTransported['vars']['data'])){
            $totalTransported = $dataTransported['vars']['meta']['pagination']['total'];
        }
        
        $pagination = [];
        if($data && isset($data['vars']['data'])){
            $pagination  = collect_recursive($data['vars']['meta']['pagination']);
        }
        return view('products/myOrder')->render([
            'result'=>$data,
            'pagination'=>$pagination,
            'totalNew'=>$totalNew,
            'totalTransported'=>$totalTransported,
            'totalSucess'=>$totalSucess,
            'totalPending'=>$totalPending,
        ]);
    }
    public function myOrderDetail($id){
        if (!checkLoginFe()) {
            return redirect(url('login'));
        }
        $result = model('order/get_order_by_id')->load([
            'id' => $id
        ]);
        return view('products/orderReview')->render([
            'result'=>$result
        ]);
    }
    public function myProfileBusiness(){
        if (!checkLoginFe()) {
            return redirect(url('login'));
        }
        $userId = $_SESSION['userIdFe'];
        $user = $this->userRepository->getById(intval($userId));
        return view('auth/myProfileBusiness')->render([
            'user'=>$user
        ]);
    }
    public function postMyProfileBusiness(){
        if (!checkLoginFe()) {
            return redirect(url('login'));
        }
        $userId = $_SESSION['userIdFe'];
        $uploadDir = ROOT . "/public/upload/users/";
        $fs_extension = "gif,jpg,jpe,jpeg,png";
        $fs_filesize = 10000;
        // Thư mục lưu file upload
        
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Cấu hình
        $maxFileSize = 5 * 1024 * 1024; // 5MB
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf'];

        $response = [
            'status' => 'success',
            'message' => 'Upload thành công!',
            'files' => [],
            'errors' => []
        ];
        
        // Hàm kiểm tra file
        function isAllowedFile($fileName, $fileSize, $allowedExtensions, $maxFileSize, &$errorMsg)
        {
            $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (!in_array($ext, $allowedExtensions)) {
                $errorMsg = "File '$fileName' không hợp lệ. Chỉ chấp nhận: " . implode(', ', $allowedExtensions);
                return false;
            }

            if ($fileSize > $maxFileSize) {
                $errorMsg = "File '$fileName' vượt quá dung lượng cho phép (" . round($maxFileSize / 1024 / 1024) . "MB).";
                return false;
            }

            return true;
        }

        // Duyệt toàn bộ các file upload
        foreach ($_FILES as $key => $fileData) {
            if (is_array($fileData['name'])) {
                // Nhiều file (vd: cccd[])
                for ($i = 0; $i < count($fileData['name']); $i++) {
                    $fileName = basename($fileData['name'][$i]);
                    $tmpName = $fileData['tmp_name'][$i];
                    $fileSize = $fileData['size'][$i];
                    $errorMsg = '';
                    if (!isAllowedFile($fileName, $fileSize, $allowedExtensions, $maxFileSize, $errorMsg)) {
                        $response['errors'][] = $errorMsg;
                        continue;
                    }
                    $fileName = uniqid() . '_' . $fileName;
                    $targetFile = $uploadDir . $fileName;
                    if (move_uploaded_file($tmpName, $targetFile)) {
                        $response[$key]['files'][] = $fileName;
                    } else {
                        $response[$key]['errors'][] = "Không thể lưu file '$fileName'.";
                    }
                }
            } else {
                // Một file duy nhất
                $fileName = basename($fileData['name']);
                $tmpName = $fileData['tmp_name'];
                $fileSize = $fileData['size'];
                $errorMsg = '';

                if (!isAllowedFile($fileName, $fileSize, $allowedExtensions, $maxFileSize, $errorMsg)) {
                    $response['errors'][] = $errorMsg;
                    continue;
                }
                $fileName = uniqid() . '_' . $fileName;
                $targetFile = $uploadDir . $fileName;
                if (move_uploaded_file($tmpName, $targetFile)) {
                    $response[$key]['files'] = $fileName;
                } else {
                    $response[$key]['errors'] = "Không thể lưu file '$fileName'.";
                }
            }
        }

        // Cập nhật status nếu có lỗi
        if (!empty($response['errors'])) {
            $response['status'] = 'error';
            $response['message'] = 'Một số file không được upload.';
        }
        $data['tax_code'] = $_REQUEST['tax_code'];
        $data['id'] = $userId;
        if(isset($response['cccd'])){
            $data['cccd'] = implode(',', $response['cccd']['files']);
        }
        if(isset($response['business_license'])){
            $data['business_license'] = $response['business_license']['files'];
        }
        if(isset($response['pharma_license'])){
            $data['pharma_license'] = $response['pharma_license']['files'];
        }
        if(isset($response['gpp_cert'])){
            $data['gpp_cert'] = $response['gpp_cert']['files'];
        }
        $result = model('users/my-license-business')->load($data);
        if($result['vars']){
            $result['message'] = '';
        }
        return json_encode($result);
    }
}
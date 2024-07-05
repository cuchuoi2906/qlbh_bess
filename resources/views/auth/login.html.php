<?php
include dirname(__FILE__) . '/../includes/header.html.php';
?>
<div class="main-content">
    <div class="container">
        <div class="login-page">
            <div class="row">
                <div class="col-xl-6">
                    <img class="w-100" src="<?= asset('/images/login.png') ?>" alt="login" />
                </div>
                <div class="col-xl-6 d-flex justify-content-center flex-column p-3 p-xl-5">
                    <h2 class="form-title mb-4">Đăng Nhập</h2>
                    <?php \VatGia\Helpers\Facade\FlashMessage::display(); ?>
                    <form action="<?= url('post.login') ?>" method="POST" class="form-login">
                        <div class="form-floating mb-3">
                            <input type="text" name="username" class="form-control" id="floatingInput" placeholder="account" />
                            <label for="floatingInput">Tên đăng nhập</label>
                        </div>
                        <div class="form-floating">
                            <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password" />
                            <label for="floatingPassword">Mật khẩu</label>
                        </div>
                        <div class="d-flex justify-content-between my-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" />
                                <label class="form-check-label" for="flexCheckDefault"> Lưu đăng nhập </label>
                            </div>
                            <!--<a href="/" class="forgot-password">Quên mật khẩu?</a>-->
                        </div>
                        <div class="btn-custom"><button class="btn btn-secondary" type="submit">Đăng nhập</button></div>
                        
                    </form>
                    <div class="text-center mt-4" style="font-size: 16px;">Bạn chưa có tài khoản? <a class="forgot-password"  href="/#formdangky">Đăng ký</a></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include dirname(__FILE__) . '/../includes/footer.html.php';
?>
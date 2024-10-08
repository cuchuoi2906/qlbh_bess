<?php
include dirname(__FILE__) . '/../includes/header.html.php';
?>
<div class="main-content">
    <div class="container">
		<div class="row">
			<div class="col-xl-7">
				<img class="w-100 mobile-login d-none" src="<?= asset('/images/banner_top1.png') ?>" alt="login" />
			</div>
		</div>
        <div class="login-page">
            <div class="row">
                <div class="col-xl-6">
                    <img class="w-100" src="<?= asset('/images/login.png') ?>" alt="login" />
                </div>
                <div class="col-xl-6 d-flex justify-content-center flex-column p-3 p-xl-5">
                    <h2 class="form-title mb-2">Đăng Nhập</h2>
                    <?php \VatGia\Helpers\Facade\FlashMessage::display(); ?>
                    <form action="<?= url('post.login') ?>" method="POST" class="form-login">
                        <div class="form-floating mb-2">
                            <input type="text" name="username" class="form-control" id="floatingInput" placeholder="account" />
                            <label for="floatingInput">Tên đăng nhập hoặc Số điện thoại</label>
                        </div>
						<!--<div class="form-floating my-2 input-group password-input-control-container">
							<input type="password" name="password" name="password" class="password-input-control" id="password" placeholder="Mật khẩu">
							<div class="input-group-append">
								<span class="input-group-text" onclick="togglePassword()">
									<i class="fas fa-eye" id="toggleIcon"></i>
								</span>
							</div>
							<label for="floatingInput">Mật khẩu</label>
						</div>-->
						<div class="form-group password-input-control">
							<div class="input-group">
								<input type="password" name="password" class="form-control" id="password" placeholder="Nhập mật khẩu">
								<div class="input-group-append">
									<span class="input-group-text" onclick="togglePassword()">
										<i class="fas fa-eye" id="toggleIcon"></i>
									</span>
								</div>
							</div>
						</div>
						<script>
							function togglePassword() {
								var passwordField = document.getElementById("password");
								var toggleIcon = document.getElementById("toggleIcon");

								// Toggle between showing/hiding the password
								if (passwordField.type === "password") {
									passwordField.type = "text";
									toggleIcon.classList.remove("fa-eye");
									toggleIcon.classList.add("fa-eye-slash");
								} else {
									passwordField.type = "password";
									toggleIcon.classList.remove("fa-eye-slash");
									toggleIcon.classList.add("fa-eye");
								}
							}
						</script>
                        <!--<div class="d-flex justify-content-between my-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" />
                                <label class="form-check-label" for="flexCheckDefault"> Lưu đăng nhập </label>
                            </div>
                            <!--<a href="/" class="forgot-password">Quên mật khẩu?</a>-->
                        <!--</div>-->
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
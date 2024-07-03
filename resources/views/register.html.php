<!doctype html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Đăng ký thành viên</title>
    <!--<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">-->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400;1,600;1,700&display=swap"
          rel="stylesheet">
    <link rel="icon" href="<?= asset('invite/images/favicon.png') ?>">
    <link rel="stylesheet" href="<?= asset('invite/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('invite/css/select2.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('invite/css/style.css') ?>">
</head>
<body class="page__register">
<header>
    <nav class="navbar navbar-expand-lg navbar-light custom__nav">
        <a class="navbar-brand" href="#"><img src="<?= asset('invite/images/logo.png') ?>" alt="Logo"></a>
        <div class="box__info__mentor">
            <p>Tham gia 3Do</p>
            <p>Người giới thiệu: <span class="box__info_mentor__name"><?= $parent->name ?></span></p>
            <!--            <p>Bước 2 của 2</p>-->
        </div>
    </nav>
</header>

<main class="bg__shadow">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="box__invite">
                    <?php \VatGia\Helpers\Facade\FlashMessage::display(); ?>
                    <form action="<?= url('post.register') ?>" method="POST" class="box__form">
                        <h1 class="form__title">Tạo tài khoản</h1>
                        <p class="form__teaser">Điền đầy đủ thông tin dưới đây để tham gia 3Do</p>
                        <input type="hidden" name="referral_code" value="<?= $parent->id ?>">
                        <div class="form-group">
                            <label for="firstName">Họ và tên</label>
                            <input type="text" name="name" class="form-control" id="name" placeholder="">
                            <!--<small class="form-text text-muted">We'll never share your email with anyone else.</small>-->
                        </div>
                        <div class="form-group">
                            <label for="phone">Số điện thoại</label>
                            <input type="text" name="phone" class="form-control" id="phone" placeholder="">
                        </div>
                        <div class="form-group">
                            <label for="email">Mật khẩu</label>
                            <input type="password" name="password" class="form-control" id="password"
                                   placeholder="Nhật mật khẩu">
                        </div>
                        <div class="form-group">
                            <label for="email">Mật khẩu</label>
                            <input type="password" name="re_password" class="form-control" id="re_password"
                                   placeholder="Nhập lại mật khẩu">
                        </div>

                        <!--                        <label class="label__title">Thông tin cá nhân</label>-->

                        <? /*
                        <div class="form-group">
                            <label>Ngày sinh</label>
                            <div class="group__birthday">
                                <select class="col-12 col-md-4 form-control select2" data-placeholder="Ngày"
                                        name="days">
                                    <option value="1">1</option>
                                </select>
                                <select class="col-12 col-md-4 form-control select2" data-placeholder="Tháng"
                                        name="month">
                                    <option value="1">Tháng 1</option>
                                    <option value="2">Tháng 2</option>
                                    <option value="3">Tháng 3</option>
                                    <option value="4">Tháng 4</option>
                                    <option value="5">Tháng 5</option>
                                    <option value="6">Tháng 6</option>
                                    <option value="7">Tháng 7</option>
                                    <option value="8">Tháng 8</option>
                                    <option value="9">Tháng 9</option>
                                    <option value="10">Tháng 10</option>
                                    <option value="11">Tháng 11</option>
                                    <option value="12">Tháng 12</option>
                                </select>
                                <select class="col-12 col-md-4 form-control select2" data-placeholder="Năm" name="year">
                                    <option value="1">2010</option>
                                </select>


                            </div>
*/ ?>
                        <!--                        <div class="form-group">-->
                        <!--                            <label for="address">Địa chỉ</label>-->
                        <!--                            <input type="text" name="address" class="form-control" id="address" placeholder="">-->
                        <!--                        </div>-->


                        <br>
                        <? /*
                        <label class="label__title">Tài liệu thỏa thuận pháp lý</label>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="agree">
                            <label class="form-check-label" for="agree">Trước khi bạn tiếp tục với mẫu đơn đăng ký mở
                                tài khoản để trở thành Đại lý cuả Dododo24h, vui lòng xem kỹ Điều Khoản và Điều Kiện (“Điều Khoản và Điều Kiện”) bằng cách
                                <a href="#">nhấp vào đây</a>. Các Điều Khoản và Điều Kiện này quy định rõ các quyền và
                                nghĩa vụ khi là một Khách Hàng Thân Thiết.</label>
                            <label for="" class="error">Phải đồng ý Chính Sách Kinh Doanh, Lưu ý Bảo Mật, Tóm tắt Chính
                                Sách Trả Thưởng và Chính Sách Hoàn Trả Sản Phẩm.</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="promotions">
                            <label class="form-check-label" for="promotions">Tôi đã đọc, hiểu và đồng ý tham gia Chương
                                Trình Khách Hàng Thân Thiết.</label>
                        </div>
                        <br>
*/ ?>
                        <button style="margin-bottom: 50px" type="submit" class="btn invite__submit">Tạo tài khoản mới
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<footer class="footer">
</footer>

</body>
</html>
<script src="<?= asset('invite/js/jquery.min.js') ?>"></script>
<script src="<?= asset('invite/js/bootstrap.min.js') ?>"></script>
<script src="<?= asset('invite/js/select2.min.js') ?>"></script>
<script>
    $(document).ready(function () {
        $('.select2').select2();
    });
</script>

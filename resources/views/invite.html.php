<!doctype html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Chấp nhận lời mời</title>
    <!--<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">-->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400;1,600;1,700&display=swap"
          rel="stylesheet">
    <link rel="icon" href="<?= asset('invite/images/favicon.png') ?>">
    <link rel="stylesheet" href="<?= asset('invite/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('invite/css/style.css') ?>">
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-light custom__nav">
        <a class="navbar-brand" href="#"><img src="<?= asset('invite/images/logo.png') ?>" alt="Logo"></a>
    </nav>
</header>

<main class="bg__shadow">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="box__invite text-center">
                    <?php if ($parent ?? false): ?>
                    <p class="invite__welcome">Chúc mừng!</p>
                    <?php else: ?>
                        <?php \VatGia\Helpers\Facade\FlashMessage::display(); ?>
                    <?php endif; ?>

                    <img src="<?= asset('invite/images/logo.png') ?>" class="invite_image" alt="Logo">
                    <?php if ($parent ?? false): ?>
                        <h1 class="invite__title"><span class="invite__name"><?= $parent->name ?></span>
                            đã mời bạn tham gia cộng đồng <strong class="invite__name">3Do</strong></h1>
                        <a href="<?= url('register', ['referer_id' => $parent->id]) ?>" class="invite__submit">Chấp nhận
                            lời mời</a>
                    <?php else: ?>

                    <?php endif; ?>
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
<!--<script src="js/custom.js"></script>-->

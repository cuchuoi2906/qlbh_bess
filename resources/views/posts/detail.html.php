<!DOCTYPE html>
<html lang="en-US">
<head>
    <title><?= $item->title ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="Keywords"
          content="">
    <meta name="Description"
          content="">

</head>
<body style="padding: 0 15px;">

<h1><?= $item->title ?></h1>
<div>
    <?= $item->content ?>
    <style>
        li{
            white-space: normal !important;
        }
        ul{
            padding: 0;
        }
    </style>
</div>
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</body>
</html>


@extends('module-master')

@section('content')
    <div class="container-fluid">
        <?php
        $form = new form();
        $form->create_form("add", $fs_action, "post", "multipart/form-data", 'onsubmit="validateForm(); return false;"');
        ?>
        <?php if($fs_errorMsg): ?>
        <div class="row">
            <div class="col-xs-12">
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-ban"></i> Có lỗi!</h4>
                    {!! $form->errorMsg($fs_errorMsg) !!}
                </div>
            </div>
        </div>
        <?php endif ?>

        <div class="row">
            <div class="col-xs-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Thông tin cơ bản</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <?//= $form->select("Loại danh mục", "cat_type", "cat_type", $type_arr, $cat_type, "") ?>
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                @foreach($locales as $code => $locale)
                                <li class="{{$code=='vn'?'active':''}}">
                                    <a href="#tab_content_{{$code}}" data-toggle="tab"
                                       aria-expanded="true">{{$locale}}</a>
                                </li>
                                @endforeach
                            </ul>
                            <div class="tab-content">
                                @foreach($locales as $code => $locale)
                                <div class="tab-pane {{$code=='vn'?'active':''}}" id="tab_content_{{$code}}">

                                    <?= $form->text("Tên sản phẩm", "pro_name_".$code, "pro_name_".$code, html_entity_decode(${'pro_name_'.$code}), "Tên sản phẩm", 1, "", "", 255, "", "", "") ?>
                                    <?= $form->textarea("Mô tả ngắn", "pro_teaser_".$code, "pro_teaser_".$code, html_entity_decode(${'pro_teaser_'.$code}), "Mô tả ngắn", 1, "", "", 255, "", "", "") ?>

                                </div>
                                @endforeach
                            </div>
                            <!-- /.tab-content -->
                        </div>


                    </div>
                </div>
            </div>

            <div class="col-xs-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Thông tin thêm</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <?= $form->text("Giá", "pro_price", "pro_price", $pro_price, "Giá", 1, "", "", 255, "", "", "") ?>
                        <?= $form->text("Giá khuyến mại", "pro_discount_price", "pro_discount_price", $pro_discount_price, "Giá khuyến mại", 1, "", "", 255, "", "", "") ?>
                        <?= $form->getFile('Ảnh sản phẩm', 'images', 'images[]', 'Ảnh sản phẩm', 0, 30, 'multiple') ?>

                    </div>
                </div>
            </div>

            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Ảnh</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <ul class="product-imgs">
                            @foreach($images as $image)
                                <li id="img_{{$image->id}}">
                                    <div class="panel panel-default">
                                        <div class="panel-body" style="position: relative">
                                            <img style="width: 100px" src="{{url() . '/upload/products/' . $image->file_name}}"/>
                                            <button onclick="delete_img({{$image->id}})"
                                                    type="button"
                                                    class="close"
                                                    aria-label="Close"
                                                    style="position: absolute; top: 3px; right: 3px;">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    {{--<div class="box-footer">--}}

                    {{--</div>--}}
                </div>
            </div>

            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Thông tin</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">

                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                @foreach($locales as $code => $locale)
                                    <li class="{{$code=='vn'?'active':''}}">
                                        <a href="#tab_content1_{{$code}}" data-toggle="tab"
                                           aria-expanded="true">{{$locale}}</a>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="tab-content">
                                @foreach($locales as $code => $locale)
                                    <div class="tab-pane {{$code=='vn'?'active':''}}" id="tab_content1_{{$code}}">

                                        <?=$form->wysiwyg('Chức năng', 'pro_functions_' . $code, html_entity_decode(${'pro_functions_'.$code})) ?>
                                        <?=$form->wysiwyg('Thông số kỹ thuật', 'pro_specifications_'.$code, html_entity_decode(${'pro_specifications_'.$code})) ?>
                                    </div>
                                @endforeach
                            </div>
                            <!-- /.tab-content -->
                        </div>

                    </div>
                    {{--<div class="box-footer">--}}

                    {{--</div>--}}
                </div>
            </div>
            <div class="col-xs-12">
                <div class="">
                    <div class="" style="text-align: center;">
                        <?= $form->button("submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "Cập nhật" . $form->ec . "Làm lại", "Cập nhật" . $form->ec . "Làm lại", ""); ?>
                        <?= $form->hidden("action", "action", "execute", ""); ?>
                        <?= $form->hidden("valradio", "valradio", 0) ?>
                    </div>
                </div>
            </div>
        </div>
        <?
        $form->close_form();
        unset($form);
        ?>
    </div>
@stop

<style>
    .product-imgs {
        list-style: none;
    }

    .product-imgs li {
        float: left;
        margin-right: 5px;
    }
</style>

@section('script')
    <script>
        $(function () {
            $('#pro_name').focus();
        });

        function delete_img(id) {
            if (confirm('Bạn có chắc chắn muốn xóa ảnh này?')) {
                $.ajax({
                    url: 'ajax_delete_product_image.php',
                    type: 'post',
                    data: {
                        image_id: id
                    },
                    success: function (response) {
                        $('#img_' + id).hide();
                    }

                });
            }
        }
    </script>
@stop
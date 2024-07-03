@extends('module-master')

@section('content')
    <div class="container-fluid">
        <?php
        $form = new form();
        $form->create_form("add", $fs_action, "post", "multipart/form-data", 'onsubmit="validateForm(); return false;"');
        ?>
        <?php
        if (AppView\Helpers\Facades\FlashMessage::hasMessages()) {
            AppView\Helpers\Facades\FlashMessage::display();
        }
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
                        <?= $form->select("Danh mục", "pro_category_id", "pro_category_id", $categories, $pro_category_id, "") ?>
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
                                    <!--                                        --><?//= $form->select("Hoa hồng trực tiếp", "pro_commission_plan_id", "pro_commission_plan_id", $commission_plans, $pro_commission_plan_id??0) ?>
                                    </div>
                                @endforeach
                                <?= $form->text("Mã sản phẩm", "pro_code", "pro_code", $pro_code??'', "Mã sản phẩm", 1, "", "", 255, "", "", "") ?>
                                <?= $form->text("Barcode", "pro_barcode", "pro_barcode", $pro_barcode??'', "Barcode", 0, "", "", 255, "", "", "") ?>
                                <?= $form->text("Tổng hoa hồng", "pro_commission", "pro_commission", $pro_commission??20, "Hoa hồng", 0, "", "", 255, "", "", "") ?>
                                <?= $form->text("Point", "pro_point", "pro_point", $pro_point??0, "Point", 0, "", "", 255, "", "", "") ?>
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

                        <div class="clear" style="clear: both; height: 10px;"></div>

                        <?= $form->select("Thương hiệu", "pro_brand_id", "pro_brand_id", [0 => 'Không chọn'] + $brands, $pro_brand_id ?? 0, "",1) ?>

                        <?= $form->number("Giá", "pro_price", "pro_price", $pro_price, "Giá", 1, "", "", 255, "", "", "") ?>
                        <?= $form->text("Giá khuyến mại", "pro_discount_price", "pro_discount_price", $pro_discount_price, "Giá khuyến mại", 0, "", "", 255, "", "", "") ?>
                        <?= $form->number("Số lượng", "pro_quantity", "pro_quantity", $pro_quantity??1, "Số lượng", 0, "", "", 255, "", "", "") ?>
                        <?= $form->select("Trạng thái", "pro_active", "pro_active", [1 => 'Hiển thị', 0 => 'Ẩn'], $pro_active, "") ?>
                        <?= $form->select("Tình trạng tồn kho", "pro_active_inventory", "pro_active_inventory", [1 => 'Còn hàng', 2 => 'Hết hàng'], $pro_active_inventory, "") ?>
                        <?= $form->select("Sản phẩm hot?", "pro_is_hot", "pro_is_hot", [1 => 'Hot', 0 => 'Không'], $pro_is_hot, "") ?>
                    </div>
                </div>
            </div>

            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Ảnh</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <?= $form->getFile('Chọn ảnh', 'images', 'images[]', 'Chọn ảnh', 0, 30, 'multiple') ?>
                        <ul class="product-imgs">
                            @foreach($images as $image)
                                <li id="img_{{$image->id}}">
                                    <div class="panel panel-default">
                                        <div class="panel-body" style="position: relative">
                                            <img style="width: 100px"
                                                 src="{{url() . '/upload/products/' . $image->file_name}}"/>
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
                        <h3 class="box-title">Video</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <?= $form->getFile('Chọn video', 'videoFile', 'videoFile', 'Chọn video', 0, 1, '') ?>
                        <input type="hidden" value="{{$pro_video_file_name}}" name="hdn_video_name" id="hdn_video_name" />
                        <?php
                        $style = 'style="display:none;"';
                        if($pro_video_file_name != ''){
                            $style = '';
                        }
                        ?>
                        <ul class="product-imgs" <?php echo $style; ?>>
                            <li id="video_id">
                                <div class="panel panel-default">
                                    <div class="panel-body" style="position: relative">
                                        <video width="500" height="200" controls="">
                                            <source src="{{url() . '/upload/products/' . $pro_video_file_name}}" type="video/mp4">
                                        </video>
                                        
                                        <button onclick="delete_img('video_id')"
                                                type="button"
                                                class="close"
                                                aria-label="Close"
                                                style="position: absolute; top: 3px; right: 3px;">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    {{--<div class="box-footer">--}}

                    {{--</div>--}}
                </div>
            </div>

            <?/*
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Sản phẩm con</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <?= $form->text("Kích thước", "pro_size[]", "pro_size", html_entity_decode(${'pro_name_'.$code}), "Tên sản phẩm", 1, "", "", 255, "", "", "") ?>
                            </div>
                            <div class="col-sm-4">
                                <?= $form->text("Giá", "pro_name_".$code, "pro_name_".$code, html_entity_decode(${'pro_name_'.$code}), "Tên sản phẩm", 1, "", "", 255, "", "", "") ?>
                            </div>
                            <div class="col-sm-3">
                                <?= $form->text("Số lượng", "pro_name_".$code, "pro_name_".$code, html_entity_decode(${'pro_name_'.$code}), "Tên sản phẩm", 1, "", "", 255, "", "", "") ?>
                            </div>
                            <div class="col-sm-1">
                                Xóa
                            </div>
                        </div>
                    </div>
                    {{--<div class="box-footer">--}}

                    {{--</div>--}}
                </div>
            </div>
*/?>

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

                                    <?=$form->wysiwyg('Mô tả sản phẩm', 'pro_functions_' . $code, html_entity_decode(${'pro_functions_'.$code})) ?>
                                    <!--                                        --><?//=$form->wysiwyg('Thông số kỹ thuật', 'pro_specifications_'.$code, html_entity_decode(${'pro_specifications_'.$code})) ?>
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
        function delete_video(id) {
            if (confirm('Bạn có chắc chắn muốn xóa video này?')) {
                $('#' + id).hide();
                $('#hdn_video_name').val('');
            }
        }
    </script>
@stop
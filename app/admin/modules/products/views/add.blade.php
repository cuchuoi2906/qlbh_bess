@extends('module-master')
<script type="text/javascript" src="../../resource/ckeditor/ckeditor.js?t=D03G5XL"></script>
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
                        <?= $form->select("Danh mục", "pro_category_id", "pro_category_id", $categories, $pro_category_id, "") ?>
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

                                    <?= $form->text("Tên sản phẩm", "pro_name_".$code, "pro_name_".$code, ${'pro_name_'.$code}, "Tên sản phẩm", 1, "", "", 255, "", "", "") ?>
                                    <?= $form->textarea("Mô tả ngắn", "pro_teaser_".$code, "pro_teaser_".$code, ${'pro_teaser_'.$code}, "Mô tả ngắn", 1, "", "", 255, "", "", "") ?>
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

                        <?= $form->select("Thương hiệu", "pro_brand_id", "pro_brand_id", [0 => 'Không chọn'] + $brands, $pro_brand_id ?? 0, "",0) ?>

                        <?= $form->number("Giá", "pro_price", "pro_price", $pro_price, "Giá", 1, "", "", 255, "", "", "") ?>
                        <?= $form->text("Giá khuyến mại", "pro_discount_price", "pro_discount_price", $pro_discount_price, "Giá khuyến mại", 1, "", "", 255, "", "", "") ?>
                        <?= $form->number("Số lượng", "pro_quantity", "pro_quantity", $pro_quantity??1, "Số lượng", 0, "", "", 255, "", "", "") ?>
                        <?= $form->select("Trạng thái", "pro_active", "pro_active", [1 => 'Hiển thị', 0 => 'Ẩn'], $pro_active, "") ?>
                        <?= $form->select("Tình trạng tồn kho", "pro_active_inventory", "pro_active_inventory", [1 => 'Còn hàng', 2 => 'Hết hàng'], $pro_active_inventory, "") ?>
                        <?= $form->select("Sản phẩm hot?", "pro_is_hot", "pro_is_hot", [1 => 'Hot', 0 => 'Không'], $pro_is_hot, "") ?>
                        <?= $form->getFile('Ảnh sản phẩm', 'images', 'images[]', 'Ảnh sản phẩm', 0, 30, 'multiple') ?>
                        <?= $form->getFile('Chọn video', 'videoFile', 'videoFile', 'Chọn video', 0, 1, '') ?>
                    </div>
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
                                        <a href="#tab_content_{{$code}}" data-toggle="tab"
                                           aria-expanded="true">{{$locale}}</a>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="tab-content">
                                @foreach($locales as $code => $locale)
                                    <div class="tab-pane {{$code=='vn'?'active':''}}" id="tab_content_{{$code}}">

                                    <?=$form->wysiwyg('Mô tả sản phẩm', 'pro_functions_' . $code, ${'pro_functions_'.$code}) ?>
                                    <!--                                        --><?//=$form->wysiwyg('Thông số kỹ thuật', 'pro_specifications_'.$code, ${'pro_specifications_'.$code}) ?>
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

@section('script')
    <script>
        $(function () {
            $('#pro_name').focus();
        });
    </script>
@stop
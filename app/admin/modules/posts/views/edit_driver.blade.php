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
            <div class="col-xs-12">
                <div class="">
                    <div class="" style="text-align: center;">
                        <?= $form->button("submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "Cập nhật" . $form->ec . "Làm lại", "Cập nhật" . $form->ec . "Làm lại", ""); ?>
                        <?= $form->hidden("action", "action", "execute", ""); ?>
                        <?= $form->hidden("valradio", "valradio", 0) ?>
                    </div>
                </div>
            </div>

            <div class="col-xs-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Thông tin cơ bản</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <?php if ($categories_arr): ?>
                        <?= $form->select("Danh mục", "pos_category_id", "pos_category_id", $categories_arr, $pos_category_id, "") ?>
                        <?php endif; ?>
                        <?php if ($products): ?>
                        <?= $form->select("Chọn sản phẩm", "pos_product_id", "pos_product_id", $products, $pos_product_id, "") ?>
                        <?php endif; ?>

                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                @foreach($locales as $code => $locale)
                                    <li class="{{$code=='vn'?'active':''}}">
                                        <a href="#tab_{{$code}}" data-toggle="tab" aria-expanded="true">{{$locale}}</a>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="tab-content">
                                @foreach($locales as $code => $locale)
                                    <div class="tab-pane {{$code=='vn'?'active':''}}" id="tab_{{$code}}">
                                        <?= $form->text("Tiêu đề", "pos_title_" . $code, "pos_title_" . $code, ${'pos_title_' . $code}, "Tiêu đề", 1, "", "", 255, "", "", "") ?>
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
                        <?= $form->getFile('File driver', 'pos_file_size', 'pos_file_size', 'File driver') ?>
                        <?= $form->checkbox("Xuất bản?", 'pos_active', 'pos_active', 1, $pos_active, '') ?>
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
    <script !src="">
        $('input[id=pos_file_size]').change(function (e) {
            var title_vn = $('#pos_title_vn');
            var title_en = $('#pos_title_en');
            // alert(title_vn.val());
            title_vn.val('' + e.target.files[0].name + '');
            title_en.val(e.target.files[0].name);
        });
    </script>
@stop
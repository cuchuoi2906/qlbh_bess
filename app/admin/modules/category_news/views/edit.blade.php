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
                    <?= $form->select("Danh mục cha", "cat_parent_id", "cat_parent_id", $parent_options, $cat_parent_id, '', 0, "", 255, "", "", "") ?>
                    <!--                        --><?//= $form->select("Loại danh mục", "cat_type", "cat_type", $types, $cat_type, '', 0, "", 255, "", "", "") ?>

                        <div style="display: none">
                            <?= $form->text("Rewrite", "cat_rewrite", "cat_rewrite", $cat_rewrite, "Rewrite đường dẫn url", 0, "", "", "", "", "", "") ?>
                        </div>
                        <?= $form->checkbox("Active", 'cat_active', 'cat_active', 1, $cat_active, '') ?>

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
                                        <?= $form->text("Tên danh mục", "cat_name_" . $code, "cat_name_" . $code, ${'cat_name_' . $code}, "Tên danh mục", 1, "", "", 255, "", "", "") ?>

                                        <?= $form->textarea("Mô tả", "cat_description_" . $code, "cat_description_" . $code, ${'cat_description_' . $code}, "Mô tả", 0, "", 100) ?>

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
                        <?= $form->getFile('Icon', 'cat_icon', 'cat_icon', 'Icon') ?>
                        <?= $form->text("Order", "cat_order", "cat_order", $cat_order, "", 1, "", "", 255, "", "", "") ?>
                        <?/*
                        <?= $form->text("SEO title", "cat_seo_title", "cat_seo_title", $cat_seo_title, "SEO title", 0, "", "", 255) ?>
                        <?= $form->text("SEO keywords", "cat_seo_keyword", "cat_seo_keyword", $cat_seo_keyword, "Mô tả", 0, "", "", 255) ?>
                        <?= $form->textarea("SEO description", "cat_seo_description", "cat_seo_description", $cat_seo_description, "Mô tả", 0, "", 100) ?>
                    */?>
                    </div>
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
            // set_max_order();
            change_write();
        });

        function locdau(str) {
            str = str.toLowerCase();
            str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
            str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
            str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
            str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
            str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
            str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
            str = str.replace(/đ/g, "d");
            str = str.replace(/!|@|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:|\;|\'| |\"|\&|\#|\[|\]|~|$|_/g, "-");
            /* tìm và thay thế các kí tự đặc biệt trong chuỗi sang kí tự - */
            str = str.replace(/-+-/g, "-"); //thay thế 2- thành 1-
            str = str.replace(/^\-+|\-+$/g, "");
            //cắt bỏ ký tự - ở đầu và cuối chuỗi
            return str;
        }

        function change_write() {
            var rewrite = locdau($('#cat_name_vn').val());
            var parent_select = $('#cat_parent_id option:selected');
            parent_id = $(parent_select).val();
            if (parent_id > 0) {
                var parent_rewrite = arr_parent_rewite[parent_id];
                rewrite = parent_rewrite + '/' + rewrite;
            }
            $('#cat_rewrite').val(rewrite);
        }

        function set_max_order() {
            var parent_select = $('#cat_parent_id option:selected');
            parent_id = $(parent_select).val();
            console.log(arr_parent_order);
            if (parent_id == 0) {
                $('#cat_order').val(parseInt(parent_max_order) + 1);
            } else {
                $('#cat_order').val(parseInt(arr_parent_order[parent_id]) + 1);
            }
        }

        $(function () {
            $('#cat_name_vn').focus();
            $('#cat_name_vn').keyup(function () {
                change_write();
            });
        });
    </script>
@stop
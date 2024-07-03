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
                        <?php // echo $form->select("Loại bài viết", "pos_type", "pos_type", $types, $pos_type, '', 0, "", 255, "", "", "") ?>

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
                                        <?= $form->textarea("Mô tả ngắn", "pos_teaser_" . $code, "pos_teaser_" . $code, ${'pos_teaser_' . $code}, "Mô tả ngắn", 0, "", 100) ?>

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

                    <?= $form->getFile('Ảnh đại diện', 'pos_image', 'pos_image', 'Ảnh đại diện') ?>

                    <?= $form->checkbox("Xuất bản?", 'pos_active', 'pos_active', 1, $pos_active, '') ?>
                    <!--                        --><?//= $form->checkbox("Tin nổi bật?", 'pos_is_hot', 'pos_is_hot', 1, $pos_is_hot, '') ?>
                        <!--                        --><?//= $form->checkbox("Hiển thị tại trang chủ?", 'pos_show_home', 'pos_show_home', 1, $pos_show_home, '') ?>

                        <!--                        --><?//= $form->text("Lượt truy cập", "pos_total_view", "pos_total_view", $pos_total_view, "Lượt truy cập", 0, "", "", 255, "", "", "") ?>
                    <?/*
                        <?= $form->text("Tiêu đề SEO", "pos_seo_title", "pos_seo_title", $pos_seo_title, "Tiêu đề SEO", 0, "", "", 255, "", "", "") ?>
                        <?= $form->text("Keywords SEO", "pos_seo_keyword", "pos_seo_keyword", $pos_seo_keyword, "Keywords SEO", 0, "", "", 255, "", "", "") ?>
                        <?= $form->textarea("Description SEO", "pos_seo_description", "pos_seo_description", $pos_seo_description, "Description SEO", 0, "", 50) ?>
                    */?>
                    </div>
                    {{--<div class="box-footer">--}}

                    {{--</div>--}}
                </div>
            </div>
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Nội dung</h3>
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

                                        <?= $form->wysiwyg("Nội dung", "pos_content_" . $code, html_entity_decode(${'pos_content_' . $code}), '../../resource/ckeditor/', "Nội dung", 0, "", 100) ?>

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
            $('#pos_title').focus();
        });
    </script>
@stop
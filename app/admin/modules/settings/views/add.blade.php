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
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Thông tin cơ bản</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <?= $form->text("Mô tả", "swe_label", "swe_label", $swe_label, "Mô tả", 1, "", "", 255, "", "", ""); ?>
                        <?= $form->text("Mã (" . (getValue('prefix', 'str')) . ')', "swe_key", "swe_key", $swe_key, "Tên key", 1, "", "", 255, "", "", ""); ?>
                        <?= $form->select("Loại", "swe_type", "swe_type", $arr_type, "", "Kiểu dữ liệu", 1, "", ""); ?>
                        <label for=""><font class="form_asterisk"></font>Giá trị :</label>

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
                                        <?=$form->text("", 'swe_value_number_' . $code, 'swe_value_number_' . $code, ${'swe_value_' . $code})?>
                                        <?=$form->textarea("", 'swe_value_plain_text_' . $code, 'swe_value_plain_text_' . $code, ${'swe_value_' . $code})?>
                                        <?= $form->wysiwyg("", "swe_value_text_" . $code, ${'swe_value_' . $code}, $editor_path, "", 1); ?>

                                    </div>
                                @endforeach
                            </div>
                            <!-- /.tab-content -->
                        </div>

                        <?= $form->getFile("", "swe_value_image", "swe_value_image", "", "", "", ""); ?>

                        <div class="gallery"></div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12">
                <div class="">
                    <div class="" style="text-align: center;">
                        <?= $form->radio("Sau khi lưu dữ liệu", "add_new" . $form->ec . "return_listing", "after_save_data", $add . $form->ec . $listing, $after_save_data, "Thêm mới" . $form->ec . "Quay về danh sách", 0, $form->ec, ""); ?>
                        <?= $form->button("submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "Cập nhật" . $form->ec . "Làm lại", "Cập nhật" . $form->ec . "Làm lại", ""); ?>
                        <?= $form->hidden("action", "action", "execute", ""); ?>
                        <?= $form->hidden("valradio", "valradio", 0); ?>
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

@section('header')
    <style type="text/css">
        #swe_value_image {
            display: none;
        }

        #cke_swe_value_text_vn, #cke_swe_value_text_en {
            display: none;
        }

        .gallery img {
            width: 300px;
            height: 200px;
        }

        .gallery {
            display: none;
        }
    </style>
@stop

@section('script')
    <script>
        $(function () {
            // Multiple images preview in browser
            var imagesPreview = function (input, placeToInsertImagePreview) {

                if (input.files) {
                    var filesAmount = input.files.length;

                    for (i = 0; i < filesAmount; i++) {
                        var reader = new FileReader();

                        reader.onload = function (event) {
                            $($.parseHTML('<img>')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
                            $(placeToInsertImagePreview).show();
                        }

                        reader.readAsDataURL(input.files[i]);
                    }
                }

            };

            $('#swe_value_image').on('change', function () {
                imagesPreview(this, 'div.gallery');
            });
        });

        $('#swe_type').change(function () {
            var type = $(this).val();
            if (type == 'text') {
//                $('#swe_value_text').show();
                $('#cke_swe_value_text_vn').show();
                $('#cke_swe_value_text_en').show();
                $('#swe_value_plain_text_vn').hide();
                $('#swe_value_plain_text_en').hide();
                $('#swe_value_number_vn').hide();
                $('#swe_value_number_en').hide();
                $('#swe_value_image').hide();
                $('.gallery').hide();
            } else if (type == 'image') {
//                $('#swe_value_text').hide();
                $('#cke_swe_value_text_vn').hide();
                $('#cke_swe_value_text_en').hide();
                $('#swe_value_plain_text_vn').hide();
                $('#swe_value_plain_text_en').hide();
                $('#swe_value_number_vn').hide();
                $('#swe_value_number_en').hide();
                $('#swe_value_image').show();
                $('.gallery').show();
            } else if (type == 'plain_text') {
//                $('#swe_value_text').hide();
                $('#cke_swe_value_text_vn').hide();
                $('#cke_swe_value_text_en').hide();
                $('#swe_value_plain_text_vn').show();
                $('#swe_value_plain_text_en').show();
                $('#swe_value_number_vn').hide();
                $('#swe_value_number_en').hide();
                $('#swe_value_image').hide();
                $('.gallery').hide();
            }else if (type == 'number') {
//                $('#swe_value_text').hide();
                $('#cke_swe_value_text_vn').hide();
                $('#cke_swe_value_text_en').hide();
                $('#swe_value_plain_text_vn').hide();
                $('#swe_value_plain_text_en').hide();
                $('#swe_value_number_vn').show();
                $('#swe_value_number_en').show();
                $('#swe_value_image').hide();
                $('.gallery').hide();
            }
        });
        $('#swe_type').change();
    </script>
@stop
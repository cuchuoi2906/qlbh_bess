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

                        <?= $form->text("Tên", "use_name", "use_name", $use_name ?? '', "Tên", 1, "", "", 255, "", "", "") ?>
                        <?= $form->text("Số điện thoại / Email", "use_phone", "use_phone", $use_phone ?? '', "Số điện thoại", 1, "", "", 255, "", "", "") ?>
                        <?=$form->select('Loại tài khoản', 'use_type', 'use_type', $typeAccountUser, $use_type,"",0)?>
                        <?= $form->select("Giới tính", "use_gender", "use_gender", $genders, $use_gender ?? 0, "") ?>
                        <?= $form->password("Mật khẩu", "use_password", "use_password", $use_password ?? '', "Mật khẩu", 1, "", "", 255, "", "", "") ?>
                        <?= $form->password("Nhập lại mật khẩu", "use_password_retype", "use_password_retype", $use_password_retype ?? '', "Nhập lại mật khẩu", 1, "", "", 255, "", "", "") ?>
                    </div>
                </div>
            </div>

            <div class="col-xs-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Thông tin thêm</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <?= $form->select("Cấp trên", "use_referral_id", "use_referral_id", $users, $use_referral_id ?? 0, "", 1) ?>
                        <?= $form->select("Trạng thái", "use_active", "use_active", $status_arr, $use_active ?? 0, "Trạng thái", 1) ?>
                        <?= $form->select("Premium", "use_premium", "use_premium", $premium_arr, $use_premium ?? 0, "Premium", 1) ?>
                        <?= $form->text("% hưởng hoa hồng", "use_premium_commission", "use_premium_commission", $use_premium_commission ?? 80, "% hưởng hoa hồng", 0, "", "", 255, "", "", "") ?>
                        <?= $form->checkbox("Nhân viên sale", 'use_sale', 'use_sale', 1, $use_sale, '') ?>
                        <?= $form->select("Sale phụ trách", "user_sale_id", "user_sale_id", $sale_user, $user_sale_id ?? 0, "", 0) ?>
                        <?=$form->select('Tỉnh/Thành Phố', 'use_province_id', 'use_province_id', $province, $use_province_id,"",1)?>
                        <?=$form->select('Quận/Huyện', 'use_district_id', 'use_district_id', [], $use_district_id,"",1)?>
                        <?=$form->select('Xã Phường', 'use_ward_id', 'use_ward_id', [], $use_ward_id,"",1)?>
                        <?= $form->text("Ðịa chỉ chi tiết", "use_address_register", "use_address_register", $use_address_register, "", '', "", "", 255, "", "", "") ?>
                        <?=$form->select('Nghề Nghiệp', 'use_job_code', 'use_job_code', $use_job, $use_job_code,"",1)?>
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
                                <li class="active">
                                    <a href="#tab_content" data-toggle="tab"
                                       aria-expanded="true"></a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_content">
                                    <?= $form->wysiwyg("Nội dung", "use_content", ${'use_content'}, '../../resource/ckeditor/', "Nội dung", 0, "", 100) ?>
                                </div>
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
            var rewrite = locdau($('#cat_name').val());
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
            $('#cat_name').focus();
            $('#cat_name').keyup(function () {
                change_write();
            });
        });
        $('#use_province_id').change(function () {
            var value = $(this).val();
            $.get('get_objects_from_type.php?type=district&value=' + value, function (response) {
                $('#use_district_id').html(response);

            });
        });
        $('#use_district_id').change(function () {
            var value = $(this).val();
            $.get('get_objects_from_type.php?type=ward&value=' + value, function (response) {
                $('#use_ward_id').html(response);

            });
        });
    </script>
@stop
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

                        <?= $form->text("Tên", "evt_name", "evt_name", $evt_name ?? '', "Tên sự kiện", 1, "", "", "", "", "", "") ?>

                        <?= $form->checkbox("Active", 'cat_active', 'cat_active', 1, $evt_active ?? 1, '') ?>

                        <?= $form->textarea("Ghi chú", "evt_note", "evt_note", $evt_note ?? '', "Ghi chú", 0, "", 100) ?>

                    </div>
                </div>
            </div>

            <div class="col-xs-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Thông tin ưu đãi</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">

                        <div class="row">
                            <div class="col-xs-6">
                                <?= $form->text("Ngày bắt đầu", "evt_start_time", "evt_start_time", $evt_start_time ? date('d-m-Y', $evt_start_time) : date('d-m-Y'), "Ngày bắt đầu", 1, "", "", "", "", "", "") ?>
                            </div>
                            <div class="col-xs-6">
                                <?= $form->text("Ngày kết thúc", "evt_end_time", "evt_end_time", $evt_end_time ? date('d-m-Y', $evt_end_time) : date('d-m-Y', time() + 86400), "Ngày kết thúc", 1, "", "", "", "", "", "") ?>
                            </div>
                        </div>

                        <?= $form->number("Tỷ lệ hoa hồng trực tiếp", "evt_direct_commission_ratio", "evt_direct_commission_ratio", $evt_direct_commission_ratio ?? 1, "Tỷ lệ hoa hồng trực tiếp", 1, "", "", "", "", "", "") ?>
                        <?= $form->number("Tỷ lệ hoa hồng cho cấp trên", "evt_parent_commission_ratio", "evt_parent_commission_ratio", $evt_parent_commission_ratio ?? 1, "Tỷ lệ hoa hồng cho cấp trên", 1, "", "", "", "", "", "") ?>

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

        $('#evt_start_time').datepicker({
            format: 'dd-mm-yyyy'
        });
        $('#evt_end_time').datepicker({
            format: 'dd-mm-yyyy'
        });
    </script>
@stop
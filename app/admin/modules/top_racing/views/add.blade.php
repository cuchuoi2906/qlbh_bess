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

                        <?= $form->text("Tên", "trc_title", "trc_title", $trc_title ?? '', "Tên chiến dịch", 1, "", "", "", "", "", "") ?>

                        <?= $form->wysiwyg("Mô tả", "trc_description", html_entity_decode($trc_description ?? ''), $editor_path); ?>

                    </div>
                </div>
            </div>

            <div class="col-xs-6">
                <div class="col-xs-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Thông tin chi tiết</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">

                            <div class="row">
                                <div class="col-xs-12">
                                    <?= $form->checkbox("Active", 'trc_active', 'trc_active', 1, $trc_active ?? 1, '') ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <?= $form->select("Kiểu đua top", "trc_type", "trc_type", \App\Models\TopRacingCampaign::TYPES, $trc_type ?? \App\Models\TopRacingCampaign::TYPE_OWNER, "", 1, '', '', 0) ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-6">
                                    <?= $form->text("Ngày bắt đầu", "trc_start", "trc_start", date('Y-m-d', $trc_start ?? time()), "Ngày bắt đầu", 1, "", "", "", "", "", "") ?>
                                </div>
                                <div class="col-xs-6">
                                    <?= $form->text("Ngày kết thúc", "trc_end", "trc_end", $trc_end ? date('Y-m-d', $trc_end) : '', "Để trống để chạy chiến dịch không kết thúc", 1, "", "", "", "", "", "") ?>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-xs-12">

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Sản phẩm</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <?= $form->select("Sản phẩm", "pro_ids", "pro_ids", $products ?? [], $pro_ids ?? [], "", 1, '', '', 1, 'placeholder="Có thể chọn nhiều"') ?>

                        </div>
                        {{--<div class="box-footer">--}}

                        {{--</div>--}}
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

        $('#trc_start').datepicker({
            dateFormat: 'yyyy-mm-dd',
            format: 'yyyy-mm-dd'
        });
        $('#trc_end').datepicker({
            dateFormat: 'yyyy-mm-dd',
            format: 'yyyy-mm-dd'
        });
    </script>
@stop
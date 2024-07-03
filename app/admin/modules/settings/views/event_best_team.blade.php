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
                        <h3 class="box-title">Set up thời gian chạy event đua team mạnh nhất</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">

                        <?= $form->checkbox("Chạy event", "swe_event_best_team_active", 'swe_event_best_team_active', 1, $swe_event_best_team_active ?? 0); ?>
                        <?= $form->text("Ngày bắt đầu", "swe_event_best_team_start", 'swe_event_best_team_start', $swe_event_best_team_start ?? '', "Ngày bắt đầu", 1, "", "", 255, "", "", ""); ?>
                        <?= $form->text("Ngày kết thúc", "swe_event_best_team_end", 'swe_event_best_team_end', $swe_event_best_team_end ?? '', "Ngày kết thúc", 1, "", "", 255, "", "", ""); ?>

                    </div>
                </div>
            </div>


            <div class="col-xs-12">
                <div class="">
                    <div class="" style="text-align: center;">
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
@stop

@section('script')
    <script>
        $('#swe_event_best_team_start').datepicker({
            format: 'dd-mm-yyyy'
        });
        $('#swe_event_best_team_end').datepicker({
            format: 'dd-mm-yyyy'
        });
    </script>
@stop
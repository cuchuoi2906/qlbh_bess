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
            @for( $i = 1; $i <= $total + 1; $i++ )
                <div class="col-xs-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Tài khoản {{$i}}</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">

                            <?= $form->text("Tên ngân hàng", "bank_name_" . $i, "bank_names[" . $i . "]", ${'bank_name_' . $i} ?? '', "Tên ngân hàng", 1, "", "", 255, "", "", ""); ?>
                            <?= $form->text("Số tài khoản", "account_number_" . $i, "account_numbers[" . $i . "]", ${'account_number_' . $i} ?? '', "Số tài khoản", 1, "", "", 255, "", "", ""); ?>
                            <?= $form->text("Chủ tài khoản", "name_" . $i, "names[" . $i . "]", ${'name_' . $i} ?? '', "Chủ tài khoản", 1, "", "", 255, "", "", ""); ?>
                            <?= $form->text("Chi nhánh", "branch_" . $i, "branches[" . $i . "]", ${'branch_' . $i} ?? '', "Chi nhánh", 1, "", "", 255, "", "", ""); ?>

                        </div>
                    </div>
                </div>
            @endfor


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

@stop
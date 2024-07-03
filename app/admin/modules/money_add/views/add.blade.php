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
                        <h3 class="box-title">Nạp tiền cho user</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <?//= $form->select("Loại danh mục", "cat_type", "cat_type", $type_arr, $cat_type, "") ?>

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
                                    <?= $form->text("User ID", "uma_user_id", "uma_user_id", ${'uma_user_id'}, "User ID", 1, "", "", 255, "", "", "") ?>
                                    <?= $form->text("Số tiền nạp", "uma_amount", "uma_amount", ${'uma_amount'}, "Số tiền nạp cho user", 1, "", "", 255, "", "", "") ?>
                                    <?= $form->textarea("Ghi chú giao dịch nạp tiền", "uma_note", "uma_note", ${'uma_note'}, "Ghi chú rõ giao dịch nạp tiền", 1, "", "", 255, "", "", "") ?>

                                </div>
                                @endforeach
                            </div>
                            <!-- /.tab-content -->
                        </div>

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
            $('#pro_name').focus();
        });
    </script>
@stop
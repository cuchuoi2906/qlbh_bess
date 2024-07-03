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
                        <h3 class="box-title">Nội dung</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">

                    <?= $form->text("Tiêu đề", "not_title", "not_title", $not_title ?? '', "Tiêu đề", 1, "", "", 255, "", "", "") ?>
                    <?= $form->select("Loại thông báo", "not_type", "not_type", $arr_type, "", "Loại thông báo", 1, "", ""); ?>
                    <?=$form->textarea('Nội dung', 'not_content', 'not_content', $not_content ?? '', 'Xin chào [name] ...', 1)?>
                    <!--                        --><?//= $form->text("Rewrite", "cat_rewrite", "cat_rewrite", $cat_rewrite, "Rewrite đường dẫn url", 0, "", "", "", "", "", "") ?>
                        <!--                        --><?//= $form->select("Trạng thái", "cat_show", "cat_show", $status_arr, $cat_show, "Trạng thái", 1) ?>
                        <!--                        --><?//= $form->checkbox("Active", 'cat_active', 'cat_active', 1, $cat_active, '') ?>
                    </div>
                </div>
            </div>

            <div class="col-xs-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Người nhận</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <?= $form->checkbox("Gửi tất cả?", 'not_is_send_all', 'not_is_send_all', 1, $not_is_send_all??1, '') ?>

                        <div class="form-group" id="user_noti_receive">
                            <select name="user_ids[]" class="form-control select2" multiple>
                                @foreach($users as $user)
                                    <option value="{{$user->id}}">{{$user->name??$user->phone}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="">
                    <div class="" style="text-align: center;">
                        <?= $form->button("submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "Lưu lại" . $form->ec . "Làm lại", "Lưu lại" . $form->ec . "Làm lại", ""); ?>
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
    $('#not_is_send_all').on('change', function() {
        if(this.checked){
            document.getElementById('user_noti_receive').style.display ='none';
        }else{
            document.getElementById('user_noti_receive').style.display ='block';
        }
    });
</script>
@stop
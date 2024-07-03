@extends('module-master')

@section('content')
<div class="container-fluid">
    <?php
    $form = new form();
    $form->create_form("edit", $fs_action, "post", "multipart/form-data", 'onsubmit="validateForm(); return false;"');
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
            <div class="box-header with-border">
                <h3 class="box-title"><b>Chỉnh sửa khảo sát</b></h3>
            </div>
            <div class="col-md-4">
                <input type="hidden" id="survey_userId" name="survey_userId" value="<?php echo intval($row['survey_userId']); ?>" >
                <?php 
                $survey_job = intval($row['survey_job']);
                $survey_regis_reason = intval($row['survey_regis_reason']);
                $survey_busined = intval($row['survey_busined']);
                $survey_busines_date = intval($row['survey_busines_date']);
                $survey_busines_desired = intval($row['survey_busines_desired']);
                $v_class_none = 'style="display:none;"';
                if($survey_regis_reason == 2){
                    $v_class_none = '';
                }
                ?>
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td><?= $form->select("Nghề nghiệp (*)", "survey_job", "survey_job", $survey_job_arr, $survey_job ?? 0, "") ?></td>
                        </tr>
                        <tr>
                            <td><?= $form->select("Lý do đăng ký (*)", "survey_regis_reason", "survey_regis_reason", $survey_regis_reason_arr, $survey_regis_reason ?? 0, "",0,'','',0,'onchange="survey_show_option_business(this.value)"') ?></td>
                        </tr>
                        <tr class="survayBusiness" <?php echo $v_class_none; ?>>
                            <td><?= $form->select("Đã từng kinh doanh (*)", "survey_busined", "survey_busined", $survey_busined_arr, $survey_busined ?? 0, "",0,588) ?></td>
                        </tr>
                        <tr class="survayBusiness" <?php echo $v_class_none; ?>>
                            <td><?= $form->select("Thời gian kinh doanh (*)", "survey_busines_date", "survey_busines_date", $survey_busines_date_arr, $survey_busines_date ?? 0, "",0,588) ?></td>
                        </tr>
                        <tr class="survayBusiness" <?php echo $v_class_none; ?>>
                            <td><?= $form->select("Hình thức kinh doanh mong muốn (*)", "survey_busines_desired", "survey_busines_desired", $survey_busines_desired_arr, $survey_busines_desired ?? 0, "",0,588) ?></td>
                        </tr>
                        <tr>
                            <td><?= $form->textarea('Ghi chú', 'survey_note', 'survey_note',$row['survey_note'], 'Nhập ghi chú', 0, '', 100,'maxlength="500"') ?></td>
                        </tr>
                    </tbody>
                </table>
                <div class="">
                    <div class="" style="text-align: center;">
                        <?= $form->button("submit", "submit", "submit", "Cập nhật","", ""); ?>
                        <?= $form->hidden("action", "action", "execute", ""); ?>
                    </div>
                </div>
            </div>
        </div>
    <?php
    $form->close_form();
    unset($form);
    ?>
</div>
@stop
@section('script')
    <script>
        function survey_show_option_business(value){
            if(value == 0){
                return;
            }
            survayBusinessClass = document.getElementsByClassName('survayBusiness');
            for(i=0;i<survayBusinessClass.length;i++){
                if(value == 2){
                    survayBusinessClass[i].style.display ='';
                }else{
                    survayBusinessClass[i].style.display ='none';
                }
            }
        }
    </script>
@stop
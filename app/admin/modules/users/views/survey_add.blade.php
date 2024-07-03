@extends('module-master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="box-header with-border">
                <h3 class="box-title"><b>Thông tin cá nhân</b></h3>
            </div>
            <div class="col-md-3">
                <table class="table table-hover table-bordered">
                    <tbody>
                        <tr>
                            <td>Họ Tên:</td>
                            <td><?php echo $row['use_name'].' (ID: '.$row['use_id'].')'; ?></td>
                        </tr>
                        <tr>
                            <td>Số điện thoại:</td>
                            <td><?php echo $row['use_phone']; ?></td>
                        </tr>
                        <tr>
                            <td>Email:</td>
                            <td><?php echo $row['use_email']; ?></td>
                        </tr>
                        <tr>
                            <td>Người giới thiệu:</td>
                            <td><?php echo $row['username_referral'].' (ID: '.$row['use_referral_id'].')'; ?></td>
                        </tr>
                        <tr>
                            <td>Ngày đăng ký:</td>
                            <td><?php echo $row['use_created_at']; ?></td>
                        </tr>
                        <tr>
                            <td>Ngày đặt hàng gần nhất:</td>
                            <td><?php echo $row['ord_created_at']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
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
                <div class="box-header with-border">
                    <h3 class="box-title"><b>Thực hiện khảo sát</b></h3>
                </div>
                <div class="col-md-4">
                    <input type="hidden" id="survey_userId" name="survey_userId" value="<?php echo $record_id; ?>" >
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td><?= $form->select("Nghề nghiệp (*)", "survey_job", "survey_job", $survey_job_arr, $survey_job ?? 0, "") ?></td>
                            </tr>
                            <tr>
                                <td><?= $form->select("Lý do đăng ký (*)", "survey_regis_reason", "survey_regis_reason", $survey_regis_reason_arr, $survey_regis_reason ?? 0, "",0,'','',0,'onchange="survey_show_option_business(this.value)"') ?></td>
                            </tr>
                            <tr class="survayBusiness" style="display:none;">
                                <td><?= $form->select("Đã từng kinh doanh (*)", "survey_busined", "survey_busined", $survey_busined_arr, $survey_job ?? 0, "",0,588) ?></td>
                            </tr>
                            <tr class="survayBusiness" style="display:none;">
                                <td><?= $form->select("Thời gian kinh doanh (*)", "survey_busines_date", "survey_busines_date", $survey_busines_date_arr, $survey_job ?? 0, "",0,588) ?></td>
                            </tr>
                            <tr class="survayBusiness" style="display:none;">
                                <td><?= $form->select("Hình thức kinh doanh mong muốn (*)", "survey_busines_desired", "survey_busines_desired", $survey_busines_desired_arr, $survey_job ?? 0, "",0,588) ?></td>
                            </tr>
                            <tr>
                                <td><?= $form->textarea('Ghi chú', 'survey_note', 'survey_note','', 'Nhập ghi chú', 0, '', 100,'maxlength="500"') ?></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="">
                        <div class="" style="text-align: center;">
                            <?= $form->button("submit", "submit", "submit", "Thêm khảo sát","", ""); ?>
                            <?= $form->hidden("action", "action", "execute", ""); ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        $form->close_form();
        unset($form);
        ?>
        <div class="col-md-12">
            <h3>Danh sách khảo sát</h3>
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td>STT</td>
                        <td>Nghề nghiệp</td>
                        <td>Lý do đăng ký</td>
                        <td>Đã từng kinh doanh</td>
                        <td>Thời gian kinh doanh</td>
                        <td>Hình thức kinh doanh mong muốn</td>
                        <td>Ghi chú</td>
                        <td>Thời gian khảo sát</td>
                        <td>User khảo sát</td>
                        <td>Thao tác</td>
                    </tr>
                    <?php 
                    if(check_array($row_survey_users)){
                        $v_stt = 1;
                        foreach($row_survey_users as $items){
                            $survey_id = intval($items['survey_id']);
                            
                            $survey_job = intval($items['survey_job']);
                            $survey_job_name = $survey_job_arr[$survey_job];
                            
                            $survey_regis_reason = intval($items['survey_regis_reason']);
                            $survey_regis_reason_name = $survey_regis_reason_arr[$survey_regis_reason];
                            
                            $survey_busined = intval($items['survey_busined']);
                            $survey_survey_busined_name = '';
                            if($survey_busined > 0){
                                $survey_survey_busined_name = $survey_busined_arr[$survey_busined];
                            }
                            
                            $survey_busines_date = intval($items['survey_busines_date']);
                            $survey_busines_date_name = '';
                            if($survey_busines_date > 0){
                                $survey_busines_date_name = $survey_busines_date_arr[$survey_busines_date];
                            }
                            
                            $survey_busines_desired = intval($items['survey_busines_desired']);
                            $survey_busines_desired_name = '';
                            if($survey_busines_desired > 0){
                                $survey_busines_desired_name = $survey_busines_desired_arr[$survey_busines_desired];
                            }
                            
                            $survey_note = $items['survey_note'];
                            $survey_created_at = $items['survey_created_at'];
                            $survey_users_admin_name = $items['survey_users_admin_name'];
                            ?>
                            <tr>
                                <td><?php echo $v_stt; ?></td>
                                <td><?php echo $survey_job_name; ?></td>
                                <td><?php echo $survey_regis_reason_name; ?></td>
                                <td><?php echo $survey_survey_busined_name; ?></td>
                                <td><?php echo $survey_busines_date_name; ?></td>
                                <td><?php echo $survey_busines_desired_name; ?></td>
                                <td><?php echo $survey_note; ?></td>
                                <td><?php echo $survey_created_at; ?></td>
                                <td><?php echo $survey_users_admin_name; ?></td>
                                <td>
                                    <a href="survey_users_edit.php?record_id=<?php echo $survey_id; ?>">
                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                    </a>
                                    ||
                                    <a onclick="return confirm('Bạn có chắc muốn xóa bản ghi này?');" href="survey_users_delete.php?record_id=<?php echo $survey_id; ?>">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php 
                            $v_stt++;
                        }
                    } ?>
                </tbody>
            </table>
        </div>
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
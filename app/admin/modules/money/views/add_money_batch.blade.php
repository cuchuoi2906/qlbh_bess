@extends('module-master')

@section('content')
    <div class="container-fluid">

        <?php
        if (AppView\Helpers\Facades\FlashMessage::hasMessages()) {
            AppView\Helpers\Facades\FlashMessage::display();
        }
        $form = new form();
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

        <div class="row show_error" style="display: none">
            <div class="col-xs-12">
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-ban"></i> Có lỗi!</h4>

                </div>
            </div>
        </div>

        <form action="{{$fs_action}}" method="POST" onsubmit="return validateForm()">
            <div class="row">
                <div class="col-xs-6">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Bảo mật</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <input type="hidden" value="<?php echo $money_batch_id; ?>" name="add_money_batch_id" id="add_money_batch_id" />
                            <div class="form-group">
                                <label for="password">Mật khẩu</label>
                                <input type="password" name="admin_pass" id="admin_pass" value=""
                                       class="form-control">
                            </div>
                            <div class="input-group">
                                <input name="otp" id="otp" type="text" class="form-control"
                                       placeholder="Mã xác nhận (Gửi qua email)">
                                <span class="input-group-btn">
                                    <button type="button" onclick="send_otp(1)"
                                            class="btn btn-info btn-flat">Gửi OTP qua email</button>
                                    <button type="button" onclick="send_otp(2)"
                                            class="btn btn-warning btn-flat">Gửi OTP qua số đt</button>
                                </span>
                            </div>
                            <br />
                            <br />
                            <div class="text-center">
                                <button class="btn btn-primary">Nạp tiền</button>
                                <input type="hidden" class="action" name="action" value="execute">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
@stop

@section('script')
    {{--<script src="/admin/resource/adminlte/plugins/input-mask/jquery.inputmask.js"></script>--}}
    {{--<script src="/admin/resource/adminlte/plugins/input-mask/jquery.inputmask.extensions.js"></script>--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/autonumeric/4.1.0/autoNumeric.min.js"
            integrity="sha256-1i9ngu0Ngx8mGl5baEWYIf0G1ls16HPMafbVlk6vYo0=" crossorigin="anonymous"></script>
    <script>
        function formatNumber(num) {
            return num.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
        }
        $('#add_money').keyup(function () {
            $('#add_money_format').html(formatNumber($(this).val()) + 'đ');
        });
        $('#add_money').change(function () {
            $('#add_money_format').html(formatNumber($(this).val()) + 'đ');
        });

        function send_otp(type) {

            if ($('#add_money').val() == '' || parseInt($('#add_money').val()) == 0) {
                $.toast({
                    // heading: '',
                    text: 'Vui lòng nhập số tiền cần nạp',
                    position: 'bottom-right',
                    stack: false,
                    icon: 'error'
                });
                return;
            }

            if ($('#admin_pass').val() == '') {
                $.toast({
                    // heading: '',
                    text: 'Vui lòng nhập mật khẩu',
                    position: 'bottom-right',
                    stack: false,
                    icon: 'error'
                });
                return;
            }

            $.ajax({
                type: 'POST',
                url: 'send_otp.php',
                data: {
                    type: type,
                    user_id: <?=getValue('record_id', 'int', 'GET', 0)?>,
                    batch_money: 1
                },
                dataType: 'json',
                success: function (response) {
                    if (response.error == 1) {
                        $.toast({
                            heading: 'Có lỗi xảy ra. Hãy thử lại',
                            text: response.message,
                            position: 'bottom-right',
                            stack: false,
                            icon: 'error'
                        })
                    } else {
                        $.toast({
                            heading: 'Thành công',
                            text: 'Bạn vừa gửi OTP thành công. Hãy check email / số điện thoại',
                            position: 'bottom-right',
                            stack: false,
                            icon: 'success'
                        })
                    }

                }
            });
        }

        function validateForm() {

            if ($('#add_money').val() == '' || parseInt($('#add_money').val()) == 0) {
                $.toast({
                    // heading: '',
                    text: 'Vui lòng nhập số tiền cần điều chỉnh',
                    position: 'bottom-right',
                    stack: false,
                    icon: 'error'
                });
                return false;
            }

            if ($('#admin_pass').val() == '') {
                $.toast({
                    // heading: '',
                    text: 'Vui lòng nhập mật khẩu tài khoản của bạn',
                    position: 'bottom-right',
                    stack: false,
                    icon: 'error'
                });
                return false;
            }

            if ($('#otp').val() == '') {
                $.toast({
                    // heading: '',
                    text: 'Vui lòng nhập mã bảo mật được gửi về email/SĐT của bạn.',
                    position: 'bottom-right',
                    stack: false,
                    icon: 'error'
                });
                return false;
            }

            return true;
        }
    </script>

@stop
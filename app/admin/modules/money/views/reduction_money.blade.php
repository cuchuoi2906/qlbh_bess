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


        <div class="row">
            <form action="{{$fs_action}}" method="POST" onsubmit="return validateForm()">
                <div class="col-xs-6">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Nạp tiền</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <div class="form-group">
                                <label for="use_name">Mã giới thiệu (ID):</label>
                                <input type="text" id="use_name" value="{{$user->id}}" class="form-control" disabled>
                            </div>
                            <div class="form-group">
                                <label for="use_name">Tên người dùng</label>
                                <input type="text" id="use_name" value="{{$user->name}}" class="form-control" disabled>
                            </div>
                            <div class="form-group">
                                <label for="use_name">Tài khoản tiền nạp</label>
                                <input type="text" id="use_money"
                                       value="{{($user->wallet) ? number_format($user->wallet->charge, 0, ',', '.') : 0}} đ"
                                       class="form-control" disabled style="text-align: right;">
                            </div>
                            <div class="form-group">
                                <label for="use_name">Tài khoản hoa hồng</label>
                                <input type="text" id="use_money"
                                       value="{{($user->wallet) ? number_format($user->wallet->commission, 0, ',', '.') : 0}} đ"
                                       class="form-control" disabled style="text-align: right;">
                            </div>
                            <div class="form-group">
                                <label for="use_name">Số tiền giảm (<span style="color: red" id="add_money_format"></span>)</label>
                                <input id="reduction_money" name="reduction_money" class="form-control"
                                       type="number"
                                       value="<?=$money_reduction ?? ''?>"
                                       style="text-align: right;" placeholder="0">
                            </div>

                            <div class="form-group">
                                <label for="use_name">Ghi chú</label>
                                <textarea name="admin_note" class="form-control" placeholder="Ghi chú"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="use_name">Thông báo</label>
                                <textarea name="note" class="form-control"
                                          placeholder="Tài khoản của bạn vừa giảm [money]"></textarea>
                            </div>

                            <div class="text-center">
                                <button class="btn btn-primary">Trừ tiền</button>
                                <input type="hidden" class="action" name="action" value="execute">
                                <a href="{{$fs_redirect}}" class="btn btn-danger">Quay lại</a>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Bảo mật</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">

                            <div class="form-group">
                                <label for="password">Mật khẩu</label>
                                <input type="password" name="admin_pass" id="admin_pass" value=""
                                       class="form-control">
                            </div>
                            <div class="input-group">
                                <input name="otp" id="otp" type="text" class="form-control" value=""
                                       placeholder="Mã xác nhận (Gửi qua email)">
                                <span class="input-group-btn">
                                    <button type="button" onclick="send_otp(1)"
                                            class="btn btn-info btn-flat">Gửi OTP qua email</button>
                                    <button type="button" onclick="send_otp(2)"
                                            class="btn btn-warning btn-flat">Gửi OTP qua số đt</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
@stop

@section('script')

    <script>
        function formatNumber(num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
        }

        $('#reduction_money').keyup(function () {
            $('#add_money_format').html(formatNumber($(this).val()) + 'đ');
        });
        $('#reduction_money').change(function () {
            $('#add_money_format').html(formatNumber($(this).val()) + 'đ');
        });

        function send_otp(type) {
            if ($('#reduction_money').val() == '' || parseInt($('#reduction_money').val()) == 0) {
                $.toast({
                    // heading: '',
                    text: 'Vui lòng nhập số tiền cần nạp',
                    position: 'bottom-right',
                    stack: false,
                    icon: 'error'
                })
                return;
            }

            if ($('#admin_pass').val() == '') {
                $.toast({
                    // heading: '',
                    text: 'Vui lòng nhập mật khẩu',
                    position: 'bottom-right',
                    stack: false,
                    icon: 'error'
                })
                return;
            }

            $.ajax({
                type: 'POST',
                url: 'send_otp.php',
                data: {
                    type: type,
                    user_id: <?=getValue('record_id', 'int', 'GET', 0)?>
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

            if ($('#reduction_money').val() == '' || parseInt($('#reduction_money').val()) == 0) {
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
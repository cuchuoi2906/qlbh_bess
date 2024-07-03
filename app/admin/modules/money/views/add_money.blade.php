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
                            <h3 class="box-title">Nạp tiền</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">

                            <div class="form-group">
                                <label for="use_name">Mã giới thiệu (ID):</label>
                                <input type="text" id="use_id" value="{{$user->id}}" class="form-control" disabled>
                            </div>
                            <div class="form-group">
                                <label for="use_name">Tên người dùng</label>
                                <input type="text" id="use_name" value="{{$user->name}}" class="form-control" disabled>
                            </div>
                            <div class="form-group">
                                <label for="use_name">Tài khoản tiền nạp</label>
                                <input type="text" id="use_charge"
                                       value="{{($user->wallet) ? number_format($user->wallet->charge, 0, ',', '.') : 0}} đ"
                                       class="form-control" disabled style="text-align: right;">
                            </div>
                            <div class="form-group">
                                <label for="use_name">Tài khoản hoa hồng</label>
                                <input type="text" id="use_money"
                                       value="{{($user->wallet) ? number_format($user->wallet->commission, 0, ',', '.') : 0}} đ"
                                       class="form-control" disabled style="text-align: right;">
                            </div>
                            {{--<div class="form-group">--}}
                            {{--<label for="wallet_type">Chọn ví</label>--}}
                            {{--<select class="form-control select2" title="Loại ví" id="wallet_type" name="wallet_type"--}}
                            {{--style="width:px" size="">--}}
                            {{--@foreach($wallet_type as $key => $item)--}}
                            {{--<option title="{{$item}}" value="{{$key}}">{{$item}}</option>--}}
                            {{--@endforeach--}}
                            {{--</select>--}}
                            {{--</div>--}}
                            <div class="form-group">
                                <label for="use_name">Số tiền nạp (<span id="add_money_format" style="color: red;"></span>)</label>
                                <input id="add_money" name="add_money"
                                       <?//=(getValue('request_id') ? 'disabled' : '')?>
                                       type="text"
                                       class="form-control"
                                       value="<?=$money_add ? $money_add : getValue('money', 'int', 'GET', '')?>"
                                       style="text-align: right;" placeholder="0">
                            </div>
                            <div class="form-group">
                                <label for="use_name">Thông báo</label>
                                <textarea name="note" class="form-control"
                                          placeholder="Tài khoản của bạn vừa tăng thêm [money]"></textarea>
                                <ul>
                                    <li>Tài khoản của bạn vừa tăng [money] do nạp tiền qua chuyển khoản ngân hàng</li>
                                    <li>Chúc mừng bạn được tặng [money] do nạp tiền lần đầu tiên</li>
                                </ul>
                            </div>
                            <div class="text-center">
                                <button class="btn btn-primary">Nạp tiền</button>
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
                                <input name="otp" id="otp" type="text" class="form-control"
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
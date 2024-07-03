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
                        <?= $form->select("Người giới thiệu", "user_referral_id", "user_referral_id", $referral_user, $user_referral_id ?? 0, "", 0) ?>
                        <?= $form->select("Người dùng", "ord_user_id", "ord_user_id", $users, $ord_user_id ?? 0, "", 1) ?>
                        <?/*
                        <?= $form->text("Tên", "ord_ship_name", "ord_ship_name", $ord_ship_name ?? '', "Tên", 1, "", "", 255, "", "", "") ?>
                        <?= $form->text("Địa chỉ", "ord_ship_address", "ord_ship_address", $ord_ship_address ?? getValue('ord_ship_address', 'str', 'POST', ''), "Địa chỉ", 1, "", "", 255, "", "", "") ?>
                        */?>
                        <?= $form->select("Địa chỉ", "ord_address_id", "ord_address_id", $addresses, getValue('ord_address_id', 'str', 'POST', 'COD'), "", 1) ?>
                        <?//= $form->text("Số điện thoại", "ord_ship_phone", "ord_ship_phone", $ord_ship_phone ?? getValue('ord_ship_phone', 'str', 'POST', ''), "Tên", 1, "", "", 255, "", "", "") ?>
                        <?= $form->text("Email", "ord_ship_email", "ord_ship_email", $ord_ship_email ?? getValue('ord_ship_email', 'str', 'POST', ''), "Tên", 1, "", "", 255, "", "", "") ?>
                        <?= $form->textarea("Ghi chú", "ord_ship_note", "ord_ship_note", $ord_ship_note ?? getValue('ord_ship_note', 'str', 'POST', ''), "Ghi chú", 1, "", "", 255, "", "", "") ?>

                        <?= $form->select("Hình thức thanh toán", "ord_payment_type", "ord_payment_type", ['COD' => 'Thanh toán khi nhận hàng (COD)', 'WALLET' => 'Ví tiêu dùng', 'BANKING' => 'Thanh toán chuyển khoản'], getValue('ord_payment_type', 'str', 'POST', 'COD'), "", 1) ?>
                        <?= $form->select("Hình thức nhận chiết khấu", "ord_commission_type", "ord_commission_type", [1 => 'Trừ vào tổng tiền', 2 => "Cộng vào ví hoa hồng"], $ord_commission_type ?? '1', "", 1) ?>
                    </div>
                </div>
            </div>

            <div class="col-xs-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Sản phẩm</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <?= $form->select("Sản phẩm", "pro_id", "pro_id", $products ?? [], [] ?? 0, "", 1) ?>

                        <table class="table">
                            <thead>
                            <tr>
                                {{--<th>STT</th>--}}
                                <th>Sản phẩm</th>
                                <th>Số luợng</th>
                                <th>Xóa</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($cart_products ?? [] as $product)
                                <tr id="product_{{$product->product->id}}">
                                    {{--<td></td>--}}
                                    <td>{{$product->product->name}}</td>
                                    <td><input onchange="change_quantity({{$product->product->id}}, this.value)"
                                               type="number" name="quantity_{{$product->product->id}}"
                                               id="quantity_{{$product->product->id}}" value="{{$product->quantity}}"/>
                                    </td>
                                    <td>
                                        <button onclick="delete_product({{$product->product->id}})">Xóa</button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="2">Tổng số sản phẩm:</td>
                                <td>{{$meta['total_product'] ?? 0}}</td>
                            </tr>
                            <tr>
                                <td colspan="2">Tổng số tiền thanh toán:</td>
                                <td>{{format_money($meta['total_money'] ?? 0)}}</td>
                            </tr>
                            <tr>
                                <td colspan="2">Tổng số tiền:</td>
                                <td>{{format_money($meta['total_money_origin'] ?? 0)}}</td>
                            </tr>
                            <tr>
                                <td colspan="2">Chiết khấu:</td>
                                <td>{{format_money($meta['total_discount'] ?? 0)}}</td>
                            </tr>
                            </tfoot>
                        </table>

                    </div>
                    {{--<div class="box-footer">--}}

                    {{--</div>--}}
                </div>
            </div>
            <div class="col-xs-12">
                <div class="">
                    <div class="" style="text-align: center;">
                        <?= $form->button("submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "Tạo đơn" . $form->ec . "Làm lại", "Cập nhật" . $form->ec . "Làm lại", ""); ?>
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
            $('#ord_user_id').change(function () {
                location.href = 'add.php?ord_user_id=' + $(this).val();
            });
            $('#user_referral_id').change(function () {
                location.href = 'add.php?user_referral_id=' + $(this).val();
            });

            $('#pro_id').change(function () {
                //Gọi API add vào giỏ hàng
                $.ajax({
                    url: 'add_cart.php',
                    type: 'POST',
                    data: {
                        id: $(this).val(),
                        user_id: $('#ord_user_id').val(),
                        add_more: 1
                    },
                    success: function (response) {
                        location.reload();
                    }
                });
            });
        });

        function delete_product(id) {
            if (confirm('Bạn có muốn xóa sản phẩm này khỏi giỏ hàng?')) {
                $.ajax({
                    url: 'delete_product_cart.php',
                    type: 'GET',
                    data: {
                        id: id,
                        user_id: $('#ord_user_id').val()
                    },
                    success: function (response) {
                        location.reload();
                    }
                });
            }
        }

        function change_quantity(id, quantity) {
            $.ajax({
                url: 'change_quantity_product_cart.php',
                type: 'GET',
                data: {
                    id: id,
                    user_id: $('#ord_user_id').val(),
                    quantity: quantity
                },
                success: function (response) {
                    location.reload();
                }
            });
        }

    </script>
@stop
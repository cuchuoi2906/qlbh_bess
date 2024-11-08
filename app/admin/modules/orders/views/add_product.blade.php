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
                        <h3 class="box-title">Sản phẩm</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <?= $form->select("Sản phẩm", "pro_id", "pro_id", $products ?? [], [] ?? 0, "", 1) ?>

                        <table class="table">
                            <!--<thead>
                            <tr>
                                {{--<th>STT</th>--}}
                                <th>Sản phẩm</th>
                                <th>Số luợng</th>
                                <th>Xóa</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($cart_products ?? [] as $product)
                                <tr id="product_{{$product->id}}">
                                    {{--<td></td>--}}
                                    <td>{{$product->name}}</td>
                                    <td><input onchange="change_quantity({{$product->product->id}}, this.value)"
                                               type="number" name="quantity_{{$product->product->id}}"
                                               id="quantity_{{$product->product->id}}" value="{{$product->quantity}}"/>
                                    </td>
                                    <td>
                                        <button onclick="delete_product({{$product->product->id}})">Xóa</button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>-->
                            <tfoot>
                            <tr>
                                <td colspan="2">Tổng số sản phẩm:</td>
                                <td>{{$total_product ?? 0}}</td>
                            </tr>
                            </tfoot>
                        </table>

                    </div>
                    {{--<div class="box-footer">--}}

                    {{--</div>--}}
                </div>
            </div>
            <!--<div class="col-xs-12">
                <div class="">
                    <div class="" style="text-align: center;">
                        <?= $form->button("submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "Tạo đơn" . $form->ec . "Làm lại", "Cập nhật" . $form->ec . "Làm lại", ""); ?>
                        <?= $form->hidden("action", "action", "execute", ""); ?>
                        <?= $form->hidden("valradio", "valradio", 0) ?>
                    </div>
                </div>
            </div>-->
        </div>
        <?
        $form->close_form();
        unset($form);
        ?>
    </div>
@stop

@section('script')
    <script>
        
        function getCookie(name) {
            var v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
            return v ? v[2] : null;
        }

        function setCookie(name, value, days) {
            var d = new Date;
            d.setTime(d.getTime() + 24 * 60 * 60 * 1000 * days);
            document.cookie = name + "=" + value + ";path=/;expires=" + d.toGMTString();
        }

        function deleteCookie(name) {
            setCookie(name, '', -1);
        }
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
                    url: 'add_order_product.php',
                    type: 'POST',
                    data: {
                        id: $(this).val(),
                        ord_id: {{$ord_id}},
                        add_more: 1
                    },
                    success: function (response) {
                        //location.reload();
                        setCookie('order_focus_id', {{$ord_id}}, 1);
                        location.href = 'listing.php?status=NEW';
                    }
                });
            });
        });

    </script>
@stop
@extends('module-master')

@section('content')
    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Danh sách</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        {!! $data_table !!}
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer">

            </div>
        </div>
    </section>
@stop

@section('header')

@stop

@section('script')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
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

        $('select.change_status_order').on('change', function () {
            var id = $(this).attr('id');
            var status = $(this).val();
            $('#contact_detail_' + id).modal('hide');
            if (status == 'BEING_TRANSPORTED') {
                $('#shipping_info_' + id).show();
            } else {
                $('#shipping_info_' + id).hide();
            }
            $('#order_change_status_note_' + id).modal('show');
            return;
        });

        $('select.change_order_payment_status').on('change', function () {
            var id = $(this).attr('id');
            var status = $(this).val();
            $('#contact_detail_' + id).modal('hide');
            $('#order_change_payemnt_status_note_' + id).modal('show');
            return;
        });

        function dialog_change_shipping_fee(id)
        {
            $('#contact_detail_' + id).modal('hide');
            $('#order_change_shipping_fee_' + id).modal('show');
        }


        function dialog_note(id)
        {
            $('#contact_detail_' + id).modal('hide');
            $('#order_note_' + id).modal('show');
        }

        function change_order_payment_status(id) {
            if (!confirm("Bạn có chắc chắn muốn thay đổi trạng thái ?")) return false;

            var status = $('select#' + id + '.change_order_payment_status').val();
            var note = $('textarea#payment_note_' + id).val();

            $.ajax({
                type: 'POST',
                url: 'change_payment_status.php',
                data: {
                    order_id: id,
                    status: status,
                    note: note
                },
                success: function (response) {
                    response = response.trim();
                    if (response.length > 0) {
                        alert(response);
                    }
                    location.reload();
                }
            });
        }

        function change_note(id) {

            var note = $('#order_note_'+id+' textarea#note_' + id).val();

            var data = {
                order_id: id,
                note: note
            };

            if (!confirm("Bạn có chắc chắn muốn thêm ghi chú cho đơn hàng này?"))
            {
                return false;
            }
            $.ajax({
                type: 'POST',
                url: 'order_note.php?status=<?=($status ?? 'NEW' )?>',
                data: data,
                success: function (response) {
                    response = response.trim();
                    if (response.length > 0) {
                        alert(response);
                    }
                    location.reload();
                }
            });
            }

        function change_shipping_fee(id) {

            var note = $('#order_change_shipping_fee_'+id+' textarea#note_' + id).val();

            var data = {
                order_id: id,
                note: note
            };

            data.shipping_fee = $('#order_change_shipping_fee_'+id+' #shipping_fee_' + id).val();

            if (!confirm("Bạn có chắc chắn muốn thay đổi phí vận chuyển ?"))
                return false;
            $.ajax({
                type: 'POST',
                url: 'change_shipping_fee.php?status=<?=($status ?? 'NEW' )?>',
                data: data,
                success: function (response) {
                    response = response.trim();
                    if (response.length > 0) {
                        alert(response);
                    }
                    location.reload();
                }
            });
        }

        function change_order_status(id) {

            var status = $('select#' + id + '.change_status_order').val();
            var note = $('textarea#note_' + id).val();

            var data = {
                order_id: id,
                status: status,
                note: note
            };
            if (status == 'BEING_TRANSPORTED') {
                data.shipping_carrier = $('#shipping_carrier_' + id).val();
                data.shipping_fee = $('#shipping_fee_' + id).val();
                data.shipping_code = $('#shipping_code_' + id).val();
                data.shipping_car = $('#shipping_car_' + id).val();
                data.shipping_number_car = $('#shipping_number_car_' + id).val();
                data.shipping_car_start = $('#shipping_car_start_' + id).val();
                data.shipping_car_phone = $('#shipping_car_phone_' + id).val();
                
                if(data.shipping_carrier.length <= 0 || data.shipping_fee.length <= 0 || data.shipping_code.length <= 0)
                {
                    $.toast({
                        // heading: '',
                        text: 'Bạn phải nhập thông tin vận chuyển',
                        position: 'bottom-right',
                        stack: false,
                        icon: 'error'
                    });
                    $('#shipping_carrier_' + id).focus();
                    return;
                }
            }

            if (!confirm("Bạn có chắc chắn muốn thay đổi trạng thái ?")) return false;
            $.ajax({
                type: 'POST',
                url: 'change_status_code.php?status=<?=($status ?? 'NEW' )?>',
                data: data,
                success: function (response) {
                    response = response.trim();
                    if (response.length > 0) {
                        alert(response);
                    }
                    location.reload();
                }
            });
        }

        var start = moment('2018');
        var end = moment();

        $('input[name="ord_created_at"]').daterangepicker({
            minYear: 2018,
            <?php if(!getValue('ord_created_at')): ?>
            startDate: start,
            endDate: end,
            <?php endif; ?>
            ranges: {
                'Tất cả': [moment('2018'), moment()],
                'Hôm nay': [moment(), moment()],
                'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                '7 Ngày trước': [moment().subtract(6, 'days'), moment()],
                '30 Ngày trước': [moment().subtract(29, 'days'), moment()],
                'Tháng này': [moment().startOf('month'), moment().endOf('month')],
                'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            "locale": {
                "format": "DD/MM/YYYY",
                "separator": " - ",
                "applyLabel": "Áp dụng",
                "cancelLabel": "Hủy",
                "fromLabel": "Từ",
                "toLabel": "Tới",
                "customRangeLabel": "Tùy chọn",
                "daysOfWeek": [
                    "CN",
                    "T2",
                    "T3",
                    "T4",
                    "T5",
                    "T6",
                    "T7"
                ],
                "monthNames": [
                    "Tháng 1",
                    "Tháng 2",
                    "Tháng 3",
                    "Tháng 4",
                    "Tháng 5",
                    "Tháng 6",
                    "Tháng 7",
                    "Tháng 8",
                    "Tháng 9",
                    "Tháng 10",
                    "Tháng 11",
                    "Tháng 12"
                ],
                "firstDay": 1
            }
        });

        // $('select.select2').on('change',function () {
        //     var _input =  $(this).attr('id');
        //     var _inputa =  $(this).attr('selected','selected');
        //     console.log(_inputa);
        //     alert(_inputa);
        //
        //     // $.ajax({
        //     //     type: 'POST',
        //     //     url: 'active.php',
        //     //     data: {
        //     //         id:  _input.substring(9),
        //     //     },
        //     //     success: function (response) {
        //     //         console.log('ok');
        //     //     }
        //     // });
        //
        // });


        function select_change_ord_status_code(id, value) {

            alert(id + '  ' + value);
        }

        function read_mark(id) {
            $.ajax({
                type: 'POST',
                url: 'read_mark.php',
                cache: false,
                data: {
                    id: id
                },
                success: function (response) {
                    location.reload();
                }
            });
        }

        function change_quantity(order_id, product_id, quantity) {

            if (confirm('Bạn có chắc thay đổi số lượng sản phẩm không?')) {
                $.ajax({
                    url: 'change_quantity.php',
                    type: 'POST',
                    data: {
                        order_id: order_id,
                        product_id: product_id,
                        quantity: quantity
                    },
                    success: function (response) {
                        $.toast({
                            // heading: '',
                            text: 'Thay đổi số lượng thành công',
                            position: 'bottom-right',
                            stack: false,
                            icon: 'success'
                        })
                        location.reload();
                    }
                });
            }

        }

        function renew(order_id, user_id) {
            if (confirm('Bạn có chắc muốn tạo lại đơn hàng này ko?')) {
                $.ajax({
                    url: 'renew.php',
                    type: 'POST',
                    data: {
                        order_id: order_id,
                        user_id: user_id
                    },
                    success: function (response) {
                        if(response == '1'){
                            $.toast({
                                // heading: '',
                                text: 'Hủy đơn thành công',
                                position: 'bottom-right',
                                stack: false,
                                icon: 'success'
                            })
                            location.href = 'add.php?ord_user_id=' + user_id;
                        }else{
                            alert(response);
                        }
                        
                    }
                });
            }
        }

        function get_district(province_id, order_id) {
            $.ajax({
                url: 'get_district_by_provice.php?status=<?=($status ?? 'NEW')?>',
                type: 'GET',
                data: {
                    province_id: province_id
                },
                success: function (response) {
                    $('#ord_district_id_' + order_id).html(response).focus();
                }
            });
        }

        function get_ward(district_id, order_id) {
            $.ajax({
                url: 'get_ward_by_district.php?status=<?=($status ?? 'NEW')?>',
                type: 'GET',
                data: {
                    district_id: district_id
                },
                success: function (response) {
                    $('#ord_ward_id_' + order_id).html(response).focus();
                }
            });
        }

        $(".ship_info").submit(function (event) {
            event.preventDefault(); //prevent default action
            var post_url = $(this).attr("action"); //get form action url
            var request_method = $(this).attr("method"); //get form GET/POST method
            var form_data = $(this).serialize(); //Encode form elements for submission

            if (confirm('Bạn có chắc thay đổi thông tin đơn hàng?')) {
                $.ajax({
                    url: post_url,
                    type: request_method,
                    data: form_data
                }).done(function (response) { //
                    $.toast({
                        // heading: '',
                        text: 'Thay đổi thông tin thành công',
                        position: 'bottom-right',
                        stack: false,
                        icon: 'success'
                    });
                });
            }

        });

        function reload_modal(order_id) {
            setCookie('order_focus_id', order_id, 1);
            location.reload();
        }
        function formatCurrencyVND(number) {
            // Định dạng số theo tiền tệ Việt Nam
            let formatted = new Intl.NumberFormat('vi-VN', {
                style: 'decimal',
                currency: 'VND'
            }).format(number);

            // Thay thế dấu . bằng dấu ,
            return formatted.replace(/\./g, ',');
        }
        function loadInputValueformatCurrency(inputElement,price){
            let value = inputElement.value;
            value = value.replace(/\,/g, '');
            value = parseInt(value);
            if(isNaN(value)){
                alert("Giá bán phải là số nguyên dương > 0");
                inputElement.value = formatCurrencyVND(price);
                return;
            }
            inputElement.value = formatCurrencyVND(value);
        }
        var order_focus_id = getCookie('order_focus_id');
        $('#contact_detail_' + order_focus_id).modal('show');
        deleteCookie('order_focus_id');
        function delete_product_order(orderId,id) {
            if (confirm('Bạn có muốn xóa sản phẩm này khỏi đơn hàng?')) {
                $.ajax({
                    url: 'delete_product_order.php',
                    type: 'GET',
                    data: {
                        orderId: orderId,
                        id: id,
                        user_id: $('#ord_user_id').val()
                    },
                    success: function (response) {
                        setCookie('order_focus_id', orderId, 1);
                        location.reload();
                    }
                });
            }
        }
    </script>
@stop
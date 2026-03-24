@extends('module-master')

@section('content')
    <div class="container-fluid">
        <?php
        $form = new form();
        $form->create_form("edit_debt", $fs_action, "post", "application/x-www-form-urlencoded");
        ?>

        <?php if ($fs_errorMsg): ?>
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
            {{-- Thông tin đơn hàng (chỉ đọc) --}}
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Thông tin đơn hàng</h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tr><th width="35%">Ngày mua</th><td>{{ isset($sae_export_date) && $sae_export_date ? date('d/m/Y', strtotime($sae_export_date)) : '' }}</td></tr>
                            <tr><th>Khách hàng</th><td>{{ $sae_customer_name ?? '' }}</td></tr>
                            <tr><th>Số điện thoại</th><td>{{ $sae_customer_phone ?? '' }}</td></tr>
                            <tr><th>Sản phẩm</th><td>{{ $sae_product_name ?? '' }}</td></tr>
                            <tr><th>SL SP mua</th><td>{{ number_format($sae_quantity_ban ?? 0) }}</td></tr>
                            <tr><th>Giá mua</th><td>{{ number_format($sae_unit_price ?? 0) }}</td></tr>
                            <tr><th>CP Khác</th><td>{{ number_format($sae_other_cost ?? 0) }}</td></tr>
                            <tr><th>Tổng mua</th><td><strong>{{ number_format($sae_total_ban ?? 0) }}</strong></td></tr>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Cập nhật thanh toán --}}
            <div class="col-md-6">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Cập nhật thanh toán</h3>
                    </div>
                    <div class="box-body">
                        <?= $form->select("Trạng thái TT", "sae_payment_status", "sae_payment_status", $payment_statuses, $sae_payment_status ?? 'Chưa thanh toán', '', 1) ?>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Ngày TT</label>
                            <div class="col-sm-9">
                                <input type="text" name="sae_payment_date" id="sae_payment_date"
                                       class="form-control datepicker-single"
                                       value="{{ $sae_payment_date_display ?? '' }}"
                                       autocomplete="off" readonly placeholder="DD/MM/YYYY">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12" style="text-align: center; margin-bottom: 20px;">
                <?= $form->button("submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "Cập nhật" . $form->ec . "Làm lại", "Cập nhật" . $form->ec . "Làm lại", "") ?>
                <?= $form->hidden("action", "action", "execute", "") ?>
                <a href="listing.php" class="btn btn-default">Quay lại</a>
            </div>
        </div>

        <?php
        $form->close_form();
        unset($form);
        ?>
    </div>
@stop

@section('header')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
@stop

@section('script')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        var dpConfig = {
            singleDatePicker: true,
            showDropdowns: true,
            autoApply: true,
            locale: {
                format: "DD/MM/YYYY",
                applyLabel: "Áp dụng",
                cancelLabel: "Hủy",
                daysOfWeek: ["CN","T2","T3","T4","T5","T6","T7"],
                monthNames: ["Tháng 1","Tháng 2","Tháng 3","Tháng 4","Tháng 5","Tháng 6",
                             "Tháng 7","Tháng 8","Tháng 9","Tháng 10","Tháng 11","Tháng 12"],
                firstDay: 1
            }
        };

        $('.datepicker-single').each(function () {
            var cfg = $.extend({}, dpConfig);
            if ($(this).val()) {
                cfg.startDate = moment($(this).val(), 'DD/MM/YYYY');
            }
            $(this).daterangepicker(cfg);
        });
    </script>
@stop

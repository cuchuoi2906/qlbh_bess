@extends('module-master')

@section('content')
    <div class="container-fluid">
        <?php
        $form = new form();
        $form->create_form("edit_warehouse_import", $fs_action, "post", "application/x-www-form-urlencoded");
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
            <!-- Cột trái -->
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Thông tin sản phẩm</h3>
                    </div>
                    <div class="box-body">

                        {{-- Select sản phẩm từ DB --}}
                        <?= $form->select("Tên Sản phẩm", "who_pro_id", "who_pro_id", $products_list, $who_pro_id ?? 0, '', 1) ?>
                        <?= $form->hidden("who_product_name", "who_product_name", $who_product_name ?? '') ?>

                        {{-- Ngày nhập --}}
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Ngày nhập <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="who_import_date" id="who_import_date"
                                       class="form-control datepicker-single"
                                       value="{{ $who_import_date_display ?? '' }}"
                                       autocomplete="off" readonly>
                            </div>
                        </div>

                        <?= $form->select("Đơn vị tính", "who_packaging_unit", "who_packaging_unit", $packaging_units, $who_packaging_unit ?? 0, '') ?>
                        <?= $form->text("Ghi chú", "who_note", "who_note", $who_note ?? '', "Ghi chú", 0) ?>
                    </div>
                </div>

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Số lượng</h3>
                    </div>
                    <div class="box-body">
                        <?= $form->text("Số lượng sp Nhập", "who_quantity", "who_quantity", $who_quantity ?? 0, "0", 0, "", "", 20, "", 'id="who_quantity"', "") ?>
                        <?= $form->text("Số lượng Kiện", "who_quantity_packing", "who_quantity_packing", $who_quantity_packing ?? 0, "0", 0, "", "", 20, "", 'id="who_quantity_packing"', "") ?>
                        <?= $form->text("Số lượng SP/Kiện", "who_carton_quantity", "who_carton_quantity", $who_carton_quantity ?? 0, "0", 0, "", "", 20, "", 'id="who_carton_quantity" readonly style="background:#f5f5f5"', "") ?>
                    </div>
                </div>

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Thông tin giá</h3>
                    </div>
                    <div class="box-body">
                        <?= $form->text("Giá nhập", "who_unit_price", "who_unit_price", $who_unit_price ?? 0, "0", 0, "", "", 20, "", 'id="who_unit_price"', "") ?>
                        <?= $form->text("CP Khác", "who_other_cost", "who_other_cost", $who_other_cost ?? 0, "0", 0, "", "", 20, "", 'id="who_other_cost"', "") ?>
                        <?= $form->text("Tổng nhập", "who_total_price", "who_total_price", $who_total_price ?? 0, "0", 0, "", "", 20, "", 'id="who_total_price" readonly style="background:#f5f5f5"', "") ?>
                    </div>
                </div>
            </div>

            <!-- Cột phải -->
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Thông tin nhà cung cấp</h3>
                    </div>
                    <div class="box-body">
                        <?= $form->text("Nhà cung cấp (NCC)", "who_supplier_name", "who_supplier_name", $who_supplier_name ?? '', "Tên nhà cung cấp", 0) ?>
                        <?= $form->text("Mã NCC", "who_supplier_code", "who_supplier_code", $who_supplier_code ?? '', "Mã nhà cung cấp", 0) ?>
                        <?= $form->text("Người nhận hàng", "who_receiver_name", "who_receiver_name", $who_receiver_name ?? '', "Người nhận hàng", 0) ?>
                        <?= $form->text("Kho nhận", "who_warehouse_name", "who_warehouse_name", $who_warehouse_name ?? '', "Tên kho nhận", 0) ?>
                    </div>
                </div>

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Thông tin lô hàng</h3>
                    </div>
                    <div class="box-body">
                        <?= $form->text("Số lô/LOT", "who_lot_number", "who_lot_number", $who_lot_number ?? '', "Số lô/LOT", 0) ?>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Ngày SX (MFG)</label>
                            <div class="col-sm-9">
                                <input type="text" name="who_mfg_date" id="who_mfg_date"
                                       class="form-control datepicker-single"
                                       value="{{ $who_mfg_date_display ?? '' }}"
                                       autocomplete="off" readonly placeholder="DD/MM/YYYY">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Hạn dùng EXP</label>
                            <div class="col-sm-9">
                                <input type="text" name="who_exp_date" id="who_exp_date"
                                       class="form-control datepicker-single"
                                       value="{{ $who_exp_date_display ?? '' }}"
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

        // Điền tên sản phẩm khi đổi select
        var productNames = {!! json_encode($products_list) !!};
        $('#who_pro_id').on('change', function () {
            var name = (productNames[$(this).val()] || '').replace(/\s*\[.*?\]\s*$/, '').trim();
            $('input[name="who_product_name"]').val(name);
        });

        // Tính tổng nhập: Giá nhập + CP Khác
        function calcTotal() {
            var price = parseFloat($('#who_unit_price').val()) || 0;
            var other = parseFloat($('#who_other_cost').val()) || 0;
            $('#who_total_price').val((price + other).toFixed(2));
        }

        // Tính số lượng SP/Kiện tự động
        function calcCarton() {
            var qty     = parseFloat($('#who_quantity').val()) || 0;
            var packing = parseFloat($('#who_quantity_packing').val()) || 0;
            $('#who_carton_quantity').val(packing > 0 ? (qty / packing).toFixed(2) : 0);
        }

        $(function () {
            $('#who_unit_price, #who_other_cost').on('input', calcTotal);
            $('#who_quantity, #who_quantity_packing').on('input', calcCarton);
        });
    </script>
@stop

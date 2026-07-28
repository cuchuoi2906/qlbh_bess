@extends('module-master')

@section('content')
    <div class="container-fluid">
        <?php
        $form = new form();
        $form->create_form("add_warehouse_export", $fs_action, "post", "application/x-www-form-urlencoded");
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
                        {{-- Select phiếu nhập kho --}}
                        <?= $form->select("Phiếu nhập kho", "sae_who_id", "sae_who_id", $warehouse_imports_list, $sae_who_id ?? 0, '', 1) ?>
                        <?= $form->hidden("sae_pro_id", "sae_pro_id", $sae_pro_id ?? 0) ?>
                        <?= $form->hidden("sae_product_name", "sae_product_name", $sae_product_name ?? '') ?>

                        {{-- Ngày xuất — daterangepicker single --}}
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Ngày xuất</label>
                            <div class="col-sm-9">
                                <input type="text" name="sae_export_date" id="sae_export_date"
                                       class="form-control datepicker-single"
                                       value="{{ $sae_export_date_display ?? date('d/m/Y') }}"
                                       autocomplete="off" readonly>
                            </div>
                        </div>

                        {{-- Loại xuất — select từ config --}}
                        <?= $form->select("Loại xuất", "sae_product_type", "sae_product_type", $export_types, $sae_product_type ?? '', '') ?>

                        <?= $form->text("Ghi chú", "sae_note", "sae_note", $sae_note ?? '', "Ghi chú", 0) ?>
                    </div>
                </div>

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Số lượng</h3>
                    </div>
                    <div class="box-body">
                        <?= $form->text("Số lượng sp Bán", "sae_quantity_ban", "sae_quantity_ban", $sae_quantity_ban ?? 0, "0", 0, "", "", 20, "", 'id="sae_quantity_ban"', '') ?>
                        <?= $form->text("Số lượng khả dụng (tồn kho)", "sae_quantity_kha_dung", "sae_quantity_kha_dung", $sae_quantity_kha_dung ?? 0, "0", 0, "", "", 20, "", 'id="sae_quantity_kha_dung" readonly style="background:#f5f5f5"', '') ?>
                        <?= $form->text("Số lượng còn lại", "sae_quantity_con_lai", "sae_quantity_con_lai", $sae_quantity_con_lai ?? 0, "0", 0, "", "", 20, "", 'id="sae_quantity_con_lai" readonly style="background:#f5f5f5"', '') ?>
                    </div>
                </div>

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Thông tin giá bán</h3>
                    </div>
                    <div class="box-body">
                        <?= $form->text("Giá bán", "sae_unit_price", "sae_unit_price", $sae_unit_price ?? 0, "0", 0, "", "", 20, "", 'id="sae_unit_price"', '') ?>
                        <?= $form->text("CP Khác", "sae_other_cost", "sae_other_cost", $sae_other_cost ?? 0, "0", 0, "", "", 20, "", 'id="sae_other_cost"', '') ?>
                        <?= $form->text("Tổng Bán", "sae_total_ban", "sae_total_ban", $sae_total_ban ?? 0, "0", 0, "", "", 20, "", 'id="sae_total_ban" readonly style="background:#f5f5f5"', '') ?>
                    </div>
                </div>
            </div>

            <!-- Cột phải -->
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Thông tin khách hàng</h3>
                    </div>
                    <div class="box-body">
                        <?= $form->text("Khách hàng", "sae_customer_name", "sae_customer_name", $sae_customer_name ?? '', "Tên khách hàng", 0) ?>
                        <?= $form->text("Số điện thoại", "sae_customer_phone", "sae_customer_phone", $sae_customer_phone ?? '', "Số điện thoại", 0) ?>
                        <?= $form->text("Địa chỉ/Kho nhận", "sae_customer_address", "sae_customer_address", $sae_customer_address ?? '', "Địa chỉ hoặc tên kho nhận", 0) ?>
                    </div>
                </div>

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Thông tin lô hàng</h3>
                    </div>
                    <div class="box-body">
                        <?= $form->text("Số lô/LOT", "sae_lot_number", "sae_lot_number", $sae_lot_number ?? '', "Tự động từ phiếu nhập", 0, "", "", 20, "", 'id="sae_lot_number" readonly style="background:#f5f5f5"', '') ?>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Ngày SX (MFG)</label>
                            <div class="col-sm-9">
                                <input type="text" name="sae_mfg_date" id="sae_mfg_date"
                                       class="form-control"
                                       value="{{ $sae_mfg_date_display ?? '' }}"
                                       readonly style="background:#f5f5f5" placeholder="Tự động từ phiếu nhập">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Hạn dùng EXP</label>
                            <div class="col-sm-9">
                                <input type="text" name="sae_exp_date" id="sae_exp_date"
                                       class="form-control"
                                       value="{{ $sae_exp_date_display ?? '' }}"
                                       readonly style="background:#f5f5f5" placeholder="Tự động từ phiếu nhập">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12" style="text-align: center; margin-bottom: 20px;">
                <?= $form->button("submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "Lưu phiếu xuất" . $form->ec . "Làm lại", "Lưu phiếu xuất" . $form->ec . "Làm lại", "") ?>
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

        // --- Format tiền tệ ---
        function formatMoney(n) {
            n = String(n).replace(/[^\d]/g, '').replace(/^0+/, '') || '0';
            return n.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }
        function parseMoney(str) {
            return parseFloat(String(str).replace(/,/g, '')) || 0;
        }
        var moneyFields = ['#sae_unit_price', '#sae_other_cost', '#sae_total_ban'];
        moneyFields.forEach(function(sel) {
            $(sel).on('input', function () {
                var pos = this.selectionStart;
                var oldLen = this.value.length;
                this.value = formatMoney(this.value);
                var newLen = this.value.length;
                this.setSelectionRange(pos + newLen - oldLen, pos + newLen - oldLen);
            });
        });

        // Format giá trị ban đầu khi load trang
        $(function () {
            moneyFields.forEach(function(sel) {
                var el = $(sel);
                if (el.val() && parseMoney(el.val()) > 0) {
                    el.val(formatMoney(el.val()));
                }
            });
        });

        // Xóa dấu phẩy trước khi submit form
        $('form').on('submit', function () {
            moneyFields.forEach(function(sel) {
                var el = $(sel);
                el.val(String(parseMoney(el.val())));
            });
        });

        // Auto-fill thông tin từ phiếu nhập kho khi đổi select
        var warehouseData = {!! json_encode($warehouse_imports_data) !!};
        $('#sae_who_id').on('change', function () {
            var id   = parseInt($(this).val());
            var data = warehouseData[id];
            if (data) {
                $('input[name="sae_pro_id"]').val(data.pro_id);
                $('input[name="sae_product_name"]').val(data.name);
                $('#sae_quantity_kha_dung').val(data.stock);
                $('input[name="sae_lot_number"]').val(data.lot);
                if (data.mfg) {
                    $('#sae_mfg_date').val(data.mfg).trigger('change');
                }
                if (data.exp) {
                    $('#sae_exp_date').val(data.exp).trigger('change');
                }
                calcConLai();
            } else {
                $('input[name="sae_pro_id"]').val(0);
                $('input[name="sae_product_name"]').val('');
                $('#sae_quantity_kha_dung').val(0);
                calcConLai();
            }
        });

        function calcTotalBan() {
            var qty   = parseFloat($('#sae_quantity_ban').val()) || 0;
            var price = parseMoney($('#sae_unit_price').val());
            var other = parseMoney($('#sae_other_cost').val());
            $('#sae_total_ban').val(formatMoney((qty * price) + other));
        }

        function calcConLai() {
            var khaDung = parseInt($('#sae_quantity_kha_dung').val()) || 0;
            var ban     = parseInt($('#sae_quantity_ban').val()) || 0;
            $('#sae_quantity_con_lai').val(Math.max(0, khaDung - ban));
        }

        $(function () {
            $('#sae_quantity_ban, #sae_unit_price, #sae_other_cost').on('input', calcTotalBan);
            $('#sae_quantity_ban, #sae_quantity_kha_dung').on('input', calcConLai);
        });
    </script>
@stop

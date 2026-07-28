@extends('module-master')

@section('content')
    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Danh sách phiếu xuất kho</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <form method="GET" class="form-inline" style="margin-bottom: 15px;">
                    <div class="form-group" style="margin-right: 10px;">
                        <label>Sản phẩm: </label>
                        <select name="sae_pro_id" class="form-control select2" style="min-width: 250px;">
                            @foreach($products_list as $k => $v)
                                <option value="{{ $k }}" {{ $sae_pro_id_filter == $k ? 'selected' : '' }}>{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Lọc</button>
                    <a href="listing.php" class="btn btn-default" style="margin-left: 5px;">Xóa lọc</a>
                    <button type="button" class="btn btn-warning" style="margin-left: 5px;" data-toggle="modal" data-target="#modalExportSlip">
                        <i class="fa fa-print"></i> Xuất phiếu
                    </button>
                </form>

                {{-- Modal xuất phiếu --}}
                <div class="modal fade" id="modalExportSlip" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Xuất phiếu giao hàng</h4>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Số điện thoại khách hàng:</label>
                                    <div class="input-group" style="max-width: 400px;">
                                        <input type="text" id="slip_phone" class="form-control" placeholder="Nhập SĐT...">
                                        <span class="input-group-btn">
                                            <button type="button" id="btn_search_phone" class="btn btn-primary">
                                                <i class="fa fa-search"></i> Tìm
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div id="slip_customer_info" style="display:none;margin-bottom:10px;padding:8px;background:#f5f5f5;border-radius:3px;">
                                    <strong>Khách hàng:</strong> <span id="slip_customer_name"></span><br>
                                    <strong>Địa chỉ:</strong> <span id="slip_customer_address"></span>
                                </div>
                                <div id="slip_result"></div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                                <button type="button" id="btn_print_slip" class="btn btn-success" style="display:none;">
                                    <i class="fa fa-print"></i> Xuất phiếu & In
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        {!! $data_table !!}
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
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
    // ===== Xuất phiếu giao hàng =====
    $(function () {
        var packagingMap = {0:'', 1:'Chai', 2:'Hộp', 3:'Gói'};

        function loadByPhone() {
            var phone = $('#slip_phone').val().trim();
            if (!phone) { alert('Vui lòng nhập số điện thoại'); return; }

            $('#slip_result').html('<p><i class="fa fa-spinner fa-spin"></i> Đang tải...</p>');
            $('#btn_print_slip').hide();
            $('#slip_customer_info').hide();

            $.getJSON('get_unexported.php', { phone: phone }, function (resp) {
                if (!resp.items || resp.items.length === 0) {
                    $('#slip_result').html('<div class="alert alert-warning">Không có sản phẩm chưa xuất phiếu cho SĐT này.</div>');
                    return;
                }

                if (resp.customer) {
                    $('#slip_customer_name').text(resp.customer.name || '');
                    $('#slip_customer_address').text(resp.customer.address || '');
                    $('#slip_customer_info').show();
                }

                var html = '<table class="table table-bordered table-hover">';
                html += '<thead><tr><th width="40"><input type="checkbox" id="chk_all_slip"></th><th>Sản phẩm</th><th>Số lô</th><th>HSD</th><th>ĐVT</th><th>SL</th><th>Đơn giá</th><th>Thành tiền</th></tr></thead><tbody>';
                resp.items.forEach(function (it) {
                    html += '<tr>';
                    html += '<td class="text-center"><input type="checkbox" class="chk_slip_item" value="' + it.sae_id + '"></td>';
                    html += '<td>' + (it.product_name || '') + '</td>';
                    html += '<td>' + (it.lot || '') + '</td>';
                    html += '<td>' + (it.exp_date || '') + '</td>';
                    html += '<td>' + (packagingMap[it.packaging_unit] || '') + '</td>';
                    html += '<td class="text-right">' + Number(it.quantity).toLocaleString('vi-VN') + '</td>';
                    html += '<td class="text-right">' + Number(it.unit_price).toLocaleString('vi-VN') + '</td>';
                    html += '<td class="text-right">' + Number(it.total).toLocaleString('vi-VN') + '</td>';
                    html += '</tr>';
                });
                html += '</tbody></table>';
                $('#slip_result').html(html);
                $('#btn_print_slip').show();
            }).fail(function () {
                $('#slip_result').html('<div class="alert alert-danger">Lỗi tải dữ liệu</div>');
            });
        }

        $('#btn_search_phone').on('click', loadByPhone);
        $('#slip_phone').on('keypress', function (e) { if (e.which === 13) { e.preventDefault(); loadByPhone(); } });

        $(document).on('change', '#chk_all_slip', function () {
            $('.chk_slip_item').prop('checked', $(this).is(':checked'));
        });

        $('#btn_print_slip').on('click', function () {
            var ids = [];
            $('.chk_slip_item:checked').each(function () { ids.push($(this).val()); });
            if (ids.length === 0) { alert('Vui lòng chọn ít nhất 1 sản phẩm'); return; }

            // Submit form POST mới sang print_slip.php trong tab mới
            var form = $('<form>', { method: 'POST', action: 'print_slip.php', target: '_blank' });
            form.append($('<input>', { type: 'hidden', name: 'ids', value: ids.join(',') }));
            $('body').append(form);
            form.submit();
            form.remove();

            // Đóng modal & reload listing để cập nhật trạng thái
            setTimeout(function () {
                $('#modalExportSlip').modal('hide');
                location.reload();
            }, 500);
        });

        // Reset modal khi đóng
        $('#modalExportSlip').on('hidden.bs.modal', function () {
            $('#slip_phone').val('');
            $('#slip_result').html('');
            $('#slip_customer_info').hide();
            $('#btn_print_slip').hide();
        });
    });

    $(function () {
        $('input[name="sae_export_date"]').daterangepicker({
            autoApply: false,
            autoUpdateInput: false,
            showDropdowns: true,
            <?php if (getValue('sae_export_date')): ?>
            startDate: moment('<?= getValue('sae_export_date') ?>'.split(' - ')[0], 'DD/MM/YYYY'),
            endDate: moment('<?= getValue('sae_export_date') ?>'.split(' - ')[1] || '<?= getValue('sae_export_date') ?>'.split(' - ')[0], 'DD/MM/YYYY'),
            <?php endif; ?>
            ranges: {
                'Hôm nay': [moment(), moment()],
                'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                '7 Ngày trước': [moment().subtract(6, 'days'), moment()],
                '30 Ngày trước': [moment().subtract(29, 'days'), moment()],
                'Tháng này': [moment().startOf('month'), moment().endOf('month')],
                'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            locale: {
                format: "DD/MM/YYYY",
                separator: " - ",
                applyLabel: "Áp dụng",
                cancelLabel: "Hủy",
                fromLabel: "Từ",
                toLabel: "Đến",
                customRangeLabel: "Tùy chọn",
                daysOfWeek: ["CN","T2","T3","T4","T5","T6","T7"],
                monthNames: ["Tháng 1","Tháng 2","Tháng 3","Tháng 4","Tháng 5","Tháng 6",
                             "Tháng 7","Tháng 8","Tháng 9","Tháng 10","Tháng 11","Tháng 12"],
                firstDay: 1
            }
        });
        // Chỉ set giá trị input khi user chọn ngày
        $('input[name="sae_export_date"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        });
        $('input[name="sae_export_date"]').on('cancel.daterangepicker', function() {
            $(this).val('');
        });
    });
    </script>
@stop

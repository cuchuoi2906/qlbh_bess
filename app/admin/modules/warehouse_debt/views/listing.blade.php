@extends('module-master')

@section('content')
    <div class="container-fluid">
        <h3 class="text-center" style="font-weight:bold; margin-bottom:15px;">QUẢN LÝ CÔNG NỢ</h3>

        {{-- Filter --}}
        <form method="GET" class="form-inline" style="margin-bottom:15px;">
            <div class="form-group" style="margin-right:8px;">
                <select name="who_pro_id" class="form-control input-sm select2" style="min-width:250px;">
                    @foreach($products_list as $k => $v)
                        <option value="{{ $k }}" {{ $who_pro_id_filter == $k ? 'selected' : '' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="margin-right:8px;">
                <input type="text" name="date_from" id="date_from" class="form-control input-sm datepicker-single"
                       placeholder="Từ ngày" value="{{ $date_from }}" autocomplete="off" readonly style="width:120px;">
            </div>
            <div class="form-group" style="margin-right:8px;">
                <input type="text" name="date_to" id="date_to" class="form-control input-sm datepicker-single"
                       placeholder="Đến ngày" value="{{ $date_to }}" autocomplete="off" readonly style="width:120px;">
            </div>
            <div class="form-group" style="margin-right:8px;">
                <select name="payment_status" class="form-control input-sm" style="width:180px;">
                    <option value="">-- Tất cả trạng thái --</option>
                    <?php foreach ($payment_statuses as $val => $label):
                        $colors = [
                            'Chưa thanh toán'     => '#e74c3c',
                            'Thanh toán một phần' => '#f39c12',
                            'Đã thanh toán'       => '#27ae60',
                        ];
                        $color = $colors[$val] ?? '#333';
                    ?>
                        <option value="{{ $val }}"
                            {{ $payment_status === $val ? 'selected' : '' }}
                            style="color:{{ $color }};font-weight:bold;">
                            {{ $label }}
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
            <button type="submit" class="btn btn-sm btn-primary">Tìm kiếm</button>
            <a href="listing.php" class="btn btn-sm btn-default" style="margin-left:4px;">Xóa lọc</a>
            <button type="submit" name="export" value="Export" class="btn btn-sm btn-success" style="margin-left:4px;">Export</button>
        </form>

        {!! $data_table !!}
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
            autoUpdateInput: false,
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
                cfg.autoUpdateInput = true;
            }
            $(this).daterangepicker(cfg);
            $(this).on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY'));
            });
        });
    </script>
@stop

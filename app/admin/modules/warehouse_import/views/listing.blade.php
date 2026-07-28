@extends('module-master')

@section('content')
    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Danh sách phiếu nhập kho</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <form method="GET" class="form-inline" style="margin-bottom: 15px;">
                    <div class="form-group" style="margin-right: 10px;">
                        <label>Sản phẩm: </label>
                        <select name="who_pro_id" class="form-control select2" style="min-width: 250px;">
                            @foreach($products_list as $k => $v)
                                <option value="{{ $k }}" {{ $who_pro_id_filter == $k ? 'selected' : '' }}>{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Lọc</button>
                    <a href="listing.php" class="btn btn-default" style="margin-left: 5px;">Xóa lọc</a>
                </form>
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
    $(function () {
        $('input[name="who_import_date"]').daterangepicker({
            autoApply: false,
            autoUpdateInput: false,
            showDropdowns: true,
            <?php if (getValue('who_import_date')): ?>
            startDate: moment('<?= getValue('who_import_date') ?>'.split(' - ')[0], 'DD/MM/YYYY'),
            endDate: moment('<?= getValue('who_import_date') ?>'.split(' - ')[1] || '<?= getValue('who_import_date') ?>'.split(' - ')[0], 'DD/MM/YYYY'),
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
        $('input[name="who_import_date"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        });
        $('input[name="who_import_date"]').on('cancel.daterangepicker', function() {
            $(this).val('');
        });
    });
    </script>
@stop

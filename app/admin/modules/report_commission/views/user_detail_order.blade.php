<!--<div class="row">
    <div class="col-xs-12">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th class="text-center bg-primary">Ðơn hàng</th>
                        <th class="text-center bg-primary">Tổng hoa hồng</th>
                        <th class="text-center bg-primary">ID</th>
                        <th class="text-center bg-primary">User nhận hoa hồng</th>
                        <th class="text-center bg-primary">Hoa hồng chia</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="field-total" style="text-align: right">
                        
                        </td>
                    </tr>
                    
                </tbody>
            </table>
        </div>
    </div>
</div>-->
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
@section('script')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
    <script>
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
    </script>
@stop
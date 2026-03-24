@extends('module-master')

@section('content')
    <div class="container-fluid">
        <h3 class="text-center" style="font-weight:bold; margin-bottom:20px;">
            THỐNG KÊ SẢN PHẨM VÀ DOANH THU
        </h3>

        {{-- Filter --}}
        <form method="GET" class="form-inline" style="margin-bottom:15px;">
            <div class="form-group" style="margin-right:10px;">
                <label>Năm:</label>
                <select name="year" class="form-control input-sm">
                    @foreach($year_list as $y)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="margin-right:10px;">
                <input type="text" name="search" class="form-control input-sm"
                       placeholder="Tìm sản phẩm..." value="{{ $search }}">
            </div>
            <button type="submit" class="btn btn-sm btn-primary">Tìm kiếm</button>
        </form>

        {{-- BẢNG 1: THỐNG KÊ SỐ LƯỢNG --}}
        <h4 style="font-weight:bold;">THỐNG KÊ SỐ LƯỢNG TỪNG SẢN PHẨM</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-condensed" id="tbl-qty">
                <thead style="background:#4CAF50; color:#fff;">
                    <tr>
                        <th>SỐ LƯỢNG SP</th>
                        <th>SL TỒN</th>
                        <th>TỔNG SL NHẬP</th>
                        <th>TỔNG SL BÁN</th>
                        @for($m = 1; $m <= 12; $m++)
                            <th>SL Tháng {{ $m }}</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @forelse($stats as $row)
                        <tr>
                            <td><strong>{{ $row['name'] }}</strong></td>
                            <td style="color:{{ $row['sl_ton'] < 0 ? 'red' : ($row['sl_ton'] > 0 ? 'green' : '') }}; font-weight:bold;">
                                {{ $row['total_import_qty'] || $row['total_export_qty'] ? number_format($row['sl_ton']) : '-' }}
                            </td>
                            <td>{{ $row['total_import_qty'] ? number_format($row['total_import_qty']) : '-' }}</td>
                            <td>{{ $row['total_export_qty'] ? number_format($row['total_export_qty']) : '-' }}</td>
                            @for($m = 1; $m <= 12; $m++)
                                <td>{{ $row["qty_m{$m}"] ? number_format($row["qty_m{$m}"]) : '' }}</td>
                            @endfor
                        </tr>
                    @empty
                        <tr><td colspan="16" class="text-center">Không có dữ liệu</td></tr>
                    @endforelse
                </tbody>
                @if(count($stats) > 0)
                <tfoot style="background:#f5f5f5; font-weight:bold;">
                    <tr>
                        <td>TỔNG</td>
                        <td style="color:{{ $totals['sl_ton'] < 0 ? 'red' : 'green' }};">
                            {{ number_format($totals['sl_ton']) }}
                        </td>
                        <td>{{ number_format($totals['total_import_qty']) }}</td>
                        <td>{{ number_format($totals['total_export_qty']) }}</td>
                        @for($m = 1; $m <= 12; $m++)
                            <td>{{ $totals["qty_m{$m}"] ? number_format($totals["qty_m{$m}"]) : '' }}</td>
                        @endfor
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>

        {{-- BẢNG 2: THỐNG KÊ DOANH THU --}}
        <h4 style="font-weight:bold; margin-top:30px;">THỐNG KÊ DOANH THU CHO TỪNG SẢN PHẨM</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-condensed" id="tbl-revenue">
                <thead style="background:#4CAF50; color:#fff;">
                    <tr>
                        <th>DOANH THU SP</th>
                        <th>LỖ - LÃI</th>
                        <th>TỔNG DT NHẬP</th>
                        <th>TỔNG DT BÁN</th>
                        @for($m = 1; $m <= 12; $m++)
                            <th>DT Tháng {{ $m }}</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @forelse($stats as $row)
                        <tr>
                            <td><strong>{{ $row['name'] }}</strong></td>
                            <td style="color:{{ $row['loi_lai'] < 0 ? 'red' : ($row['loi_lai'] > 0 ? 'green' : '') }}; font-weight:bold;">
                                {{ $row['total_import_amount'] || $row['total_export_amount'] ? number_format($row['loi_lai']) : '-' }}
                            </td>
                            <td>{{ $row['total_import_amount'] ? number_format($row['total_import_amount']) : '-' }}</td>
                            <td>{{ $row['total_export_amount'] ? number_format($row['total_export_amount']) : '-' }}</td>
                            @for($m = 1; $m <= 12; $m++)
                                <td>{{ $row["amt_m{$m}"] ? number_format($row["amt_m{$m}"]) : '' }}</td>
                            @endfor
                        </tr>
                    @empty
                        <tr><td colspan="16" class="text-center">Không có dữ liệu</td></tr>
                    @endforelse
                </tbody>
                @if(count($stats) > 0)
                <tfoot style="background:#f5f5f5; font-weight:bold;">
                    <tr>
                        <td>TỔNG</td>
                        <td style="color:{{ $totals['loi_lai'] < 0 ? 'red' : 'green' }};">
                            {{ number_format($totals['loi_lai']) }}
                        </td>
                        <td>{{ number_format($totals['total_import_amount']) }}</td>
                        <td>{{ number_format($totals['total_export_amount']) }}</td>
                        @for($m = 1; $m <= 12; $m++)
                            <td>{{ $totals["amt_m{$m}"] ? number_format($totals["amt_m{$m}"]) : '' }}</td>
                        @endfor
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
@stop

@section('header')
    <style>
        #tbl-qty th, #tbl-qty td,
        #tbl-revenue th, #tbl-revenue td {
            white-space: nowrap;
            text-align: center;
            vertical-align: middle;
            font-size: 13px;
            padding: 5px 8px;
        }
        #tbl-qty td:first-child, #tbl-revenue td:first-child,
        #tbl-qty th:first-child, #tbl-revenue th:first-child {
            text-align: left;
            min-width: 140px;
        }
    </style>
@stop

<div class="text-center">
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#contact_detail_{{$row->id}}">
        Xem chi tiết
    </button>
</div>

<!-- Modal -->
<div class="modal fade" id="contact_detail_{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="exampleModalLabel">Lịch sử nạp tiền</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php /*<div class="col-md-12">
                        <table class="table table-striped">
                            <tr>
                                <td>Người đặt:</td>
                                <td>{{ $row->ship_name }}</td>
                            </tr>
                            <tr>
                                <td>Số điện thoại:</td>
                                <td>{{ $row->ship_phone }}</td>
                            </tr>
                            <tr>
                                <td>Email:</td>
                                <td>{{ $row->ship_email }}</td>
                            </tr>
                            <tr>
                                <td>Địa chỉ:</td>
                                <td>{{ $row->ship_address }}</td>
                            </tr>
                            <tr>
                                <td>Tổng số sản phẩm:</td>
                                <td>{{ $total }}</td>
                            </tr>
                            <tr>
                                <td>Tổng tiền:</td>
                                <td>{{ number_format($row->amount) }} VND</td>
                            </tr>
                            <tr>
                                <td>Trang thái:</td>
                                <td>{{ \App\Models\Orders\Orders::$status[$row->status_code] }}</td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    {{$row->note}}
                                </td>
                            </tr>
                        </table>
                    </div> */ ?>

                    <div class="col-md-12">
                        <table class="table table-hover table-bordered">
                            <tr>
                                <th class="text-center">Admin</th>
                                <th class="text-center">IP</th>
                                <th class="text-center">Số tiền cũ</th>
                                <th class="text-center">Số tiền thêm vào</th>
                                <th class="text-center">Số tiền giảm đi</th>
                                <th class="text-center">Số tiền mới</th>
                                <th class="text-center">Ngày thực hiện</th>
                                <th class="text-center">Tài khoản</th>
                                <th class="text-center">Ghi chú</th>
                            </tr>

                            @foreach ($wallet_log ?: [] as $item)
                                <tr>
                                    <td class="text-center">{{$item->adminInfo->loginname}}</td>
                                    <td class="text-center">{{$item->uwl_ip}}</td>
                                    <td class="text-right">{{format_money( $item->uwl_money_old)}}</td>
                                    <td class="text-right">{{format_money( $item->uwl_money_add)}}</td>
                                    <td class="text-right">{{format_money( $item->uwl_money_reduction)}}</td>
                                    <td class="text-right">{{format_money( $item->uwl_money_new)}}</td>
                                    <td class="text-center">{{date( "d-m-Y", strtotime($item->uwl_created_at))}}</td>
                                    <td class="text-center">{{($item->uwl_wallet_type == 0) ? 'Tiền nạp' : 'Hoa hồng'}}</td>
                                    <td class="text-center">{{$item->note}}</td>
                                </tr>
                            @endforeach

                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng lại</button>
            </div>
        </div>
    </div>
</div>
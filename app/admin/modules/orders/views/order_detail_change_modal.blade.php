<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#contact_detail_{{$row->id}}">
    Xem chi tiết
</button>


<!-- Modal -->
<div class="modal fade" id="contact_detail_{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                {{--<h5 class="modal-title" id="exampleModalLabel">Chi tiết đơn hàng</h5>--}}
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="print_content_{{$row->id}}">
                <div class="row">
                    <div class="col-md-12">
                        <form action="change_ship_info.php?ord_id={{$row->id}}&status=<?=($status ?? 'NEW')?>" id="ship_info_{{$row->id}}"
                              class="ship_info table-responsive" method="post">
                            <table class="table table-striped">
                                <tr>
                                    <td>Mã đơn:</td>
                                    <td>
                                        {{ $row->code }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Người đặt:</td>
                                    <td>
                                        {{($row->user->phone ? $row->user->phone : $row->user->email). ' / ' .  $row->user->name .' / '.($row->user->id)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Người nhận:</td>
                                    <td>
                                        <input class="form-control" name="ord_ship_name" id="ord_ship_name"
                                               value="{{ $row->ship_name }}">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Số điện thoại:</td>
                                    <td>
                                        <input class="form-control" name="ord_ship_phone" id="ord_ship_phone"
                                               value="{{ $row->ship_phone }}">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Email:</td>
                                    <td>
                                        <input class="form-control" name="ord_ship_email" id="ord_ship_email"
                                               value="{{ $row->ship_email }}">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Địa chỉ:</td>
                                    <td>
                                        <input class="form-control" name="ord_ship_address" id="ord_ship_address"
                                               value="{{$row->ship_address}}">
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                        <select class="form-control" name="ord_province_id"
                                                id="ord_province_id_{{$row->id}}"
                                                onchange="get_district(this.value, {{$row->id}})">
                                            <option value="0">Chọn tỉnh thành</option>
                                            @foreach($provinces as $province)
                                                @if($province->id == $row->province_id)
                                                    <option selected
                                                            value="{{$province->id}}">{{$province->name}}</option>
                                                @else
                                                    <option value="{{$province->id}}">{{$province->name}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <select class="form-control" name="ord_district_id"
                                                id="ord_district_id_{{$row->id}}"
                                                onchange="get_ward(this.value, {{$row->id}})">
                                            <option value="0">Chọn quận / huyện</option>
                                            @foreach($districts as $district)
                                                @if($district->id == $row->district_id)
                                                    <option selected
                                                            value="{{$district->id}}">{{$district->name}}</option>
                                                @else
                                                    <option value="{{$district->id}}">{{$district->name}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <select class="form-control" name="ord_ward_id" id="ord_ward_id_{{$row->id}}">
                                            <option value="0">Chọn phường / xã</option>
                                            @foreach($wards as $ward)
                                                @if($ward->id == $row->ward_id)
                                                    <option selected
                                                            value="{{$ward->id}}">{{$ward->name}}</option>
                                                @else
                                                    <option value="{{$ward->id}}">{{$ward->name}}</option>
                                                @endif
                                            @endforeach
                                        </select>

                                    </td>
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
                                    <td>Cách nhận hoa hồng trực tiếp:</td>
                                    <td>{{ $row->commission_type == 2 ? 'Cộng vào ví hoa hồng':'Trừ trực tiếp vào giá tiền' }}</td>
                                </tr>
                                <tr>
                                    <td>Trạng thái:</td>
                                    <td>
                                        @if($row->status_code != \App\Models\Order::SUCCESS)

                                            <select id="{{$row->id}}" current_value="{{$row->status_code}}"
                                                    class="change_status_order change_status_order_{{$row->id}}"
                                                    name="change_status_order">
                                                @foreach(status_list($row->status_code) as $key => $status)
                                                    @if($key != $row->status_code)
                                                        <option value="{{$key}} ">{{$status}}</option>
                                                    @else
                                                        <option disabled value="{{$key}} "
                                                                selected="selected">{{$status}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        @else
                                            {{ \App\Models\Order::$status[$row->status_code] }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Hình thức thanh toán:</td>
                                    <td>{{ \App\Models\Order::paymentTypes()[$row->payment_type] }}</td>
                                </tr>
                                <tr>
                                    <td>Trạng thái thanh toán:</td>
                                    <td>
                                        @if($row->payment_type == \App\Models\Order::PAYMENT_TYPE_BANKING && $row->status_code == \App\Models\Order::NEW && $row->payment_status != \App\Models\Order::PAYMENT_STATUS_SUCCESS)
                                            <select id="{{$row->id}}" class="change_order_payment_status"
                                                    current-value="{{$row->payment_status}}">
                                                @foreach(\App\Models\Order::paymentStatus() as $status => $label)
                                                    <option {{ $row->payment_status == $status ? 'selected': '' }} value="{{$status}}">
                                                        {{$label}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            {{ \App\Models\Order::paymentStatus()[$row->payment_status] }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <textarea class="form-control" placeholder="Ghi chú"
                                                  name="ord_note">{{$row->note}}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <input type="submit" value="Thay đổi thông tin">
                                        <input type="reset" value="Tải lại" onclick="reload_modal({{$row->id}})">
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>

                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Mã sản phẩm</th>
                                    <th>Sản phẩm</th>
                                    {{--<td>SKU</td>--}}
                                    <th>Số lượng</th>
                                    <th>Giá</th>
                                    <th>Chiết khấu</th>
                                    <th>
                                        Tổng tiền thanh toán
                                    </th>
                                </tr>

                                <form>
                                    @foreach ($row->products as $product)

                                        <tr>
                                            <td>
                                                {{$product->info->pro_code}}
                                            </td>
                                            <td>
                                                {{$product->info->name}}
                                            </td>
                                            {{--<td>--}}
                                            {{--{{$product->info->sku}}--}}
                                            {{--</td>--}}
                                            <td>

                                                
                                                    {{$product->quantity}}
                                                


                                            </td>
                                            <td>
                                                {{ number_format($product->price) }}
                                            </td>
                                            <td>
                                                {{ number_format($product->price - $product->sale_price) }}
                                            </td>
                                            <td>
                                                {{ number_format($product->quantity * $product->sale_price)}}
                                            </td>
                                        </tr>

                                    @endforeach
                                </form>
                                <tr>
                                    <td style="text-align: right" colspan="4">Tổng tiền</td>
                                    <td><span style="color: red;">{{ number_format($row->amount) }} VND</span></td>
                                </tr>

                            </table>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Hoa hồng</th>
                                    <?php if($is_admin): ?>
                                    <th>VAT</th>
                                    <?php endif; ?>
                                    <th>Người hưởng</th>
                                    {{--<th>Loại hoa hồng</th>--}}
                                    <th>Trạng thái</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $total_commission = 0;
                                $total_vat = 0;
                                ?>
                                @foreach($row->commissions AS $commission)
                                    @if($commission->amount)
                                        <tr>
                                            <td>
                                                {{number_format($commission->amount)}}
                                                <?php if ($commission->type == 0) {
                                                    $total_commission += $commission->amount;
                                                } ?>
                                            </td>
                                            <?php if($is_admin): ?>
                                            <td>
                                                <?php
                                                $total_vat += $commission->vat;
                                                ?>
                                                {{number_format($commission->vat)}}
                                            </td>
                                            <?php endif; ?>
                                            <td>
                                                {{$commission->user->name ? $commission->user->name : $commission->user->email}}
                                                ({{$commission->user->id}})
                                            </td>
                                            <?/*
                                    <td>
                                        @if($commission->is_dirrect)
                                            Hoa hồng trực tiếp
                                        @else
                                            Hoa hồng hệ thống
                                        @endif
                                    </td>
*/?>
                                            <td>

                                                @if($commission->type == 1)
                                                    Hoa hồng chỉ dùng để lên cấp
                                                @else
                                                    @if($commission->is_direct)
                                                        @if($row->commission_type == 2)
                                                            {{\App\Models\OrderCommission::statuses()[$commission->status_code]}}
                                                        @else
                                                            Đã trừ vào giá tiền
                                                        @endif
                                                        (Hoa hồng trực tiếp)
                                                    @else
                                                        {{\App\Models\OrderCommission::statuses()[$commission->status_code]}}
                                                    @endif
                                                @endif


                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td style="color: red">
                                        {{number_format($total_commission)}}
                                    </td>
                                    <?php if($is_admin): ?>
                                    <td style="color: red">
                                        {{number_format($total_vat)}}
                                    </td>
                                    <?php endif; ?>
                                    <td colspan="2" style="text-align: left">Tổng (
                                        = <?php echo round(($total_commission / $row->amount) * 100) ?>% giá trị đơn
                                        hàng)
                                    </td>
                                </tr>
                                </tfoot>


                            </table>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Thời gian</th>
                                    <th>Trạng thái đơn hàng</th>
                                    <th>Trạng thái thanh toán</th>
                                    <th>Người xử lý</th>
                                    <th>Ghi chú</th>
                                </tr>

                                @foreach ($row->logs as $log)

                                    <tr>
                                        <td>
                                            {{$log->updated_at??$row->created_at}}
                                        </td>
                                        <td>
                                            @if($log->old_status_code == $log->new_status_code)
                                                {{\App\Models\Order::$status[$log->old_status_code] ?? ''}}
                                            @else
                                                {{\App\Models\Order::$status[$log->old_status_code] ?? ''}}
                                                => {{\App\Models\Order::$status[$log->new_status_code] ?? ''}}
                                            @endif
                                        </td>
                                        <td>
                                            @if($log->old_payment_status == $log->new_payment_status)
                                                {{\App\Models\Order::paymentStatus()[$log->old_payment_status] ?? ''}}
                                            @else
                                                {{\App\Models\Order::paymentStatus()[$log->old_payment_status] ?? ''}}
                                                => {{\App\Models\Order::paymentStatus()[$log->new_payment_status] ?? ''}}
                                            @endif
                                        </td>
                                        <td>
                                            {{$log->admin ? $log->admin->name : 'Hệ thống'}}
                                        </td>
                                        <td>
                                            {{$log->note}}
                                        </td>
                                    </tr>

                                @endforeach

                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="renew({{$row->id}}, {{$row->user->id}})">
                    Tạo lại đơn
                </button>

                <button class="btn btn-secondary" onclick="dialog_note({{$row->id}})">
                    Ghi chú
                </button>
                <button class="btn btn-secondary" onclick="printDivId('print_content_{{$row->id}}')">
                    In
                </button>
                <button type="button" class="btn btn-secondary" onclick="reload_modal({{$row->id}})">Tải lại</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng lại</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="order_change_status_note_{{$row->id}}" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Xác nhận thay đổi trạng thái đơn</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form>
                            <div class="form-group">
                                <label>Ghi chú</label>
                                <textarea class="form-control" name="note_<?=$row->id?>"
                                          id="note_<?=$row->id?>" rows="3"
                                          placeholder="Nhập nội dung ghi chú ..."></textarea>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="change_order_status(<?=$row->id?>)">Xác nhận
                </button>
                <button type="button" onclick="location.reload()" class="btn btn-secondary" data-dismiss="modal">Đóng
                    lại
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="order_change_payemnt_status_note_{{$row->id}}" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Xác nhận thay đổi trạng thái thanh toán</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form>
                            <div class="form-group">
                                <label>Ghi chú</label>
                                <textarea class="form-control" name="note_<?=$row->id?>"
                                          id="payment_note_<?=$row->id?>" rows="3"
                                          placeholder="Nhập nội dung ghi chú ..."></textarea>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="change_order_payment_status(<?=$row->id?>)">
                    Xác nhận
                </button>
                <button type="button" onclick="location.reload()" class="btn btn-secondary" data-dismiss="modal">
                    Đóng lại
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="order_note_{{$row->id}}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true">
   <div class="modal-dialog" role="document">
       <div class="modal-content">
           <div class="modal-header">
               <h5 class="modal-title" id="exampleModalLabel">Ghi chú</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">&times;</span>
               </button>
           </div>
           <div class="modal-body">
               <div class="row">
                   <div class="col-md-12">
                       <form>
                           <input type="hidden" value="note" name="type">
                           <div class="form-group">
                               <label>Ghi chú</label>
                               <textarea class="form-control" name="note_<?=$row->id?>"
                                         id="note_<?=$row->id?>" rows="3"
                                         placeholder="Nhập nội dung ghi chú ..."></textarea>
                           </div>
                       </form>
                   </div>
               </div>
           </div>
           <div class="modal-footer">
               <button type="button" class="btn btn-secondary" onclick="change_note(<?=$row->id?>)">Xác
                   nhận
               </button>
               <button type="button" onclick="location.reload()" class="btn btn-secondary" data-dismiss="modal">
                   Đóng
                   lại
               </button>
           </div>
       </div>
   </div>
</div>

@section('script')

@stop
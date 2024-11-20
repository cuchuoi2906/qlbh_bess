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
            <input type="hidden" id="title_print{{$row->id}}" name="title_print{{$row->id}}" value="{{$row->user->name.'_'.$row->code}}" />
            <div class="modal-body section-to-print">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
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
                                    <td>Số điện thoại người nhận:</td>
                                    <td>{{ $row->ship_phone }}</td>
                                </tr>
                                <tr>
                                    <td>Địa chỉ:</td>
                                    <td>
                                        {{ $row->ship_address }}
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
                                                        <option value="{{$key}}">{{$status}}</option>
                                                    @else
                                                        <option disabled value="{{$key}}"
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
                                        @if($row->status_code != \App\Models\Order::SUCCESS && $row->payment_status != \App\Models\Order::PAYMENT_STATUS_SUCCESS)
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
                                        {{$row->note}}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($row->status_code != \App\Models\Order::PENDING)
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <td>Nhà xe:</td>
                                    <td>{{ $row->shipping_car }}</td>
                                </tr>
                                <tr>
                                    <td>Giờ khởi hành</td>
                                    <td>{{ $row->shipping_car_start }}</td>
                                </tr>
                                <tr>
                                    <td>Biển số xe</td>
                                    <td>{{ $row->shipping_number_car }}</td>
                                </tr>
                                <tr>
                                    <td>Số ĐT nhà xe</td>
                                    <td>{{ $row->shipping_car_phone }}</td>
                                </tr>
                                <tr>
                                    <td>Phí vận chuyển</td>
                                    <td>{{ number_format($row->ord_shipping_fee) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    @endif;


                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th>STT</th>
                                    <th>Sản phẩm</th>
                                    {{--<td>SKU</td>--}}
                                    <th>Số lượng</th>
                                    <th>Giá</th>
                                    <th>
                                        Tổng tiền thanh toán
                                    </th>
                                </tr>

                                <form>
                                    @foreach ($row->products as $product)
                                        <tr>
                                            <td>
                                                {{$loop->iteration}}
                                            </td>
                                            <td>
                                                {{$product->info->name}}
                                            </td>
                                            {{--<td>--}}
                                            {{--{{$product->info->sku}}--}}
                                            {{--</td>--}}
                                            <td>

                                                @if($row->status_code == \App\Models\Order::NEW && $row->payment_status == \App\Models\Order::PAYMENT_STATUS_NEW)
                                                    <input type="number"
                                                           name="quantity[{{$product->info->id}}]"
                                                           value="{{$product->quantity}}"
                                                           onchange="change_quantity({{$row->id}}, {{$product->info->id}}, this.value)"
                                                    >
                                                @else
                                                    {{$product->quantity}}
                                                @endif


                                            </td>
                                            <td>
                                                {{ number_format(($product->orp_sale_price ? $product->orp_sale_price : $product->price)) }}
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
                                    <td style="color: red">
                                        {{number_format($total_vat)}}
                                    </td>
                                    <td colspan="" style="text-align: left">Tổng (
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
                                            {{$log->admin ? $log->admin->name : ($log->updated_by == -1 ? 'Người dùng' : 'Hệ thống')}}
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
            <div class="modal-body section-to-print" id="print_content_{{$row->id}}" style="display:none;">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered" style="font-size: 12px;margin-bottom: 0px;">
                                <tr>
                                    <td colspan="2">
                                        <span>website: vuaduoc.com</span> 
                                        <span style="float: right;">
                                            <img width="100px" src="https://vuaduoc.com/assets//images/logo.png" alt="logo">
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 100px;">Khách hàng</td>
                                    <td>{{$row->user->name}}</td>
                                </tr>
                                <tr>
                                    <td>Số điện thoại</td>
                                    <td>{{$row->ship_phone}}</td>
                                </tr>
                                <tr>
                                    <td>Địa chỉ</td>
                                    <td>{{$row->ship_address}}</td>
                                </tr>
                                <tr>
                                    <td>Ghi chú</td>
                                    <td>{{$row->note}}</td>
                                </tr>
                            </table>
                            <table class="table table-striped" style="font-size: 13px;margin-bottom: 0px;">
                                <tbody>
                                    <tr>
                                        <td>Mã đơn hàng:</td>
                                        <td>{{$row->code}}</td>
                                    <tr>
                                    <tr>
                                        <td>Trạng thái chuyển khoản</td>
                                        <td>
                                            {{ \App\Models\Order::paymentStatus()[$row->payment_status] }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Tổng đơn hàng</td>
                                        <td>
                                            {{ number_format($row->amount)}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Cước vận chuyển: {{number_format($row->ord_shipping_fee)}}</td>
                                        <td>
                                            Phí ship: Người gửi trả
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Tiền thu hộ</td>
                                        <td>
                                            <label>{{ number_format($row->amount + $row->ord_shipping_fee)}}</label>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table table-bordered" bgcolor="#00ff00" style="font-size: 13px;margin-bottom: 0px;">
                                <tr>
                                    <td>Nhà xe:</td>
                                    <td><label>{{ $row->shipping_car }}</label></td>
                                </tr>
                                <tr>
                                    <td>Giờ khởi hành: <label>{{ $row->shipping_car_start }}</label></td>
                                    <td>Số ĐT nhà xe: <label>{{ $row->shipping_car_phone }}</label></td>
                                </tr>
                                <tr>
                                    <td>Biển số xe</td>
                                    <td>{{ $row->shipping_number_car }}</td>
                                </tr>
                                <tr>
                                    <td>Ghi chú</td>
                                    <td>{{ $row->shipping_note }}</td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <div class="col-md-7">
                                            <div class="item">
                                                <img src="https://vuaduoc.com/assets/images/qr_chuyen_khoan_new.jpg?2" alt="qr-code" height="130px">
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="col-md-5" style="font-size: 16px">
                                            <div class="item">
                                                <label for="">Người nhận:</label>
                                                <div class="value fw-bold">Dương Việt Hùng</div>
                                            </div>
                                            <div class="item">
                                                <label for="">Số tài khoản:</label>
                                                <div class="value fw-bold">19020234693028</div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="printDivId('print_content_{{$row->id}}',{{$row->id}})">
                    In
                </button>
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
                        <form class="form-horizontal">
                            <div id="shipping_info_<?=$row->id?>" style="display: none">
                                <div class="form-group">
                                    <label class="col-sm-3">Nhà xe</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" type="text" name="shipping_car_<?=$row->id?>"
                                               id="shipping_car_<?=$row->id?>"
                                               placeholder="Nhà xe"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3">Biển số xe</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" type="text" name="shipping_number_car_<?=$row->id?>"
                                           id="shipping_number_car_<?=$row->id?>"
                                           placeholder="Biển số xe"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3">Giờ khởi hành</label>
                                    <div class="col-sm-9">
                                        <input value="<?=$row->shipping_car_start?>" class="form-control" type="text" name="shipping_car_start_<?=$row->id?>"
                                               id="shipping_car_start_<?=$row->id?>"
                                               placeholder="Giờ khởi hành"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3">Số ĐT nhà xe</label>
                                    <div class="col-sm-9">
                                        <input value="<?=$row->shipping_car_phone; ?>" class="form-control" type="text" name="shipping_car_phone_<?=$row->id?>"
                                           id="shipping_car_phone_<?=$row->id?>"
                                           placeholder="Số ĐT nhà xe"/>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-sm-3">Cước vận chuyển</label>
                                    <div class="col-sm-9">
                                        <input value="<?=$row->shipping_fee?>" class="form-control" name="shipping_fee_<?=$row->id?>"
                                           id="shipping_fee_<?=$row->id?>"
                                           placeholder="Phí vận chuyển" oninput="loadInputValueformatCurrency(this,0)" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2">Ghi chú</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="note_<?=$row->id?>"
                                          id="note_<?=$row->id?>" rows="3"
                                          placeholder="Nhập nội dung ghi chú ..."></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="change_order_status(<?=$row->id?>)">Xác
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

<div class="modal fade" id="order_change_shipping_fee_{{$row->id}}" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Xác nhận thay đổi phí vận chuyển</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form>
                            <input type="hidden" value="change_shipping_fee" name="type">
                            <div id="shipping_info_<?=$row->id?>" style="">
                                <div class="form-group">
                                    <label>Đơn vị vận chuyển</label>
                                    <input class="form-control" type="text" name="shipping_carrier_<?=$row->id?>"
                                           readonly
                                           id="shipping_carrier_<?=$row->id?>"
                                           value="<?=$row->shipping_carrier?>"
                                           placeholder="Nhập đơn vị vận chuyển"/>
                                </div>
                                <div class="form-group">
                                    <label>Mã vận đơn</label>
                                    <input class="form-control" type="text" name="shipping_code_<?=$row->id?>"
                                           readonly
                                           id="shipping_code_<?=$row->id?>"
                                           value="<?=$row->shipping_code?>"
                                           placeholder="Mã vận đơn"/>
                                </div>
                                <div class="form-group">
                                    <label>Phí vận chuyển</label>
                                    <input value="<?=$row->shipping_fee?>"
                                           class="form-control"
                                           type="number"
                                           name="shipping_fee_<?=$row->id?>"
                                           id="shipping_fee_<?=$row->id?>"
                                           placeholder="Phí vận chuyển"/>
                                </div>
                            </div>
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
                <button type="button" class="btn btn-secondary" onclick="change_shipping_fee(<?=$row->id?>)">Xác
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
                <button type="button" class="btn btn-secondary"
                        onclick="change_order_payment_status(<?=$row->id?>)">
                    Xác nhận
                </button>
                <button type="button" onclick="location.reload()" class="btn btn-secondary" data-dismiss="modal">
                    Đóng lại
                </button>
            </div>
        </div>
    </div>
</div>
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#contact_detail_{{$row->id}}">
    Xem chi tiết
</button>


<!-- Modal -->
<div class="modal fade" id="contact_detail_{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document" style="width: 1000px;">
        <div class="modal-content">
            <div class="modal-header">
                {{--<h5 class="modal-title" id="exampleModalLabel">Chi tiết đơn hàng</h5>--}}
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="print_content_send_partner_{{$row->id}}" style="display: none;">
                <div class="row">
                    <input type="hidden" id="title_print{{$row->id}}" name="title_print{{$row->id}}" value="{{$row->user->name.'_'.$row->code}}" />
                    <div class="col-md-12">
                        <table class="table table-striped">
                            <tr>
                                <td colspan="2" align="right">
                                    <img width="100px" src="<?php echo env("URL_WEB"); ?>/assets//images/logo.png" alt="logo"><br />
                                    website: <?php echo env("DOMAIN"); ?>
                                </td>
                            </tr>
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
                                <td>Tổng số sản phẩm:</td>
                                <td>{{ $total }}</td>
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
                                <td>Ghi chú:</td>
                                <td colspan="2">
                                    {{$row->note}}
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <form action="change_order_price.php?ord_id={{$row->id}}&status=NEW" id="order_price_{{$row->id}}"
                              class="ship_info table-responsive" method="post">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>STT</th>
                                        <th>Sản phẩm</th>
                                        {{--<td>SKU</td>--}}
                                        <th>Số lượng</th>
                                        <th>Giá</th>
                                        <!--<th>Chiết khấu</th>-->
                                        <th>
                                            Tổng tiền thanh toán
                                        </th>
                                    </tr>
                                    <?php
                                    $totalAmountAll = 0;
                                    $arrProduct = [];
                                    $i=0;
                                    ?>
                                    @foreach ($row->products as $product)
                                        <?php
                                        $arrProduct[$i]['stt'] = $i+1;
                                        $arrProduct[$i]['name'] = $product->info->name;
                                        $arrProduct[$i]['quantity'] = $product->quantity;
                                        $arrProduct[$i]['sale_price'] = $product->sale_price;
                                        $i++;
                                        ?>
                                        <?php $totalAmountAll +=  $product->quantity * $product->sale_price; ?>
                                    @endforeach
                                    <?php 
                                    usort($arrProduct, function ($a, $b) {
                                        return strcmp($a['name'], $b['name']); // So sánh theo tên (tăng dần)
                                    });
                                    $i=1;
                                    ?>
                                    @foreach ($arrProduct as $items)
                                        <tr>
                                            <td>
                                                @if(!$items['sale_price'] || !intval($items['quantity']))
                                                <s><?php echo $i; ?></s>
                                                @else
                                                <?php echo $i; ?>
                                                @endif
                                            </td>
                                            <td>
                                                @if(!$items['sale_price'] || !intval($items['quantity']))
                                                    <s><?php echo $items['name']; ?></s>
                                                @else
                                                    <?php echo $items['name']; ?>
                                                @endif
                                            </td>
                                            {{--<td>--}}
                                            {{--{{$product->info->sku}}--}}
                                            {{--</td>--}}
                                            <td style="width: 20px;background-color: gray;">
                                                @if(!$items['sale_price'] || !intval($items['quantity']))
                                                <s><?php echo $items['quantity']; ?></s>
                                                @else
                                                <?php echo $items['quantity']; ?>
                                                @endif
                                            </td>
                                            <td>
                                                @if(!$items['sale_price'] || !intval($items['quantity']))
                                                <s><?php echo $items['sale_price']; ?></s>
                                                @else
                                                <?php echo $items['sale_price']; ?>
                                                @endif
                                            </td>
                                            <!--<td>
                                                {{ number_format($product->price - $product->sale_price) }}
                                            </td>-->
                                            <td>
                                                @if(!$items['sale_price'] || !intval($items['quantity']))
                                                    <s><?php echo number_format($items['quantity'] * $items['sale_price']);$i++; ?></s>
                                                @else
                                                    <?php echo number_format($items['quantity'] * $items['sale_price']);$i++; ?>
                                                @endif
                                                
                                            </td>
                                        </tr>
                                    @endforeach
                                    
                                    @if($row->ord_discount_admin)
                                        <tr>
                                            <td style="text-align: right" colspan="4">Tổng tiền đơn hàng</td>
                                            <td><span style="color: red;">{{ number_format($totalAmountAll) }} VND</span></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: right" colspan="4">Khuyến mại từ vuaduoc (Trừ thẳng vào tổng giá trị đơn hàng):</td>
                                            <td>{{ number_format($row->ord_discount_admin) }} VND</td>
                                        </tr>
                                    @endif
                                    
                                    @if($row->ord_vat)
                                        <tr>
                                            <td style="text-align: right" colspan="4">VAT(10% trong tổng đơn hàng)</td>
                                            <td><span style="color: red;">{{ number_format($totalAmountAll*0.1) }} VND</span></td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td style="text-align: right" colspan="4">Tổng tiền Thanh toán</td>
                                        <td><span style="color: red;">{{ number_format($row->amount) }} VND</span></td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
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
                                    <td>Tổng số sản phẩm:</td>
                                    <td>{{ $total }}</td>
                                </tr>
                                <tr>
                                    <td style="color: red;">Trạng thái nhặt:</td>
                                    <td>

                                        <select id="{{$row->id}}" current_value="{{$row->status_code}}"
                                                class="change_status_order change_status_order_{{$row->id}}"
                                                name="change_status_order">
                                            @foreach($array_status_hapu as $key => $status)
                                                @if($key != $row->status_process)
                                                    <option value="{{$key}} ">{{$status}}</option>
                                                @else
                                                    <option disabled value="{{$key}} "
                                                            selected="selected">{{$status}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                @if($row->status_code == \App\Models\Order::NEW)
                                    <tr>
                                        <td>Nhân viên bán hàng: </td>
                                        <td>
                                            <select id="user_admin_{{$row->id}}" current_value="{{$row->status_code}}"
                                                    class="change_status_order"
                                                    name="ord_admin_user_id">
                                                @foreach($adminUser as $admin)
                                                    @if($admin->adm_id != $row->admin_user_id)
                                                        <option value="{{$admin->adm_id}}">{{$admin->adm_loginname}}</option>
                                                    @else
                                                        <option disabled value="{{$admin->adm_id}}"
                                                                selected="selected">{{$admin->adm_loginname}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Nhân viên nhặt thuốc: </td>
                                        <td>
                                            <select id="ord_admin_userprice_{{$row->id}}" current_value="{{$row->status_code}}"
                                                    class="change_status_order"
                                                    name="ord_admin_userprice_id">
                                                <option value="0">Chọn user Nhặt</option>
                                                @foreach($adminUserPrice as $admin)
                                                    @if($admin->adm_id != $row->ord_admin_userprice_id)
                                                        <option value="{{$admin->adm_id}}">{{$admin->adm_loginname}}</option>
                                                    @else
                                                        <option disabled value="{{$admin->adm_id}}"
                                                                selected="selected">{{$admin->adm_loginname}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Nhà xe: </td>
                                        <td>
                                            <input class="form-control" type="text" name="shipping_car"
                                               id="shipping_car_<?=$row->id?>"
                                               placeholder="Nhà xe" value="<?=$row->ord_shipping_car?>"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Giờ khởi hành: </td>
                                        <td>
                                            <input class="form-control" type="text" name="shipping_car_start"
                                               id="shipping_car_start"
                                               placeholder="Giờ khởi hành" value="<?=$row->ord_shipping_car_start?>"/>
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td colspan="2">
                                        <!--<input type="submit" value="Thay đổi thông tin">-->
                                        <input type="reset" value="Tải lại" onclick="reload_modal({{$row->id}})">
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>

                    <div class="col-md-12">
                        <div class="table-responsive">
                            
                            <form action="change_order_price.php?ord_id={{$row->id}}&status=NEW" id="order_price_{{$row->id}}"
                              class="ship_info table-responsive" method="post">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>STT</th>
                                        <th>Sản phẩm</th>
                                        {{--<td>SKU</td>--}}
                                        <th>Số lượng</th>
                                        <th>Giá Bán</th>
                                        <th>Giá Nhập</th>
                                        <!--<th>Chiết khấu</th>-->
                                        <th>
                                            Chênh lệch
                                        </th>
                                        <th>
                                            Ghi chú
                                        </th>
                                        <th>
                                            Giá Min
                                        </th>
                                        <th>
                                            Quầy
                                        </th>
                                        <th>
                                            Giá Max
                                        </th>
                                        <th>
                                            Quầy
                                        </th>
                                    </tr>
                                        <?php 
                                        $arrProduct = [];
                                        ?>
                                        @foreach ($row->products as $product)
                                            <?php
                                            $arrProduct[$i]['stt'] = $i+1;
                                            $arrProduct[$i]['name'] = $product->info->name;
                                            $arrProduct[$i]['product_id'] = $product->info->id;
                                            $arrProduct[$i]['id'] = $product->id;
                                            $arrProduct[$i]['quantity'] = $product->quantity;
                                            $arrProduct[$i]['sale_price'] = $product->sale_price;
                                            $arrProduct[$i]['price_hapu'] = $product->price_hapu;
                                            $arrProduct[$i]['price_chenh_hapi'] = $product->sale_price - $product->price_hapu;
                                            $arrProduct[$i]['note_hapu'] = $product->orp_note_hapu;
                                            $arrProduct[$i]['min_price_hapu'] = $product->productHapu->pro_ha_price_min;
                                            $arrProduct[$i]['min_pro_ha_pharmacy'] = $product->productHapu->pro_ha_pharmacy_min;
                                            $arrProduct[$i]['max_price_hapu'] = $product->productHapu->pro_ha_price_max;
                                            $arrProduct[$i]['max_pro_ha_pharmacy'] = $product->productHapu->pro_ha_pharmacy_max;
                                            $i++;
                                            //$max_price_hapu = $product->maxProductHapu->orp_price_hapu;
                                            //var_dump($min_price_hapu);
                                            //var_dump($max_price_hapu);
                                            ?>
                                            <?php $totalAmountAll +=  $product->quantity * $product->sale_price; 
                                            $totalAmountAll_hapu +=  $product->quantity * $product->price_hapu;
                                            ?>
                                        @endforeach
                                        <?php 
                                        usort($arrProduct, function ($a, $b) {
                                            return strcmp($a['name'], $b['name']); // So sánh theo tên (tăng dần)
                                        });
                                        $i=1;
                                        ?>
                                        @foreach ($arrProduct as $items)
                                            <tr>
                                                <td>
                                                    <?php echo $i; ?>
                                                </td>
                                                <td>
                                                    <span style="<?php if($items['price_chenh_hapi'] <= 0){ echo "color: red;"; } ?>"><?php echo $items['name']; ?></span>
                                                </td>
                                                {{--<td>--}}
                                                {{--{{$product->info->sku}}--}}
                                                {{--</td>--}}
                                                <td style="width: 20px">
                                                    <?php echo $items['quantity']; ?>
                                                </td>
                                                <td>
                                                    <?php echo number_format($items['sale_price']); ?>
                                                </td>
                                                <td>
                                                    <span style="<?php if($items['price_hapu'] == 0 && $items['quantity'] > 0){ echo "color: red;"; } ?>">
                                                        <?php echo number_format($items['price_hapu']); ?>
                                                    </span>
                                                </td>
                                                <td id="ord_price_product_total<?php echo $items['product_id']; ?>">
                                                    <span style="<?php if($items['price_chenh_hapi'] <= 0){ echo "color: red;"; } ?>">
                                                    <?php echo number_format($items['price_chenh_hapi']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php echo $items['note_hapu']; ?>
                                                </td>
                                                <td>
                                                    <?php echo number_format($items['min_price_hapu']); ?>
                                                </td>
                                                <td>
                                                    <?php echo $items['min_pro_ha_pharmacy']; ?>
                                                </td>
                                                <td>
                                                    <?php echo number_format($items['max_price_hapu']); ?>
                                                </td>
                                                <td>
                                                    <?php echo $items['max_pro_ha_pharmacy']; ?>
                                                </td>
                                            </tr>
                                            <?php 
                                            $i++;
                                            ?>
                                        @endforeach
                                        
                                        <tr>
                                            <td style="text-align: right" colspan="3">Tổng tiền</td>
                                            <td><span style="color: red;">{{ number_format($row->amount) }}</span></td>
                                            <td><span style="color: red;">{{ number_format($totalAmountAll_hapu) }}</span></td>
                                            <td><span style="color: red;">{{ number_format($row->amount - $totalAmountAll_hapu) }}</span></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: right" colspan="3">Tổng ship:</td>
                                            <td>{{number_format($row->ord_shipping_fee_car)}}</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: right" colspan="3">Lỗ/lãi:</td>
                                            <td><span style="color: red;">{{number_format((($row->amount - $totalAmountAll_hapu - $row->ord_shipping_fee_car)/$row->amount)*100,2)}}%</span></td>
                                        </tr>
                                        @if($row->status_code == \App\Models\Order::NEW)
                                        <tr>
                                            <td colspan="4">
                                                <!--<input type="submit" value="Cập nhật giá đơn hàng">-->
                                                <!--<button class="btn btn-default" type="submit" value="Submit">Cập nhật giá đơn hàng</button>
                                                <a class="btn btn-default" href="add_product.php?status=NEW&ord_id={{$row->id}}" >Thêm mới sản phẩm</a>-->
                                                <a class="btn btn-default" href="javascript:void(null)" onclick="exportProductOrder({{$row->id}},1)" >In sản phẩm excel</a>
                                                <a class="btn btn-default" href="javascript:void(null)" onclick="split_two_order({{$row->id}})" >Tách Đơn</a>
                                            </td>
                                        </tr>
                                        @endif
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="dialog_note({{$row->id}})">
                    Ghi chú
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
                <h5 class="modal-title" id="exampleModalLabel">Xác nhận thay đổi trạng thái nhặt thuốc</h5>
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
                <button type="button" class="btn btn-secondary" onclick="change_order_status_hapu(<?=$row->id?>)">Xác nhận
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
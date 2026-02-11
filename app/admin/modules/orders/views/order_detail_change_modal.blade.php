<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#contact_detail_{{$row->id}}">
    Xem chi tiết
</button>


<!-- Modal -->
<div class="modal fade" id="contact_detail_{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document" style="width: 900px;">
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
                                <td>Số điện thoại:</td>
                                <td>
                                    {{ $row->ship_phone }}
                                </td>
                            </tr>
                            <tr>
                                <td>Địa chỉ:</td>
                                <td>
                                    {{$row->ship_address}}
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
                                                <?php echo number_format($items['sale_price']); ?>
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
                                    <td>Số điện thoại:</td>
                                    <td>
                                        <input class="form-control" name="ord_ship_phone" id="ord_ship_phone"
                                               value="{{ $row->ship_phone }}">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Địa chỉ:</td>
                                    <td>
                                        <input class="form-control" name="ord_ship_address" id="ord_ship_address"
                                               value="{{$row->ship_address}}">
                                    </td>
                                </tr>
                                <!--<tr>
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
                                </tr>-->
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
                                    <td style="color:red;">Thu hộ tiền mặt:</td>
                                        <td>
                                        @if($row->status_code == \App\Models\Order::NEW)
                                            <select style="color: red;" id="change_order_shipping_car_cod{{$row->id}}" class="change_order_shipping_car_cod"
                                                    current-value="{{$row->ord_shipping_car_cod}}" name="change_shipping_car_cod">
                                                @foreach(\App\Models\Order::shippingCarStatus() as $status => $label)
                                                    <option {{ $row->ord_shipping_car_cod == $status ? 'selected': '' }} value="{{$status}}">
                                                        {{$label}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            {{ \App\Models\Order::shippingCarStatus()[$row->ord_shipping_car_cod] }}
                                        @endif
                                    </td>
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
                                        <td>Trưởng nhóm: </td>
                                        <td>
                                            <select id="ord_admin_group_user_id_{{$row->id}}" current_value="{{$row->status_code}}"
                                                    class="change_status_order"
                                                    name="ord_admin_group_user_id">
                                                <option value="0">Chọn trưởng nhóm</option>
                                                @foreach($adminUser as $admin)
                                                    @if($admin->adm_id != $row->ord_admin_group_user_id)
                                                        <option value="{{$admin->adm_id}}">{{$admin->adm_loginname}}</option>
                                                    @else
                                                        <option disabled value="{{$admin->adm_id}}"
                                                                selected="selected">{{$admin->adm_loginname}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    <?php 
                                    $flagUpdateInfoCar = false;
                                    if($row->ord_shipping_car == "" || $row->ord_shipping_car_start == ""){
                                        $flagUpdateInfoCar = true;
                                    }
                                    if($orderByPhone){
                                        $row->ord_shipping_car = $row->ord_shipping_car == "" ? $orderByPhone->ord_shipping_car : $row->ord_shipping_car;
                                        $row->ord_shipping_car_start = $row->ord_shipping_car_start == "" ? $orderByPhone->ord_shipping_car_start : $row->ord_shipping_car_start;
                                        $row->ord_shipping_fee_car = $row->ord_shipping_fee_car == 0 ? $orderByPhone->ord_shipping_fee_car : $row->ord_shipping_fee_car;
                                        $row->ord_shipping_car_phone = $row->ord_shipping_car_phone == 0 ? $orderByPhone->ord_shipping_car_phone : $row->ord_shipping_car_phone;
                                        $noteInfoCar = "";
                                    }
                                    ?>
                                    <tr>
                                        <td>Nhà xe: </td>
                                        <td>
                                            <input class="form-control" type="text" name="shipping_car"
                                               id="shipping_car_<?=$row->id?>"
                                               placeholder="Nhà xe" value="<?=$row->ord_shipping_car?>"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Số ĐT nhà xe: </td>
                                        <td>
                                            <input value="<?=$row->ord_shipping_car_phone; ?>" class="form-control" type="text" name="shipping_car_phone"
                                                id="shipping_car_phone_<?=$row->id?>"
                                                placeholder="Số ĐT nhà xe"/>
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
                                    <tr>
                                        <td>Phí ship: </td>
                                        <td>
                                            <input class="form-control" type="text" name="ord_shipping_fee_car"
                                               id="ord_shipping_fee_car"
                                               placeholder="Phí ship" value="<?= number_format($row->ord_shipping_fee_car)?>"/>
                                        </td>
                                    </tr>
                                    <?php 
                                    if($flagUpdateInfoCar){
                                    ?>
                                        <tr>
                                            <td colspan="2" style="color: red;">Click button [Thay đổi thông tin] để lưu lại thông tin nhà xe và tiền ship</td>
                                        </tr>
                                    <?php 
                                    }?>
                                @endif
                                <tr>
                                    <td colspan="2">
                                        <input type="submit" value="Thay đổi thông tin">
                                        <input type="reset" value="Tải lại" onclick="reload_modal({{$row->id}})">
                                    </td>
                                </tr>
                            </table>
                        </form>
                        <form action="mapping_order.php?ord_id={{$row->id}}&status=<?=($status ?? 'NEW')?>" id="mapping_order_{{$row->id}}"
                              class="ship_info table-responsive" method="post">
                            <table class="table table-striped">
                                <tr>
                                    <td><input class="form-control" type="text" name="ord_code_mapping"
                                               id="ord_code_mapping<?=$row->id?>"
                                               placeholder="Mã đơn hàng" value=""/></td>
                                    <td>
                                        <input type="submit" value="Gộp Đơn">
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
                                        <th>Giá</th>
                                        <!--<th>Chiết khấu</th>-->
                                        <th>
                                            Tổng tiền thanh toán
                                        </th>
                                        <th>Ghi chú</th>
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
                                            $arrProduct[$i]['orp_note_hapu'] = $product->orp_note_hapu;
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
                                                    <?php echo $i; ?>
                                                </td>
                                                <td>
                                                    <?php echo $items['name']; ?>
                                                </td>
                                                {{--<td>--}}
                                                {{--{{$product->info->sku}}--}}
                                                {{--</td>--}}
                                                <td style="width: 20px">
                                                    <input style="width: 50px;text-align: center;" onchange="loadPriceProductByQuality(this,{{$row->id}})" class="form-control" name="ord_quantity<?php echo $items['product_id']; ?>" id="ord_quantity<?php echo $items['product_id']; ?>"
                                                   value="<?php echo $items['quantity']; ?>">
                                                </td>
                                                <td>
                                                    <input style="width: 90px;text-align: center;" onchange="updatePriceProduct(this,{{$row->id}})" oninput="loadInputValueformatCurrency(this,<?php echo $items['sale_price']; ?>)" class="form-control" name="ord_price<?php echo $items['product_id']; ?>" id="ord_price<?php echo $items['product_id']; ?>"
                                                   value="<?php echo number_format($items['sale_price']); ?>">
                                                </td>
                                                <!--<td>
                                                    {{ number_format($product->price - $product->sale_price) }}
                                                </td>-->
                                                <td id="ord_price_product_total<?php echo $items['product_id']; ?>">
                                                    <?php echo number_format($items['quantity'] * $items['sale_price']); ?>
                                                </td>
                                                <td>
                                                    <?php echo $items['orp_note_hapu']; ?>
                                                </td>
                                                <td style="width: 20px">
                                                    <a href="javascript:void(null);" onclick="delete_product_order({{$row->id}},<?php echo $items['id']; ?>)">Xóa</button>
                                                </td>
                                            </tr>
                                            <?php 
                                            $i++;
                                            ?>
                                        @endforeach
                                        <tr>
                                            <td style="text-align: right" colspan="4">VAT(10% trong tổng đơn hàng)</td>
                                            <td>
                                                <input type="checkbox" value="1" name="ord_vat" id="ord_vat" <?php echo $row->ord_vat ? 'checked' : ''; ?> />
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td style="text-align: right" colspan="4">Khuyến mại từ vuaduoc (Trừ thẳng vào giá trị đơn hàng)</td>
                                            <td>
                                                <input style="width: 90px;text-align: center;" oninput="loadInputValueformatCurrency(this,{{ $row->ord_discount_admin }})" class="form-control" name="ord_discount_admin" id="ord_discount_admin"
                                                   value="{{ number_format($row->ord_discount_admin)}}">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: right" colspan="4">Tổng tiền</td>
                                            <td><span style="color: red;">{{ number_format($row->amount) }} VND</span></td>
                                        </tr>
                                        @if($row->status_code == \App\Models\Order::NEW)
                                        <tr>
                                            <td colspan="4">
                                                <!--<input type="submit" value="Cập nhật giá đơn hàng">-->
                                                <button class="btn btn-default" type="submit" value="Submit">Cập nhật giá đơn hàng</button>
                                                <a class="btn btn-default" href="add_product.php?status=NEW&ord_id={{$row->id}}" >Thêm mới sản phẩm</a>
                                                <a class="btn btn-default" href="javascript:void(null)" onclick="exportProductOrder({{$row->id}},0)" >In sản phẩm excel</a>
                                            </td>
                                        </tr>
                                        @endif
                                </table>
                            </form>
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
                        <div class="table-responsive" style>
                            <table class="table table-bordered" style="table-layout: fixed; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Thời gian</th>
                                        <th>Trạng thái đơn hàng</th>
                                        <th>Trạng thái thanh toán</th>
                                        <th>Người xử lý</th>
                                        <th>Ghi chú</th>
                                    </tr>
                                </thead>
                                <tbody style="display: block; max-height: 250px; overflow-y: auto; width: 668px;">
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
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="renew({{$row->id}}, {{$row->user->id}})">
                    Xóa và tạo lại đơn
                </button>

                <button class="btn btn-secondary" onclick="dialog_note({{$row->id}})">
                    Ghi chú
                </button>
                <button class="btn btn-secondary" onclick="printDivId('print_content_send_partner_{{$row->id}}',{{$row->id}})">
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
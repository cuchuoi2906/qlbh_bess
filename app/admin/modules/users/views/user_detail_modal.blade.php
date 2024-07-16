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
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped">
                            <tr>
                                <td>Họ Tên:</td>
                                <td>{{ $row->name }}</td>
                            </tr>
                            <tr>
                                <td>Số điện thoại:</td>
                                <td>{{ $row->phone }}</td>
                            </tr>
                            <tr>
                                <td>Email:</td>
                                <td>{{ $row->email }}</td>
                            </tr>
                            <tr>
                                <td>Sản phẩm tìm kiếm:</td>
                                <td>{{ $row->content }}</td>
                            </tr>
                            <!--<tr>
                                <td>Ví nạp:</td>
                                <td>{{ $row->wallet ? number_format($row->wallet->charge) : 0 . ' đ'}}</td>
                            </tr>
                            <tr>
                                <td>Ví Hoa Hồng:</td>
                                <td>{{ $row->wallet ? number_format($row->wallet->commission) : 0 . ' đ'}}</td>
                            </tr>
                            <tr>
                                <td>Người giới thiệu:</td>
                                <td>{{ $row->parent ? ($row->parent->name . ' (ID: '.$row->parent->id.')') : ''}}</td>
                            </tr>
                            <tr>
                                <td>Point lên level:</td>
                                <td>{{ number_format($row->getTotalCommissionForUpLevel()) }}</td>
                            </tr>
                            <tr>
                                <td>Trạng thái:</td>
                                <td>
                                    <?php
                                    if ($row->use_active == 1){
                                        echo 'Đã kích hoạt';
                                    }elseif ($row->use_active== -99){
                                        echo 'Đã xóa';
                                    }else{
                                       echo 'Chưa kích hoạt';
                                    }
                                    ?>
                                </td>
                            </tr>-->

                        </table>
                    </div>

                    <!--<div class="col-md-12">
                        <h3>Cấp duới</h3>
                        <table class="table table-bordered">
                            <tr>
                                <td>ID</td>
                                {{--<td>SKU</td>--}}
                                <td>Tên</td>
                                <td>Email</td>
                                <td>Sđt</td>
                                <td>Level</td>
                                <td>Trạng thái</td>
                                <td>Đã hủy</td>
                            </tr>
                            @foreach ($row->childs as $child)
                                <?php
                                $total_active = $total_active ?? 0;
                                $total_unactive = $total_unactive ?? 0;
                                $total_deleted = $total_deleted ?? 0;
                                if ($child->active) {
                                    $total_active++;
                                } else {
                                    $total_unactive++;
                                }
                                if ($child->deleted_at) {
                                    $total_deleted++;
                                }
                                ?>
                                <tr>
                                    <td>
                                        {{$child->id}}
                                    </td>
                                    <td>
                                        {{$child->name}}
                                    </td>
                                    <td>
                                        {{$child->email}}
                                    </td>
                                    <td>
                                        {{$child->phone}}
                                    </td>
                                    <td>
                                        {{$child->level}}
                                    </td>
                                    <td>
                                        {{$child->active?'Đã kích hoạt':'Chưa kích hoạt'}}
                                    </td>
                                    <td>
                                        {{$child->deleted_at?'Đã hủy':''}}
                                    </td>
                                </tr>

                            @endforeach
                            <tr>
                                <td colspan="6">
                                    Tổng số đã kích hoạt: {{$total_active ?? 0}}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6">
                                    Tổng số chưa kích hoạt: {{$total_unactive ?? 0}}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6">
                                    Tổng số đã hủy: {{$total_deleted ?? 0}}
                                </td>
                            </tr>
                        </table>
                    </div>-->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng lại</button>
            </div>
        </div>
    </div>
</div>
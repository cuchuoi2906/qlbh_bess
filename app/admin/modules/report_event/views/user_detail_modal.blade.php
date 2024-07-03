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

                        </table>
                    </div>

                    <div class="col-md-12">
                        <h3>Cấp duới</h3>
                        <table class="table table-bordered">
                            <tr>
                                <td>ID</td>
                                {{--<td>SKU</td>--}}
                                <td>Tên</td>
                                <td>Email</td>
                                <td>Sđt</td>
                                <td>Level</td>
                            </tr>
                            @foreach ($row->childs as $child)
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
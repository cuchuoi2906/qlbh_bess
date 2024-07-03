@extends('module-master')

@section('content')
    <?php if($fs_errorMsg): ?>
    <div class="row">
        <div class="col-xs-12">
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> Có lỗi!</h4>
                <?php $form = new form(); ?>
                {!! $form->errorMsg($fs_errorMsg) !!}
            </div>
        </div>
    </div>
    <?php endif ?>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>Số lượng</th>
            <th width="50%">Chiết khấu</th>
            <th>Xóa</th>
        </tr>
        </thead>
        <tbody>
        {{--<tr>--}}
        {{--<td>1</td>--}}
        {{--<td>{{number_format($product->price)}}đ</td>--}}
        {{--<td></td>--}}
        {{--</tr>--}}
        @foreach($product->pricePolicies ?? [] as $policy)
            <form method="post" action="price_policy.php?product_id={{$product->id}}">
                <tr>
                    <td>
                        <input name="quantity" type="number" required value="{{$policy->quantity}}">

                    </td>
                    <td>
                        <input style="width: 100%" name="price" min="0" type="number"
                               required value="{{$policy->price}}">
                    </td>
                    <td>
                        <input type="hidden" name="id" value="{{$policy->id}}">
                        <input type="hidden" name="action" value="edit">
                        <button>Sửa</button>
                        <a onclick="return confirm('Bạn có chắc chắn ko?')" href="delete_price_policy.php?id={{$policy->id}}">Xoá</a>
                    </td>
                </tr>
            </form>
        @endforeach
        <tr>
            <form method="post" action="price_policy.php?product_id={{$product->id}}">
                <td>
                    <input name="quantity_add" type="number" required>
                </td>
                <td>
                    <input style="width: 100%;" name="price_add" min="0" type="number"
                           required>
                </td>
                <td>
                    <input type="hidden" name="action" value="add">
                    <button>Thêm</button>
                </td>
            </form>
        </tr>
        </tbody>
    </table>
@stop
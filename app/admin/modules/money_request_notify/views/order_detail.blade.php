<table>
    <tr>
        <td>Mã đơn hàng:</td>
        <td>{{$row->order->code}}</td>
    </tr>
    <tr>
        <td>Tổng tiền:</td>
        <td>{{$row->order->amount}}</td>
    </tr>
    <tr>
        <td>Người đặt:</td>
        <td>{{$row->order->user->email}} <br/>{{$row->order->user->phone}}</td>
    </tr>
    <tr>
        <td>Trạng thái đơn:</td>
        <td>{{\App\Models\Order::$status[$row->order->status_code]}}</td>
    </tr>
</table>
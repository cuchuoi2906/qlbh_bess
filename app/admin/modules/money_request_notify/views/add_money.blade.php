<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_money_{{$row->id}}">
    Cộng tiền
</button>


<!-- Modal -->
<div class="modal fade" id="add_money_{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
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
                        <iframe style="border: none; width: 100%; height: 600px"
                                src="../money/add_money.php?record_id={{$row->user->id}}&money={{$row->money}}&request_id={{$row->id}}"></iframe>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng lại</button>
            </div>
        </div>
    </div>
</div>
<button type="button" class="btn btn-primary"
        onclick="add_iframe('<iframe class=\'full_iframe\' src=\'../report_money/history.php?&user_id=<?=$row->id?>&log_type=0\'></iframe>', '#money_body_<?=$row->id?>')"
        data-toggle="modal" data-target="#money_{{$row->id}}">
    Giao dịch
</button>

<!-- Modal -->
<div class="modal fade" id="survay_{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                    <div class="col-md-12" id="money_body_<?=$row->id?>">

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng lại</button>
            </div>
        </div>
    </div>
</div>
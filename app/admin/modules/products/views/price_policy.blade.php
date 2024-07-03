<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#price_policy_{{$row->id}}">
    Xem chi tiết
</button>

<!-- Modal -->
<div class="modal fade in" id="price_policy_{{$row->id}}" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel"
     style=""
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="height: 500px">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Chính sách chiết khấu hoa hồng trực tiếp</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <iframe src="price_policy.php?product_id={{$row->id}}"
                                style="border: none; width: 100%; height: 400px"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


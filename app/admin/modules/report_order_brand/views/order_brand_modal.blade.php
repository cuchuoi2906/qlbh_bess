<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#brand_detail_{{$row->id}}">
    Xem chi tiết
</button>

<!-- Modal -->
<div class="modal fade" id="brand_detail_{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
						<iframe src="order_brand_detail.php?userId={{$row->use_id}}&ord_created_at=<?php echo $range_date; ?>"
							style="border: none; width: 100%; height: 400px"></iframe>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="location.reload()" class="btn btn-secondary" data-dismiss="modal">
                    Đóng lại
                </button>
            </div>
        </div>
    </div>
</div>

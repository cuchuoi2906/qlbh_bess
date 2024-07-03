@extends('module-master')

@section('content')
<style>
.card-box{
    padding: 20px;
    border-radius: 5px;
    background-clip: padding-box;
    margin-bottom: 20px;
    background-color: #fff;
    border: 1px solid #e7e7e7;
    width: 40%;
}
</style>
    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Danh sách</h3>
            </div>
            <div class="card-box">
                <form id="admin_add_cat" method="post" enctype="multipart/form-data">
                    <input type="hidden" value="0" name="actionImport" id="actionImport" />
                    <div class="form-group">
                        <div class="form-group">
                            <label for=""><font class="form_asterisk">* </font>Tên file :</label>
                            <input class="form-control" type="text" placeholder="Tên" id="money_file_name" name="money_file_name" value="" style="width:px; height:px" maxlength="255">
                        </div>
                        <div class="text-center text-error" id="error-exist"></div>
                        <label for="category_name">Import File</label>
                        <div class="uploader">
                            <input type="file" id="fileImport" class="form-control" name="excelImport" placeholder="Select file" >
                        </div>
                    </div>
                    <div class="form-group m-b-0 m-t-30">
                        <span class="btn btn-primary" name="form_submit" value="submit" type="submit" onclick="updateImportFile();">Cập nhật</span>
                        <span style="float:right;" onclick="window.location.href='http://dev.3do.com.vn/admin/chinh_sach_gia_san_pham.xlsx'" class="btn btn-primary" name="form_submit" value="submit" type="submit">Tải file mẫu</span>
                    </div>
                </form>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        {!! $data_table !!}
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <div class="card-box">
                <span onclick="load_iframe_addmoney();" type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_money_1">
                    Thay đổi chính sách giá
                </span>
                <span onclick="btn_destroy_onclick()" id="destroy-money" class="btn btn-primary" name="form_submit">Từ chối</span>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">

            </div>
        </div>
    </section>
    <div class="modal fade" id="add_money_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
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
                            <iframe id="iframe_add_money" style="border: none; width: 100%; height: 600px"
                                    src=""></iframe>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng lại</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('header')
    <style>
        .edit_link {
            display: block;
        }
    </style>
@stop
@section('script')
    <script>
        function updateImportFile(){
            var file = $('#fileImport')[0].files[0];
            if(typeof(file) ==='undefined'){
                alert('Bạn chưa chọn file import');
                return;   
            }
            var nameValue = $('#money_file_name').val();
            if(nameValue == ''){
                alert('Bạn phải nhập tên file import');
                return;
            }
            $('#actionImport').val(1);
            document.getElementById("admin_add_cat").submit();
        }
        function load_iframe_addmoney(){
            var arr_id = [];
            var j =0;
            var checkboxAll = document.getElementsByClassName('checkboxAll');
            var v_record_count = checkboxAll.length;
            for(var i = 0; i < v_record_count; i++){
                var p_check_obj = checkboxAll[i];
                if(p_check_obj.checked){
                    arr_id[j] = p_check_obj.value;
                    j++;
                }
            }
            if(arr_id.length==0){
                alert('Chưa có đối tượng nào được chọn!');
                $('#add_money_1').modal({backdrop: 'static', keyboard: false});
                return;
            }
            var myJsonString = JSON.stringify(arr_id);
            var formData = {
                idArr: myJsonString
            };
            //$('#add_money_1').modal().hide();
            var domain = window.location.origin;
            jQuery.ajax({
                type: "POST",
                url: domain+"/admin/modules/money_load_batch/validate.php",
                data: formData,
                dataType: "json",
                encode: true,
            }).done(function (data) {
                if(data.suscess == 0){
                    alert(data.message);
                    $('#add_money_1').modal('hide');
                    return;
                }
            });
            var urliframe = '../money/add_money_batch.php?money_batch_id='+arr_id.toString();
            document.getElementById('iframe_add_money').src=urliframe;
            //$('#add_money_1').modal().show();
        }
         function check_all(chk_object){
            var v_is_checked = chk_object.checked;
            var checkboxAll = document.getElementsByClassName('checkboxAll');
            var v_record_count = checkboxAll.length;
            for(var i = 0; i < v_record_count; i++){
                var p_check_obj = checkboxAll[i];
                if(p_check_obj){
                    p_check_obj.checked = v_is_checked;
                }
            }
        }
        function btn_destroy_onclick(){
            var checkboxAll = document.getElementsByClassName('checkboxAll');
            var v_record_count = checkboxAll.length;
            var arr_id = [];
            var j =0;
            $('#actionImport').val(0);
            for(var i = 0; i < v_record_count; i++){
                var p_check_obj = checkboxAll[i];
                if(p_check_obj.checked){
                    arr_id[j] = p_check_obj.value;
                    j++;
                }
            }
            if(arr_id.length==0){
                alert('Chưa có đối tượng nào được chọn!');
                return;
            }
            if(confirm("Bạn có chắc chắn muốn từ chối nạp tiền!")) {
                var myJsonString = JSON.stringify(arr_id);
                var formData = {
                    idArr: myJsonString
                };
                var domain = window.location.origin;
                jQuery.ajax({
                    type: "POST",
                    url: domain+"/admin/modules/products/change_status.php",
                    data: formData,
                    dataType: "json",
                    encode: true,
                }).done(function (data) {
                    alert(data.message);
                    if(data.suscess == 1){
                        var timest = new Date().valueOf();
                        var url = '../products/import.php?'+timest;
                        window.location.href=url;
                    }
                    return;
                });
            }else{
                for(var i = 0; i < v_record_count; i++){
                    var p_check_obj = checkboxAll[i];
                    if(p_check_obj){
                        p_check_obj.checked = false;
                    }
                }
            }
        }
        
    </script>
@stop
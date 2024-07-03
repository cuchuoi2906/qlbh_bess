@extends('module-master')

@section('content')
    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Danh sách</h3>
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
            <!-- /.box-body -->
            <div class="box-footer">

            </div>
        </div>
    </section>
@stop

@section('header')

@stop

@section('script')
    <script>
        function payment_is_paid(id, note) {
            if (note.length <= 0) {
                alert('Bạn phải nhập ghi chú');
                return;
            }
            if (confirm('Bạn có chắc không?')) {
                $.ajax({
                    url: 'confirm.php',
                    type: 'POST',
                    data: {
                        id: id,
                        note: note
                    },
                    response: function (response) {
                        location.reload();
                    }
                });
            }
            location.reload();
        }

    </script>
@stop
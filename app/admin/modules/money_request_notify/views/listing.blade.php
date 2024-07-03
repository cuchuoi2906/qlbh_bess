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
    <style>
        .edit_link {
            display: block;
        }
    </style>
@stop

@section('script')
    <script>
        function change_status(ele) {
            $.ajax({
                url: 'change_status.php',
                type: 'POST',
                data: {
                    id: $(ele).attr('request-id'),
                    status: $(ele).val()
                },
                success: function (response) {
                    $.toast({
                        // heading: '',
                        text: 'Thay đổi trạng thái thành công',
                        position: 'bottom-right',
                        stack: false,
                        icon: 'success'
                    });
                    location.reload();
                }
            });

        }
    </script>
@stop
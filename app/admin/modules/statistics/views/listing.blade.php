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
        $('select.select2').on('change',function () {
            var _input =  $(this).attr('id');
            var _inputa =  $(this).attr('selected','selected');
            console.log(_inputa);
            alert(_inputa);

            // $.ajax({
            //     type: 'POST',
            //     url: 'active.php',
            //     data: {
            //         id:  _input.substring(9),
            //     },
            //     success: function (response) {
            //         console.log('ok');
            //     }
            // });

        });


        function select_change_ord_status_code(id, value) {

            alert(id + '  ' + value);
        }
        function read_mark(id) {
            $.ajax({
                type: 'POST',
                url: 'read_mark.php',
                cache: false,
                data: {
                    id: id
                },
                success: function (response) {
                    location.reload();
                }
            });
        }
    </script>
@stop
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
            $('input.active').on('change',function () {
                var _input =  $(this).attr('id');
                var _inputa =  $(this);

                $.ajax({
                    type: 'POST',
                    url: 'active.php',
                    data: {
                        id:  _input.substring(9),
                    },
                    success: function (response) {
                        console.log('ok');
                    }
                });

            });
        </script>
@stop
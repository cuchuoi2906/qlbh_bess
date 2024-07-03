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

        $('.use_level').change(function () {
            var level = $(this).val();
            var user_id = $(this).attr('data-row-id');
            if (confirm('Bạn có chắc chắn thay đổi cấp độ của người dùng này không?')) {
                $.ajax({
                    type: 'POST',
                    url: 'change_level.php',
                    dataType: 'json',
                    data: {
                        user_id: user_id,
                        level: level
                    },
                    success: function (ressponse) {
                        console.log(ressponse);
                        if (parseInt(ressponse.error) == 1) {
                            alert(ressponse.message);
                        } else {
                            location.reload();
                        }
                    }

                });
            }
        });

    </script>
@stop
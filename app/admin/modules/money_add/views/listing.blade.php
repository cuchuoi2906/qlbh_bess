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
    .edit_link{
        display: block;
    }
</style>
@stop

@section('script')
    <script>
        <?php if($is_admin): ?>
        function select_change_pro_category_id(id, value) {
            $.ajax({
                url: 'change_category.php',
                type: 'POST',
                data: {
                    product_id: id,
                    category_id: value
                },
                success: function(response)
                {

                }
            });

        }
        <?php
        endif;
        ?>

    </script>
@stop
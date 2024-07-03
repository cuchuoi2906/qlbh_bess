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
                    <div class="col-md-12" id="sort-able">
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
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
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
                success: function (response) {

                }
            });

        }

        <?php
        endif;
        ?>

        function add_policy() {
            alert('a');
        }


        $(function () {
            $("#sort-able table tbody").sortable({
                placeholder: "ui-state-highlight",
                stop: function (event, ui) {

                    var index = ui.item.index();
                    console.log(index);
                    var current_id = $('#sort-able table tbody tr:nth-child(' + (index + 1) + ')').attr('row-id');

                    console.log(current_id);

                    var before_id = 0;
                    var after_id = 0;
                    before = index;
                    if ($('#sort-able table tbody tr:nth-child(' + before + ')').length > 0) {
                        before_id = $('#sort-able table tbody tr:nth-child(' + before + ')').attr('row-id');
                    }
                    var after = index + 2;
                    if ($('#sort-able table tbody tr:nth-child(' + after + ')').length > 0) {
                        after_id = $('#sort-able table tbody tr:nth-child(' + after + ')').attr('row-id');
                    }

                    $.ajax({
                        url: 'update_order.php',
                        type: 'POST',
                        data: {
                            id: current_id,
                            before_id: before_id,
                            after_id: after_id

                        },
                        success: function (response) {
                            console.log(response);
                        }
                    });

                }
            });
            $("#sort-able table tbody").disableSelection();
        });


    </script>
@stop
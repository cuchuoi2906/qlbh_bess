<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <?php
    echo admin_lte_header();
    ?>
    @yield('header')
    <style>
        td {
            vertical-align: middle !important;
        }
    </style>
</head>
<body style="background: #ecf0f5;">
@yield('content')
<?php
echo admin_lte_footer();
?>
@yield('script')
<script type="text/javascript">
    //$('.table-header-search select').select2();

    //Quick active
    $('input.quick-active').change(function () {
        var record_id = $(this).attr('record-id');
        var field = $(this).attr('field');

        $.ajax({
            url: 'active.php',
            method: 'POST',
            data: {
                id: record_id,
                field: field,
                value: $(this).is(':checked') ? 1 : 0
            },
            success: function (data) {
                toastr.success('Thay đổi trạng thái thành công!');
            },
            error: function (response) {
                toastr.error('Thay đổi trạng thái thất bại!');
            }
        });
    });


    function run_waitMe(el, num, effect) {
        text = 'Please wait...';
        fontSize = '';
        switch (num) {
            case 1:
                maxSize = '';
                textPos = 'vertical';
                break;
            case 2:
                text = '';
                maxSize = 30;
                textPos = 'vertical';
                break;
            case 3:
                maxSize = 30;
                textPos = 'horizontal';
                fontSize = '18px';
                break;
        }
        el.waitMe({
            effect: effect,
            text: text,
            bg: 'rgba(255,255,255,0.7)',
            color: '#000',
            maxSize: maxSize,
            waitTime: -1,
            source: 'img.svg',
            textPos: textPos,
            fontSize: fontSize,
            onClose: function (el) {
            }
        });
    }

    if (<?=count(locales())?> <= 1)
    {
        $('ul.nav.nav-tabs').hide();
    }

    function printDivId(elem)
    {
        var mywindow = window.open('', 'PRINT', 'height=400,width=600');
        let title = '';
        if(document.getElementById("title_print")){
            title = document.getElementById("title_print").value;
        }
        mywindow.document.write('<html><head><title>'+title+'</title>');
        mywindow.document.write(document.getElementsByTagName('head')[0].innerHTML);

        mywindow.document.write('</head><body >');
        //mywindow.document.write('<h1>' + document.title  + '</h1>');
        mywindow.document.write(document.getElementById(elem).innerHTML);
        mywindow.document.write('</body></html>');

        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10*/

        // mywindow.print();
        setTimeout(
            function(){
                mywindow.print();
                mywindow.close();
            },
            1000
        );
        return true;
    }

    //Clone scroll for table responsive
    $(function(){
        $('.table-responsive-clone-content').each(function(){
            $(this).width($(this).parent().next('.table-responsive').find('table').width());
        });
        
        $(".table-responsive-clone").scroll(function(){
            $(".table-responsive")
                .scrollLeft($(".table-responsive-clone").scrollLeft());
        });
        $(".table-responsive").scroll(function(){
            $(".table-responsive-clone")
                .scrollLeft($(".table-responsive").scrollLeft());
        });
    });

</script>
<style>
    .field-total {
        color: red;
        font-size: 16px;
        text-align: center;
        font-weight: bold;
    }

    .field-search a{
        color: white;
    }

    .is_deleted {
        /*background-color: #ff1600;*/
    }
</style>
</body>
</html>
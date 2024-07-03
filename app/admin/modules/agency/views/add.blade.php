@extends('module-master')

@section('content')
    <div class="container-fluid">
        <?php
        $form = new form();
        $form->create_form("add", $fs_action, "post", "multipart/form-data", 'onsubmit="validateForm(); return false;"');
        ?>
        <?php if($fs_errorMsg): ?>
        <div class="row">
            <div class="col-xs-12">
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-ban"></i> Có lỗi!</h4>
                    {!! $form->errorMsg($fs_errorMsg) !!}
                </div>
            </div>
        </div>
        <?php endif ?>

        <div class="row">
            <div class="col-xs-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Thông tin cơ bản</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">

                    <?= $form->text("Tên đại lý", "agc_name", "agc_name", $agc_name ?? '', "Tên đại lý", 1, "", "", 255, "", "", "") ?>
                    <?= $form->text("Số điện thoại", "agc_phone", "agc_phone", $agc_phone ?? '', "Số điện thoại", 1, "", "", 255, "", "", "") ?>
                    <?= $form->text("Website", "agc_website", "agc_website", $agc_website ?? '', "Website", 1, "", "", 255, "", "", "") ?>
                    <?= $form->text("Email", "agc_email", "agc_email", $agc_email ?? '', "Email", 1, "", "", 255, "", "", "") ?>
                    <?= $form->select("Chọn tỉnh/ thành phố", "agc_city_id", "agc_city_id", $cities, 5, "") ?>
                        <div class="load_district_select"></div>
                    <?= $form->text("Địa chỉ", "agc_address", "agc_address", $agc_address ?? '', "Địa chỉ", 1, "", "", 255, "", "", "") ?>
                    <!--                        --><?//= $form->text("Rewrite", "cat_rewrite", "cat_rewrite", $cat_rewrite, "Rewrite đường dẫn url", 0, "", "", "", "", "", "") ?>
                    <!--                        --><?//= $form->select("Trạng thái", "cat_show", "cat_show", $status_arr, $cat_show, "Trạng thái", 1) ?>
                    <!--                        --><?//= $form->checkbox("Active", 'cat_active', 'cat_active', 1, $cat_active, '') ?>
                    </div>
                </div>
            </div>

            <div class="col-xs-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Vị trí</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div id="maps_maparea">
                            <div id="maps_mapcanvas" style="margin-top:10px;" class="form-group"></div>

                            <div style="display: none">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon">L</span>
                                                <input type="text" class="form-control" name="maps[maps_mapcenterlat]"
                                                       id="maps_mapcenterlat" value="{maps_mapcenterlat}"
                                                       readonly="readonly">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon">N</span>
                                                <input type="text" class="form-control" name="maps[maps_mapcenterlng]"
                                                       id="maps_mapcenterlng" value="{maps_mapcenterlng}"
                                                       readonly="readonly">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon">L</span>
                                                <input type="text" class="form-control" name="maps[maps_maplat]"
                                                       id="maps_maplat" value="<?php echo $agc_latitude ?? ''; ?>"
                                                       readonly="readonly">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon">N</span>
                                                <input type="text" class="form-control" name="maps[maps_maplng]"
                                                       id="maps_maplng" value="<?php echo $agc_longitude ?? ''; ?>"
                                                       readonly="readonly">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon">Z</span>
                                                <input type="text" class="form-control" name="maps[maps_mapzoom]"
                                                       id="maps_mapzoom" value="{maps_mapzoom}" readonly="readonly">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?= $form->checkbox("Đại lý lớn?", 'agc_show', 'agc_show', 1, $agc_show, '') ?>
                    </div>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="">
                    <div class="" style="text-align: center;">
                        <?= $form->button("submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "Lưu lại" . $form->ec . "Làm lại", "Lưu lại" . $form->ec . "Làm lại", ""); ?>
                        <?= $form->hidden("action", "action", "execute", ""); ?>
                        <?= $form->hidden("valradio", "valradio", 0) ?>
                    </div>
                </div>
            </div>
        </div>
        <?
        $form->close_form();
        unset($form);
        ?>
    </div>
@stop

@section('script')
    <script>


        var map, ele, mapH, mapW, addEle, mapL, mapN, mapZ;

        ele = 'maps_mapcanvas';
        addEle = 'agc_address';
        mapLat = 'maps_maplat';
        mapLng = 'maps_maplng';
        mapZ = 'maps_mapzoom';
        mapArea = 'maps_maparea';
        mapCenLat = 'maps_mapcenterlat';
        mapCenLng = 'maps_mapcenterlng';

        // Call Google MAP API
        if (!document.getElementById('googleMapAPI')) {
            var s = document.createElement('script');
            s.type = 'text/javascript';
            s.id = 'googleMapAPI';
            s.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&callback=controlMap&key=<?=setting('google_map_api')?>';
            document.body.appendChild(s);
        } else {
            controlMap();
        }

        // Creat map and map tools
        function initializeMap() {
            var zoom = parseInt($("#" + mapZ).val()), lat = parseFloat($("#" + mapLat).val()),
                lng = parseFloat($("#" + mapLng).val()), Clat = parseFloat($("#" + mapCenLat).val()),
                Clng = parseFloat($("#" + mapCenLng).val());
            Clat || (Clat = 20.984516000000013, $("#" + mapCenLat).val(Clat));
            Clng || (Clng = 105.79547500000001, $("#" + mapCenLng).val(Clng));
            lat || (lat = Clat, $("#" + mapLat).val(lat));
            lng || (lng = Clng, $("#" + mapLng).val(lng));
            zoom || (zoom = 15, $("#" + mapZ).val(zoom));

            mapW = $('#' + ele).innerWidth();
            mapH = mapW * 3 / 6.5;

            // Init MAP
            $('#' + ele).width(mapW).height(mapH > 500 ? 500 : mapH);
            map = new google.maps.Map(document.getElementById(ele), {
                zoom: zoom,
                center: {
                    lat: lat,
                    lng: lng
                }
            });

            // Init default marker
            var markers = [];
            markers[0] = new google.maps.Marker({
                map: map,
                position: new google.maps.LatLng(lat, lng),
                draggable: true,
                animation: google.maps.Animation.DROP
            });
            markerdragEvent(markers);

            // Init search box
            var searchBox = new google.maps.places.SearchBox(document.getElementById(addEle));

            google.maps.event.addListener(searchBox, 'places_changed', function () {
                var places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }

                for (var i = 0, marker; marker = markers[i]; i++) {
                    marker.setMap(null);
                }

                markers = [];
                var bounds = new google.maps.LatLngBounds();
                for (var i = 0, place; place = places[i]; i++) {
                    var marker = new google.maps.Marker({
                        map: map,
                        position: place.geometry.location,
                        draggable: true,
                        animation: google.maps.Animation.DROP
                    });

                    markers.push(marker);
                    bounds.extend(place.geometry.location);
                }

                markerdragEvent(markers);
                map.fitBounds(bounds);
                console.log(places);
            });

            // Add marker when click on map
            google.maps.event.addListener(map, 'click', function (e) {
                for (var i = 0, marker; marker = markers[i]; i++) {
                    marker.setMap(null);
                }

                markers = [];
                markers[0] = new google.maps.Marker({
                    map: map,
                    position: new google.maps.LatLng(e.latLng.lat(), e.latLng.lng()),
                    draggable: true,
                    animation: google.maps.Animation.DROP
                });

                markerdragEvent(markers);
            });

            // Event on zoom map
            google.maps.event.addListener(map, 'zoom_changed', function () {
                $("#" + mapZ).val(map.getZoom());
            });

            // Event on change center map
            google.maps.event.addListener(map, 'center_changed', function () {
                $("#" + mapCenLat).val(map.getCenter().lat());
                $("#" + mapCenLng).val(map.getCenter().lng());
                console.log(map.getCenter());
            });
        }

        // Show, hide map on select change
        function controlMap(manual) {
            $('#' + mapArea).slideDown(100, function () {
                initializeMap();
            });

            return !1;
        }

        // Map Marker drag event
        function markerdragEvent(markers) {
            for (var i = 0, marker; marker = markers[i]; i++) {
                $("#" + mapLat).val(marker.position.lat());
                $("#" + mapLng).val(marker.position.lng());

                google.maps.event.addListener(marker, 'drag', function (e) {
                    $("#" + mapLat).val(e.latLng.lat());
                    $("#" + mapLng).val(e.latLng.lng());
                });
            }
        }

        load_district_ajax(5);

        $('#agc_city_id').change(function () {
            var city_id = $(this).val();
            load_district_ajax(city_id);
        });   //Initialize Select2 Elements

        function load_district_ajax(city_id) {
            $.ajax({
                type: 'POST',
                url: 'load_district.php',
                data: {
                    city_id: city_id,
                },
                success: function (response) {
                    $('.load_district_select').html(response);
                    $('.select2').select2()
                }
            });
        }
    </script>
@stop
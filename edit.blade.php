@extends('layouts.form')
@section('title')
    {{ config( 'constants.APP_NAME') }} | Edit Album
@stop

@section('header')
@section('css')
    @include('includes.form_css')
@stop
@include('includes.x_header')
@stop
@section('content')

    <!-- BEGIN FORM -->
    <!-- BEGIN CONTAINER -->
    <div class="page-container">
    @include('includes.x_menu_header')
    <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
            <!-- BEGIN CONTENT BODY -->
            <div class="page-content">
                <!-- BEGIN PAGE HEADER-->
            @include('includes.x_breadcrumb')
            <!-- BEGIN PAGE TITLE-->
                <h1 class="page-title"> Edit Album</h1>
                <!-- END PAGE TITLE-->
                <!-- END PAGE HEADER-->
                <!-- BEGIN DASHBOARD STATS 1-->
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN VALIDATION STATES-->
                        <div class="portlet light form-fit ">
                            <div class="portlet-body form">
                                <!-- BEGIN FORM-->
                                <form action="{{url(X_BASE_URL.'album/publish')}}" id="form_sample_4" method="post" class="form-horizontal" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="{{$album->id}}">
                                    <div class="form-body">
                                        <div class="alert alert-danger display-hide">
                                            <button class="close" data-close="alert"></button> You have some form errors. Please check below.
                                        </div>


                                        <div class="form-group">
                                            <label class="control-label col-md-3">Assigned User
                                                <span class="required"> * </span>
                                            </label>
                                            <div class="col-md-4">
                                                <div class="input-icon right">
                                                    <select name="difficulty_id" name="select_user" class="form-control">
                                                        <option>Select user  </option>
                                                        @foreach($users as $user)
                                                            <option @if( $user->id == $album->user_id ) {{ 'selected="selected"' }} @endif value="{{$user->id}}">{{$user->first_name}} {{$user->last_name}} [{{ $user->email }}]</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3">Name
                                                <span class="required"> * </span>
                                            </label>
                                            <div class="col-md-4">
                                                <div class="input-icon right">
                                                    <i class="fa"></i>
                                                    <input type="text" class="required form-control" value="{{ $album->name }}" name="name" /> </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3">Min-Max duration
                                                <span class="required"> * </span>
                                            </label>
                                            <div class="col-md-2">
                                                <div class="input-icon right">
                                                    <i class="fa"></i>
                                                    <input type="text" value="{{ $album->min_duration }}" class=" form-control" placeholder="Min Duration"  name="min_duration" />
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="input-icon right">
                                                    <i class="fa"></i>
                                                    <input type="text" value="{{ $album->max_duration }}" class=" form-control" placeholder="Max Duration"  name="max_duration" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Country & Cost
                                                <span class="required"> * </span>
                                            </label>
                                            <div class="col-md-2">
                                                <div class="input-icon right">
                                                    <select name="currency" class=" form-control">
                                                        <option> Select Currency</option>
                                                        @foreach( $countries as $country )
                                                            <option @if( $country->id == $album->currency ) {{ 'selected' }}@endif value="{{$country->id}}">{{ $country->currency }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="input-icon right">
                                                    <i class="fa"></i>
                                                    <input type="text" value="{{ $album->cost }}" class=" form-control" placeholder="Trip Cost"  name="cost" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Difficulty Level
                                                <span class="required"> * </span>
                                            </label>
                                            <div class="col-md-4">
                                                <div class="input-icon right">
                                                    <select name="difficulty_id" class="form-control">
                                                        <option>Select Difficulty Level </option>
                                                        @foreach( $difficulties_level as $difficulty )
                                                            <option @if( $difficulty->id == $album->difficulty_id ) {{'selected'}} @endif value="{{$difficulty->id}}">{{ $difficulty->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Season Month
                                                <span class="required"> * </span>
                                            </label>
                                            <div class="col-md-4">
                                                <div class="input-icon right">
                                                    <select name="season_month_id[]" id="e1" class="form-control select2-multiple" multiple >
                                                        @foreach( $season_months as $season_month )
                                                            <option @if( in_array( $season_month->id, explode( ',', $album_season_month->season_month_id ) ) ) {{ 'selected' }} @endif value="{{$season_month->id}}">{{$season_month->name}}</option>
                                                        @endforeach

                                                    </select>
                                                    <input type="checkbox" id="checkbox" >Select All
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Activity Category
                                                <span class="required"> * </span>
                                            </label>
                                            <div class="col-md-4">
                                                <div class="input-icon right">
                                                    <select name="activity_category_id[]" class="select2-multiple form-control" multiple>

                                                                @foreach( $activity_categories as $activity_category )
                                                                    <option @if( in_array( $activity_category->id, explode( ',', $album_activity_category->activity_category_id ) ) ) {{ 'selected' }} @endif value="{{$activity_category->id}}">{{$activity_category->name}}</option>
                                                                @endforeach

                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Location
                                                <span class="required"> * </span>
                                            </label>
                                            <div class="col-md-5">
                                                <div class="input-icon right">
                                                    <input id="pac-input" name="location" class="controls  form-control" style="width:270px; margin-top:10px" value="{{$album->location}}" type="text" placeholder="Location"/>
                                                    <div id="map" style="height:300px;"></div>

                                                    <input id="lat" type="hidden" class="form-control" name="lat" value="{{$album->lat}}">
                                                    <input id="lng" type="hidden" class="form-control" name="lng" value="{{$album->lng}}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3">Description
                                                <span class="required"> * </span>
                                            </label>
                                            <div class="col-md-9">
                                                <div class="input-icon right">
                                                    <i class="fa"></i>
                                                    <textarea class="tinymce form-control" id="description" name="description" >{{ $album->description }}</textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3">Theme
                                                <span class="required"> * </span>
                                            </label>
                                            <div class="col-md-4">
                                                <div class="input-icon right">
                                                    <select name="theme_id[]" class="select2-multiple form-control" multiple>
                                                        <option value="0">Select Theme </option>
                                                        @foreach( $themes as $theme )
                                                            <option @if( in_array( $theme->id,explode(',',$album_theme['theme_id'] ) ) ) {{'selected'}} @endif value="{{$theme->id}}">{{ $theme->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Region
                                                <span class="required"></span>
                                            </label>
                                            <div class="col-md-4">
                                                <div class="input-icon right">
                                                    <select name="region_id[]" class="select2-multiple form-control" multiple>
                                                        <option value="0">Select Region </option>
                                                        @foreach( $regions as $region )
                                                            <option @if( in_array( $region->id, explode( ',', $album_regions['region_id'] ) ) ) {{ 'selected' }} @endif value="{{$region->id}}">{{ $region->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3">Bucket List Status
                                            </label>
                                            <div class="col-md-4">
                                                <div class="mt-radio-inline">
                                                    <label class="mt-radio">
                                                        <input type="radio" name="bucket_list_status" id="active" value="1" checked="" @if($album->bucket_list_status == 1) checked @endif> Active
                                                        <span></span>
                                                    </label>
                                                    <label class="mt-radio">
                                                        <input type="radio" name="bucket_list_status" id="in-active" value="0" @if($album->bucket_list_status == 0) checked @endif>In-active
                                                        <span></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3">Images</label>
                                            <div class="col-md-4">
                                                <div class="col-md-10 col-md-offset-3">

                                                    <input type="file" class="form-control-file multi with-preview"  name="image[]" >

                                                </div>
                                            </div>
                                        </div>

                                        <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax">

                                            <tbody>

                                            <?php $i = 0; ?>
                                            @foreach($album_images as $key=>$album_image)
                                                @if( $i == 0)
                                                    <tr>
                                                        @endif
                                                        <td class="col-md-3">
                                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                                                    <?php $image = '';
                                                                    ?>
                                                                    <?php
                                                                    $image = DEFAULT_IMG_URL;
                                                                    if ( file_exists( config('constants.PHOTOS_IMG_DIR').$album_image->album_id.'/'.$album_image->image  ) ){

                                                                        $image = config('constants.PHOTOS_IMG_URL').$album_image->album_id.'/'.$album_image->image;
                                                                    }
                                                                    ?>
                                                                    <img src="{{ $image }}" alt="" />
                                                                </div>
                                                                <div>

                                                                <span class="btn default btn-file">
                                                                    <a href="javascript:;" class="btn red fileinput-new" data-dismiss="fileinput" onclick=" var ans = confirm( ' Are you sure you want to delete this image?'); if( ans ){ window.location.href='{{url(X_BASE_URL.'album/'.$album_image->id.'/delete' )}}'; }"  > X </a>
                                                                <span class="fileinput-new"> <input type="checkbox" name="cover_status[] " @if($album_image->cover_status == 1) checked @endif value="1"/> Make as album cover image </span>
														        </span>
                                                                </div>


                                                                <div>
                                                                <span class="btn default btn-file">
                                                                    <input type="hidden" value="{{ $album_image->id }}" name="album_image_id[]" />
                                                                    <span class="fileinput-new "> <input placeholder="caption" type="text" class="required form-control" value="{{ $album_image->title }}" name="update_image_title[]" />  </span>
														        </span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <?php $i++; ?>
                                                        @if( $i == 3)
                                                    </tr>
                                                    <?php $i =0; ?>
                                                @endif
                                            @endforeach

                                            </tbody>
                                        </table>

                                        <div class="form-group">
                                            <label class="control-label col-md-3">Status
                                            </label>
                                            <div class="col-md-4">
                                                <div class="mt-radio-inline">
                                                    <label class="mt-radio">
                                                        <input type="radio" name="status" id="active" value="1" checked="" @if($album->status == 1) checked @endif> Active
                                                        <span></span>
                                                    </label>
                                                    <label class="mt-radio">
                                                        <input type="radio" name="status" id="in-active" value="" @if(in_array($album->status ,array(0,2,8,9))) checked @endif>In-active
                                                        <span></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <div class="row">
                                            <div class="col-md-offset-3 col-md-9">
                                                <button type="submit" class="btn green">Submit</button>
                                                <button type="reset" class="btn default">Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <!-- END FORM-->
                            </div>
                        </div>
                        <!-- END VALIDATION STATES-->
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
@section('footer_js')
    @include('includes.form_js')

    <script type="text/javascript">

        function selectAllSeasonMonth( season_month_val){

            if( season_month_val == 0 ){
                $('#select_all option').attr('selected', true);
                $('#select_option').attr('selected', false);
            }
        }

        function initMap() {


            var myLatLng = {lat: <?php echo $album->lat; ?>, lng: <?php echo $album->lng; ?>};

            var map = new google.maps.Map(document.getElementById('map'), {
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                zoom: 4,
                center: myLatLng
            });

            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
                title: '<?php echo $album->location; ?>'
            });

            var input = /** @type {HTMLInputElement} */(
                document.getElementById('pac-input')
            );
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
            var searchBox = new google.maps.places.SearchBox(
                /** @type {HTMLInputElement} */(input));
            google.maps.event.addListener(searchBox, 'places_changed', function() {

                var markers = [];
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
                    var image = {
                        url: 'http://maps.google.com/mapfiles/kml/paddle/red-circle.png',//place.icon,
                        size: new google.maps.Size(71, 71),
                        origin: new google.maps.Point(0, 0),
                        anchor: new google.maps.Point(17, 34),
                        scaledSize: new google.maps.Size(25, 25)
                    };
                    var marker = new google.maps.Marker({
                        draggable: true,
                        map: map,
                        icon: image,
                        title: place.name,
                        position: place.geometry.location
                    });
                    // drag response
                    google.maps.event.addListener(marker, 'dragend', function(e) {

                        var geocoder = new google.maps.Geocoder();

                        geocoder.geocode({
                            latLng: this.getPosition()
                        }, function(responses) {
                            if (responses && responses.length > 0) {
                                $('#pac-input').val( responses[0].formatted_address );
                            }
                        });

                        displayPosition(this.getPosition());
                    });
                    // click response
                    google.maps.event.addListener(marker, 'click', function(e) {
                        displayPosition(this.getPosition());
                    });
                    markers.push(marker);

                    displayPosition(place.geometry.location);
                    bounds.extend(place.geometry.location);

                }
                map.fitBounds(bounds);
            });
            google.maps.event.addListener(map, 'bounds_changed', function() {
                var bounds = map.getBounds();
                searchBox.setBounds(bounds);
                if(map.getZoom()> 11){
                    map.setZoom(15)
                }else{
                    map.setZoom(11)
                }

            });
            // displays a position on two <input> elements
            function displayPosition(pos) {
                document.getElementById('lat').value = pos.lat();
                document.getElementById('lng').value = pos.lng();
            }
        }

    </script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAExw1bsoKzUEQFSJlFGJs0uCNZW0zAIog&libraries=places&signed_in=true&callback=initMap"
            async defer></script>
    {{--{!! Html::script('assets/ckeditor/ckeditor.js') !!}--}}
    {{--{!! Html::script('assets/ckfinder/ckfinder.js') !!}--}}
    {{--<script>--}}
        {{--var editor = CKEDITOR.replace( 'description', {--}}
            {{--filebrowserUploadUrl : '<?php echo BASE_URL ?>assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',--}}
            {{--filebrowserImageUploadUrl : '<?php echo BASE_URL ?>assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',--}}
            {{--filebrowserFlashUploadUrl : '<?php echo BASE_URL ?>assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'--}}
        {{--});--}}
    {{--</script>--}}

    {!! Html::script('assets/tinymce/jquery.tinymce.min.js') !!}
    {!! Html::script('assets/tinymce/tinymce.min.js') !!}


    <script type="text/javascript">

        (function() {
            var editor_id = "";
            tinymce.PluginManager.add('instagram', function(editor, url) {
                // Add a button that opens a window
                editor.addButton('instagram', {
                    text: 'Instagram',
                    icon: false,
                    onclick: function() {
                        // Open window
                        editor.windowManager.open({
                            title: 'Instagram Embed',
                            body: [
                                {   type: 'textbox',
                                    size: 40,
                                    height: '100px',
                                    name: 'instagram',
                                    label: 'instagram'
                                }
                            ],
                            onsubmit: function(e) {
                                // Insert content when the window form is submitted
                                console.log(e.data.instagram);
                                var embedCode = e.data.instagram;
                                var script = embedCode.match(/<script.*<\/script>/)[0];
                                var scriptSrc = script.match(/".*\.js/)[0].split("\"")[1];
                                console.log(script, scriptSrc);

                                var sc = document.createElement("script");
                                sc.setAttribute("src", scriptSrc);
                                sc.setAttribute("type", "text/javascript");

                                var iframe = document.getElementById(editor_id + "_ifr");
                                var iframeHead = iframe.contentWindow.document.getElementsByTagName('head')[0];

                                tinyMCE.activeEditor.insertContent(e.data.instagram);
                                iframeHead.appendChild(sc);
                                setTimeout(function() {
                                    iframe.contentWindow.instgrm.Embeds.process();
                                }, 1000)
                                // editor.insertContent('Title: ' + e.data.title);
                            }
                        });
                    }
                });
            });
            tinymce.init({
                selector:'textarea.tinymce',
                toolbar: 'bold italic | alignleft aligncenter alignright alignjustify | undo redo | link image media | code preview | fullscreen | instagram',
                plugins: "wordcount fullscreen link image code preview media instagram",
                menubar: true,
                relative_urls: false,
                extended_valid_elements : "script[language|type|async|src|charset]",
                file_browser_callback : function(field_name, url, type, win) {
                    var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
                    var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

                    var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;
                    if (type == 'image') {
                        cmsURL = cmsURL + "&type=Images";
                    } else {
                        cmsURL = cmsURL + "&type=Files";
                    }

                    tinyMCE.activeEditor.windowManager.open({
                        file : cmsURL,
                        title : 'Filemanager',
                        width : x * 0.8,
                        height : y * 0.8,
                        resizable : "yes",
                        close_previous : "no"
                    });
                },
                setup: function (editor) {
                    console.log(editor);
                    editor.on('init', function (args) {
                        editor_id = args.target.id;
                    });
                },

            });
            tinymce.init(editor_config);

        })();




    </script>

    <script type="text/javascript">

        $("#e1").select2();
        $("#checkbox").click(function(){
            if($("#checkbox").is(':checked') ){
                $("#e1 > option").prop("selected","selected");
                $("#e1").trigger("change");
            }else{
                $("#e1 > option").removeAttr("selected");
                $("#e1").trigger("change");
            }
        });

        $("#button").click(function(){
            alert($("#e1").val());
        });

    </script>

@stop

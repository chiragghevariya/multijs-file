<section id="deshbord">
    <div class="container">
        <form class=""  id="form_sample_2" action="{{ url(BASE_URL.'photos/add') }}" method="POST"  enctype="multipart/form-data">
            <div class="col-md-8 col-xs-12 col-md-offset-2 form-fade-in">

                <div class="form-group-lg col-md-12">
                    <input id="title-input" name="name" type="text" autofocus placeholder="Title" />
                </div>

                <div class="col-md-11 col-xs-12">

                    <div class="input-group col-md-12 col-xs-11">
                        <div class="col-md-6 padding-left-0">
                            <select name="currency" class="required form-control">
                                <option>Currency</option>
                                @foreach( $countries as $country )
                                    <option value="{{$country->id}}">{{ $country->currency }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 padding-left-0 padding-right-0">
                            <input id="cost" type="text" class="required price form-control" name="cost" placeholder="Trip Cost">
                        </div>
                    </div>

                    <div class="input-group col-md-12 col-xs-11">
                        <input id="min_duration" type="text" class="required price form-control" name="min_duration" placeholder="Min Duration">

                    </div>

                    <div class="input-group col-md-12 col-xs-11">

                        <input id="max_duration" type="text" class="required price form-control" name="max_duration" placeholder="Max Duration">
                    </div>

                    <div class="input-group col-md-12 col-xs-11">
                        <select name="difficulty_id" class="required form-control">
                            <option>Select Difficulty Level </option>
                            @foreach( $difficulties_level as $difficulty )
                                <option value="{{$difficulty->id}}">{{ $difficulty->name}}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="input-group col-md-12 col-xs-11">
                        <select id="e1" name="season_month_id[]" class="required form-control select2-multiple album_season_month" multiple >
                            @foreach( $season_months as $season_month )
                                <option value="{{$season_month->id}}">{{$season_month->name}}</option>
                            @endforeach
                        </select>
                        <div class="mnths-all-top-right-pull-">
                            <div class="pull-left">
                                <input type="checkbox" id="checkbox" >
                            </div>
                            <div class="pull-left pull-left-input-checvk-0boc">
                                <lable>Select All Season Month</lable>
                            </div>
                        </div>
                    </div>


                </div>

            </div>
            <div class="col-md-11" id="clone-add">
                <div class="second-text-fild col-md-11 col-md-offset-2 col-xs-12">

                    <div class="form-group">
                        <div class="col-md-10">
                            <div class="input-icon right">
                                <i class="fa"></i>
                                <textarea class="form-control" maxlength="30" id="descriptions" name="description"></textarea>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="col-md-10">
                            <!--<input id="location" type="text" class="required form-control" name="location" placeholder="Location" autocomplete="on">-->
                            <input id="pac-input" name="location" class="controls  form-control" style="width:300px; margin-top:10px" type="text" placeholder="Location">
                            <div id="map" style="height:300px;"></div>

                            <input id="lat" type="hidden" class="form-control" name="lat">
                            <input id="lng" type="hidden" class="form-control" name="lng">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-10">
                            <select name="activity_category_id[]" class="required select2-multiple form-control album_activity_select" multiple>
                                @foreach( $activity_categories as $activity_category )
                                            <option value="{{$activity_category->id}}">{{$activity_category->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                </div>

                <div class="form-group">
                    <div class="col-md-6 col-md-offset-2">

                        <input type="file" class="form-control-file multi with-preview"  name="image[]" >

                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-md-offset-3">
                         <input type="checkbox" id="term" value="agree" name="term" class="required" /> Agree with the terms and conditions

                    </div>
                </div>

                <div class="col-md-8 col-xs-12 col-md-offset-2 col-xs-12 publi-save-drft-pbls">
                    <div class="pull-left margin-none margin-right-20">
                        <input class="btn btn-default" type="submit" name="save" value="PUBLISH" />
                    </div>
                    <div class="pull-left margin-none">
                        <input class="btn btn-default" type="submit" name= "save" value="SAVE AS DRAFT" />
                    </div>
            </div>



        </form>
    </div>

</section>

























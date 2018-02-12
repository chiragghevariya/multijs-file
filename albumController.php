<?php

namespace App\Http\Controllers;

use App\Activity_category;
use App\Album;

use App\Album_activity_categoris;
use App\Album_region;
use App\Album_season_months;
use App\Album_theme;
use App\Article;
use App\Article_activity_category;
use App\Article_category;
use App\Article_itinerary;
use App\Article_season_month;
use App\Banner;
use App\Campaign;
use App\Category;
use App\City;
use App\Country;
use App\Destination;
use App\Difficulty;
use App\Images;
use App\Like;
use App\Region;
use App\Season_month;
use App\State;
use App\Status;
use App\Theme;
use App\User;
use App\User_point;
use App\Weekend_gateway;
use Auth;
use function dd;
use File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Image;
use Illuminate\Http\Request;

use function redirect;
use Toastr;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use function view;

class albumController extends Controller
{

    public function __construct(

        Album $album,Campaign $campaign,Weekend_gateway $weekend_gateway,Album_region $album_region,User $user,Images $image ,Album_activity_categoris $album_activity_categori, Album_season_months $album_season_month, Banner $banner, Country $country, Category $category, Difficulty $difficulty, State $state, City $city, Season_month $season_month, Activity_category $activity_category,
        Destination $destination, Region $region, Article $article, Article_itinerary $itinerary, Article_category $article_category, Article_activity_category $article_activity_category, Article_itinerary $article_itinerary,
        Article_season_month $article_season_month,Theme $theme,Album_theme $album_theme,Like $like
    )

    {

        $this->_image=$image;
        $this->_album = $album;
        $this->_user=$user;
        $this->_album_region=$album_region;
        $this->_campaign=$campaign;
        $this->_weekend_gateway=$weekend_gateway;
        $this->_banner	=	$banner;
        $this->_country	=	$country;
        $this->_category	=	$category;
        $this->_difficulty	=	$difficulty;
        $this->_state	=	$state;
        $this->_city	=	$city;
        $this->_season_month	=	$season_month;
        $this->_activity_category	=	$activity_category;
        $this->_destination	=	$destination;
        $this->_region	=	$region;
        $this->_article	=	$article;
        $this->_article_itinerary=$itinerary;
        $this->_album_acitvity_categori=$album_activity_categori;
        $this->_album_season_month=$album_season_month;
        $this->_article_category	=	$article_category;
        $this->_article_activity_category	=	$article_activity_category;
        $this->_article_itinerary	=	$article_itinerary;
        $this->_article_season_month	=	$article_season_month;
        $this->_theme=$theme;
        $this->_album_theme=$album_theme;
        $this->_likes=$like;

        $this->_userData = Auth::guard('users')->user();


    }

    public function add(){

        $data['difficulties_level'] = $this->_difficulty->whereStatus(1)->get();/* Get all active difficulty level */
        $data['season_months'] = $this->_season_month->whereStatus(1)->get();/* Get all active season month */
        $data['countries'] = $this->_country->whereStatus(1)->get();/* Get all active region */
        $data['states'] = $this->_state->whereStatus(1)->get();/* Get all active state */
        $data['cities'] = $this->_city->whereStatus(1)->get();/* Get all active city */
        $data['activity_categories'] = $this->_activity_category->whereStatus(1)->get();/* Get all active aprent activitt categoties */

        return view('front.album.add',$data);
    }

    public function post(){


             $input= Input::all();

             $input['status'] = ( isset( $input['save'] ) && $input['save'] == 'SAVE AS DRAFT' )?Status::$DRAFT_ARTICLE:Status::$REJECTED;


            $input['user_id']= $this->_userData->id;
            $input_album_activity_categories=$input['activity_category_id'];
            $input_album_season_month=$input['season_month_id'];
            $input_album_title=$input['image_title'];

            unset($input['season_month_id']);
            unset($input['activity_category_id']);
            unset($input['image_title']);

            $album =new Album($input);

            $sav_album=$album->save();

            $destinationPath = config('constants.PHOTOS_IMG_DIR').$album->id.'/'; /* upload path */
            if (!file_exists( $destinationPath )) { /* path does not exist */

                $result = File::makeDirectory($destinationPath, 0777, true, true);/* make directory */
            }

            foreach (Input::file('image') as $key=> $file ){

                if( !empty( $file ) ){

                    $extension = $file->getClientOriginalExtension(); /* getting image extension */
                    $fileName = time().'_'.mt_rand() .'.'.$extension; /* renaming image */
//                   Image::make( $file )->resize(342, 184)->save( $destinationPath.$fileName );
                    $file->move($destinationPath, $fileName); /* uploading file to given path */

                    /* Call update function to user object and update record in `weekend_gateways` table */
                    $images_album['album_id'] = $album->id;
                    $images_album['image'] = $fileName;
                    $images_album['title'] = $input_album_title[$key];
                    $images_album['status'] = Status::$INACTIVE;

                    $album_image= new Images($images_album);
                    $saveimage = $album_image->save();
                }

            }

        foreach( $input_album_activity_categories as $album_activity_categories ){

            $activity_category['album_id'] = $album->id;
            $activity_category['activity_category_id'] = $album_activity_categories;
            $activity_category['status'] = 1;

            $activity_category_obj = new Album_activity_categoris($activity_category );/* Create activity_category_obj object */
            $saveData = $activity_category_obj->save(); /* Call save function to user object and insert record in `article_activity_categories` table */
        }

        foreach( $input_album_season_month as $album_season_month ){

            $season_month['album_id'] = $album->id;
            $season_month['season_month_id'] = $album_season_month;
            $season_month['status'] = 1;

            $season_month_obj = new Album_season_months( $season_month );/* Create season_month_obj object */
            $saveData = $season_month_obj->save(); /* Call save function to user object and insert record in `article_season_months` table */
        }

        if ($input['status'] == 9){

            Mail::send('emails.email-message.email_publish',
                [
                    'name' => $this->_userData->first_name.' '.$this->_userData->last_name,

                ],
                function ($m) use( $input )  {


                    $m->to( $this->_userData->email, $this->_userData->first_name.' '.$this->_userData->last_name )->subject('Your album is submitted for publish');

                });
        }

        else{

            Mail::send('emails.email-message.email_draft',
                [
                    'name' => $this->_userData->first_name.' '.$this->_userData->last_name,

                ],
                function ($m) use( $input )  {


                    $m->to( $this->_userData->email, $this->_userData->first_name.' '.$this->_userData->last_name )->subject('Your album in draft');

                });
        }


        Toastr::success('Album added successfully');
//            Toastr::success( 'Article added successfully' );
             return redirect('photos/add');

    }

    public function listalbum(){

        $breadcrumb = [
            'parent_title' => ['photos'],
            'parent_url' => [ X_BASE_URL.'photos'],
            'page_title' => 'Add'
        ];
        $data['breadcrumb'] = $breadcrumb;
        return view('album.list',$data);
    }


    public function publish($id){

        $breadcrumb = [
            'parent_title' => [ 'Album' ],
            'parent_url' => [ X_BASE_URL.'album' ],
            'page_title' => '	Publish Album'
        ];

        $data['breadcrumb'] = $breadcrumb;
        $data['themes']=$this->_theme->whereStatus(1)->get();
        $data['album_theme']=$this->_album_theme->select(DB::raw('GROUP_CONCAT(theme_id) AS theme_id'))->where('album_id',$id)->first();
        $data['regions'] = $this->_region->whereStatus(1)->get();/* Get all active region */
        $data['countries'] = $this->_country->whereStatus(1)->get();/* Get all active country */
        $data['season_months'] = $this->_season_month->whereStatus(1)->get();/* Get all active season month */
        $data['difficulties_level'] = $this->_difficulty->whereStatus(1)->get();/* Get all active difficulties_level */
        $data['categories'] = $this->_category->whereStatus(1)->whereNotIn( 'id', [4,5] )->get();/* Get all active category */
        $data['activity_categories'] = $this->_activity_category->whereStatus(1)->get();/* Get all active aprent activitt categoties */
        $data['activity_categories'] = $this->_activity_category->whereStatus(1)->get();/* Get all active aprent activitt categoties */

        $data['album'] = $this->_album
            ->select( 'albums.*', 'users.first_name', 'users.last_name', 'users.image AS user_image',                                                                                                                                    'albums.id AS album_id' )
            ->join('users','users.id','=', 'albums.user_id' )
            ->where( [ 'albums.id' => $id ] )->first();

        $data['album_regions']=$this->_album_region->select( DB::raw('GROUP_CONCAT(region_id ) AS region_id' )  )->where('album_id',$id)->first();
        $data['album_season_month'] = $this->_album_season_month
            ->select( DB::raw('GROUP_CONCAT(season_month_id ) AS season_month_id' )  )
            ->whereAlbumId( $id )->first();

        $data['album_images']=$this->_image->where('album_id',$id)->get();

        $data['users']=$this->_user->orderBy('first_name')->get();


        $data['album_activity_category'] = $this->_album_acitvity_categori
            ->select( DB::raw('GROUP_CONCAT(activity_category_id) AS activity_category_id' ) )
            ->whereAlbumId( $id )->first();

        return view( 'album.edit' )->with($data);

    }

    public function postpublish(){

        $input = Input::all();

        $album_id =$this->_album->where('id',$input['id'])->first();
        $input_album_activity_categories = ( !empty ( $input['activity_category_id']) )?$input['activity_category_id']:[];
        $input_album_season_month = ( !empty( $input['season_month_id'] ))?$input['season_month_id']:[];
        $images_album = ( !empty( $input['image_title'] ) )?$input['image_title']:[];
        $album_image_ids = ( !empty( $input['album_image_id'] ) )?$input['album_image_id']:[];
        $update_image_titles = ( !empty( $input['update_image_title'] ) )?$input['update_image_title']:[];
        $update_cover_statuses = ( !empty( $input['cover_status'] ) )?$input['cover_status']:[];
        $input_album_region_id = ( !empty( $input['region_id'] ) )?$input['region_id']:[];
        $input_album_theme=( !empty( $input['theme_id'] ) )?$input['theme_id']:[];

        unset($input['activity_category_id']);
        unset($input['season_month_id']);
        unset($input['image_title']);
        unset($input['album_image_id']);
        unset($input['update_image_title']);
        unset($input['cover_status']);
        unset($input['image']);
        unset($input['region_id']);
        unset($input['theme_id']);

        $this->_album_acitvity_categori->where('album_id','=',$input['id'])->delete();

        $this->_album_season_month->where('album_id','=',$input['id'])->delete();

        $this->_album_theme->where('album_id','=',$input['id'])->delete();

        $this->_album_region->where('album_id','=',$input['id'])->delete();


        $input['publish_at'] = date( 'Y-m-d H:i:s' );

        $albums=$this->_album->where( 'id', '=', $input['id'] )->update( $input );

        $destinationPath = config('constants.PHOTOS_IMG_DIR').$input['id'].'/'; /* upload path */
        if (!file_exists( $destinationPath )) { /* path does not exist */

            $result = File::makeDirectory($destinationPath, 0777, true, true);/* make directory */
        }

//        (isset($input['select_all']))
//        $input['user_id'] = ( isset( $input['select_all'] ) )?$albums->id:'';

        foreach (Input::file('image') as $key=> $file ){

            if( !empty( $file ) ){

                $extension = $file->getClientOriginalExtension(); /* getting image extension */
                $fileName = time().'_'.mt_rand() .'.'.$extension; /* renaming image */
//                   Image::make( $file )->resize(342, 184)->save( $destinationPath.$fileName );
                $file->move($destinationPath, $fileName); /* uploading file to given path */

                /* Call update function to user object and update record in `weekend_gateways` table */
                $images_album['album_id'] = $input['id'];
                $images_album['image'] = $fileName;
                $images_album['title'] = $images_album[$key];
                $images_album['cover_status'] = 0;

                $album_image= new Images($images_album);
                $saveimage = $album_image->save();
            }

        }




        foreach ( $album_image_ids as $key =>  $album_image_id ){

            /* Call update function to user object and update record in `weekend_gateways` table */
            $update_images_album['title'] = ( isset( $update_image_titles[$key] ) )? $update_image_titles[$key]:'';
            $update_images_album['cover_status'] = ( isset( $update_cover_statuses[$key] ) )?$update_cover_statuses[$key]:'';
	        $albums=$this->_image->where( 'id', '=', $album_image_id )->update( $update_images_album );

        }

        foreach( $input_album_activity_categories as $album_activity_categories ){

            $activity_category['album_id'] = $input['id'];
            $activity_category['activity_category_id'] = $album_activity_categories;
            $activity_category['status'] = 1;

            $activity_category_obj = new Album_activity_categoris($activity_category);/* Create activity_category_obj object */
            $saveData = $activity_category_obj->save(); /* Call save function to user object and insert record in `article_activity_categories` table */
        }

        foreach( $input_album_theme as $album_theme ){

            $theme['album_id'] = $input['id'];
            $theme['theme_id'] = $album_theme;
            $theme['status'] = 1;

            $theme_obj = new Album_theme($theme);/* Create activity_category_obj object */
            $saveData = $theme_obj->save(); /* Call save function to user object and insert record in `article_activity_categories` table */
        }

        foreach( $input_album_season_month as $album_season_month ){

            $season_month['album_id'] = $input['id'];
            $season_month['season_month_id'] = $album_season_month;
            $season_month['status'] = 1;

            $season_month_obj = new Album_season_months($season_month);/* Create season_month_obj object */
            $saveData = $season_month_obj->save(); /* Call save function to user object and insert record in `article_season_months` table */
        }


        foreach ($input_album_region_id as $article_regions){

                $article_region['album_id']=$input['id'];
                $article_region['region_id']=$article_regions;
                $article_region['status']=1;
                $article_region_obj=new Album_region($article_region);
                $save_region=$article_region_obj->save();

            }

        $album_user_id=$this->_album->select('user_id')->where('id',$input['id'])->first();
        $article_category_user_point['category_id'] = 4;
        $article_category_user_point['user_id'] = $album_user_id->user_id;
        $article_category_user_point['post_id'] = $input['id'];
        $article_category_user_point['point'] = Category::categoryPoint(4,$input['status']);
        $article_category_user_point['status'] = 1;




        $article_category_point_obj = new User_point( $article_category_user_point );/* Create article_category_obj object */
        $saveData = $article_category_point_obj->save(); /* Call save function to user object and insert record in `article_categories` table */

        $user_point_add = $this->_user->select('total_point')->where('id',$album_user_id->user_id)->first();

        $user_point=$user_point_add['total_point'] +Category::categoryPoint(4,$input['status']) ;
        $this->_user->where('id',$album_user_id->user_id)->update( ['total_point' => $user_point ] );

        if ($input['status'] == 1){

            Mail::send('emails.email-message.email_published',
                [
                    'name' => Album::first_name($album_id->user_id).' '.Album::last_name($album_id->user_id),
                    'link'=>'travelhi5.com/album/'.$input['id'].'/detail',

                ],
                function ($m) use( $album_id )  {


                    $m->to( Album::email($album_id->user_id), Album::first_name($album_id->user_id).' '.Album::last_name($album_id->user_id) )->subject('Your '.$album_id->name .'is now published');

                });
        }



        return redirect( X_BASE_URL.'album' );


    }

    public function delete($id){

        $album_data = $this->_image->select( 'album_id' )->where('id','=',$id)->first();

        $this->_image->whereId( $id )->delete();

        return redirect(X_BASE_URL.'album/'.$album_data['album_id'].'/publish');
    }

    public function album_item_delete($id){

        $this->_album->where('id','=',$id)->update(
            [
                'status'=> Status::$DELETED

            ]
        );

        return redirect()->back();
    }

    public function album_list(){


        $data['albums'] = $this->_album
            ->select('albums.id','albums.user_id','albums.name','albums.status','albums.description','images.id','images.image','images.cover_status','images.title')
            ->join('album_regions','album_regions.album_id','=','albums.id')
            ->join('images','images.album_id','=','albums.id')

            ->where(
                [
                    'images.cover_status' =>1,
                    'albums.status' => Status::$ACTIVE,
                    'albums.user_id'=>$this->_userData->id
                ]
            )
            ->get();
//            dd($data);exit;
//        return view( 'front.find_trails', $data );

//         $data['album_image']=$this->_image->select('id','album_id','title','image','cover_status')->get();


        return view('front.album.album_list',$data);
    }

    public function index(){

        $data['activity_categories']=$this->_activity_category->whereStatus(1)->get();
        $data['countries']=$this->_country->all();
        $data['states']=$this->_state->all();
        $data['regions']=$this->_region->all();
        $data['destinations']=$this->_destination->all();
        $data['weekend_gateways']=$this->_weekend_gateway->all();
        $data['difficulties']=$this->_difficulty->all();
        $data['season_month']=$this->_season_month->all();
        $data['campaigns']=$this->_campaign->all();
        $data['themes']=$this->_theme->all();

        $data['albums'] = $this->_album
            ->select('albums.id','albums.user_id','albums.name','albums.location','albums.total_visitor','albums.total_like',
                'albums.description','albums.status','albums.cost','albums.min_duration','albums.max_duration',
                'images.cover_status','images.title','images.image','albums.difficulty_id'
            )
            ->join('images','images.album_id','=','albums.id')
            ->where(
                [
                    'albums.status' => Status::$ACTIVE,
                    'images.cover_status'=>1
                ]
            )->orderBy('albums.id','DESC')->paginate(4);

        return view('front.find_album',$data);

    }

    public function detail($id){


        $album_status= $this->_album->where(['id'=>$id])->first();

        if ($album_status->status == 8){

            return view('errors.album_draft_detail');
        }

        else{


            $visitor_count_album = $this->_album->select('albums.total_visitor')->where('id',$id)->first();
            $this->_album->where('id',$id)->update( ['total_visitor' => $visitor_count_album['total_visitor']+1] );

            /*similarActivity */

            $single_activity_category=$this->_album_acitvity_categori->where('album_id',$id)->value('activity_category_id');
//            dd($single_activity_category);
            $data['similarActivity']=  $this->_album
                ->select('albums.id','albums.user_id','albums.name','albums.location','albums.lat','albums.lng','albums.difficulty_id',
                    'albums.description','albums.status','albums.cost','albums.min_duration','albums.max_duration','albums.total_like','albums.total_visitor',
                    'images.cover_status','images.title','images.image'
                )
                ->join('images','images.album_id','=','albums.id')
                ->join('album_activity_categoris','album_activity_categoris.album_id','=','albums.id')
                ->where([

                    'albums.status'=>1,
                    'album_activity_categoris.activity_category_id'=>$single_activity_category

                ])->orderBy('albums.id','DESC')->skip(1)->take(3)->get();
//            dd($data);

            /*neartrip*/
            $album_lat_lng=$this->_album->where('id',$id)->first();
            $latlngRaw = DB::raw ( $this->_album->getLocationAlbumRawNear( $album_lat_lng->lat, $album_lat_lng->lng, 50 ) );

            $data['neartrip'] = $this->_album
                ->select('albums.id','albums.user_id','albums.name','albums.location','albums.lat','albums.lng','albums.difficulty_id',
                    'albums.description','albums.status','albums.cost','albums.min_duration','albums.max_duration','albums.total_like','albums.total_visitor',
                    'images.cover_status','images.title','images.image',$latlngRaw
                )
                ->join('images','images.album_id','=','albums.id')
                ->having("distance", "<=", 50 )
                ->orderBy("distance")->skip(1)->take(3)->get();
//            dd($data);


            $data['albums'] = $this->_album
                ->select('albums.id','albums.user_id','albums.name','albums.location','albums.lat','albums.lng','albums.difficulty_id','albums.currency',
                    'albums.description','albums.status','albums.cost','albums.min_duration','albums.max_duration','albums.total_like','albums.total_visitor',
                    'images.cover_status','images.title','images.image'
                )
                ->join('images','images.album_id','=','albums.id')

                ->where('albums.id',$id)
                ->first();

            $data['images']=$this->_image->where('album_id',$id)->get();
            return view('front.album.detail',$data);
        }

    }

    public function albumLike($id){

        $likesexit=$this->_likes->where([

            'post_id'=>$id,
            'user_id'=>Auth::guard('users')->user()->id,
            'category_id'=>4
        ])->first();

        if($likesexit == null){

            $articleLike['user_id']=Auth::guard('users')->user()->id;
            $articleLike['post_id']=$id;
            $articleLike['category_id']=4;
            $articleLike['status']=1;

            $articleLike_obj=new Like($articleLike);
            $saveData=$articleLike_obj->save();

            $total_likes=$this->_album->select('id','total_like')->where('id',$id)->first();
            $this->_album->where('id',$id)->update(['total_like'=>$total_likes['total_like']+1]);

        }else{

            $this->_likes->where([

                'user_id'=>Auth::guard('users')->user()->id,
                'post_id'=>$id,
                'category_id'=>4
            ])->delete();

            $total_likes=$this->_album->select('total_like')->where('id',$id)->first();
            $this->_album->where('id',$id)->update(['total_like'=>$total_likes['total_like']-1]);
        }

        return redirect()->back();
    }

    public function draft_album_edit($id){



        $data['countries'] = $this->_country->whereStatus(1)->get();/* Get all active country */
        $data['season_months'] = $this->_season_month->whereStatus(1)->get();/* Get all active season month */
        $data['difficulties_level'] = $this->_difficulty->whereStatus(1)->get();/* Get all active difficulties_level */
        $data['activity_categories'] = $this->_activity_category->whereStatus(1)->get();/* Get all active aprent activitt categoties */

        $data['album'] = $this->_album
            ->select( 'albums.*', 'users.first_name', 'users.last_name', 'users.image AS user_image',                                                                                                                                    'albums.id AS album_id' )
            ->join('users','users.id','=', 'albums.user_id' )
            ->where( [ 'albums.id' => $id ] )->first();

        $data['album_season_month'] = $this->_album_season_month
            ->select( DB::raw('GROUP_CONCAT(season_month_id ) AS season_month_id' )  )
            ->whereAlbumId( $id )->first();

        $data['album_images']=$this->_image->where('album_id',$id)->get();

        $data['album_activity_category'] = $this->_album_acitvity_categori
            ->select( DB::raw('GROUP_CONCAT(activity_category_id) AS activity_category_id' ) )
            ->whereAlbumId( $id )->first();



        return view('front.draft.album_draft',$data);
    }

    public function draft_album_post(){


        $input = Input::all();

        $input['status'] = ( isset( $input['save'] ) && $input['save'] == 'SAVE AS DRAFT' )?Status::$DRAFT_ARTICLE:Status::$REJECTED;
        unset($input['save']);


        $input_album_activity_categories = ( !empty ( $input['activity_category_id']) )?$input['activity_category_id']:[];
        $input_album_season_month = ( !empty( $input['season_month_id'] ))?$input['season_month_id']:[];
        $images_album = ( !empty( $input['image_title'] ) )?$input['image_title']:[];
        $album_image_ids = ( !empty( $input['album_image_id'] ) )?$input['album_image_id']:[];
        $update_image_titles = ( !empty( $input['update_image_title'] ) )?$input['update_image_title']:[];

        unset($input['activity_category_id']);
        unset($input['season_month_id']);
        unset($input['image_title']);
        unset($input['album_image_id']);
        unset($input['update_image_title']);
        unset($input['image']);


        $this->_album_acitvity_categori->where('album_id','=',$input['id'])->delete();

        $this->_album_season_month->where('album_id','=',$input['id'])->delete();

        $albums=$this->_album->where( 'id', '=', $input['id'] )->update( $input );

        $destinationPath = config('constants.PHOTOS_IMG_DIR').$input['id'].'/'; /* upload path */
        if (!file_exists( $destinationPath )) { /* path does not exist */

            $result = File::makeDirectory($destinationPath, 0777, true, true);/* make directory */
        }

       $input['user_id'] = ( isset( $input['select_all'] ) )?$albums->id:'';

        foreach (Input::file('image') as $key=> $file ){

            if( !empty( $file ) ){

                $extension = $file->getClientOriginalExtension(); /* getting image extension */
                $fileName = time().'_'.mt_rand() .'.'.$extension; /* renaming image */
//                   Image::make( $file )->resize(342, 184)->save( $destinationPath.$fileName );
                $file->move($destinationPath, $fileName); /* uploading file to given path */

                /* Call update function to user object and update record in `weekend_gateways` table */
                $images_album['album_id'] = $input['id'];
                $images_album['image'] = $fileName;
                $images_album['title'] = $images_album[$key];
                $images_album['cover_status'] = 0;

                $album_image= new Images($images_album);
                $saveimage = $album_image->save();
            }

        }




        foreach ( $album_image_ids as $key =>  $album_image_id ){

            /* Call update function to user object and update record in `weekend_gateways` table */
            $update_images_album['title'] = ( isset( $update_image_titles[$key] ) )? $update_image_titles[$key]:'';
            $albums=$this->_image->where( 'id', '=', $album_image_id )->update( $update_images_album );

        }

        foreach( $input_album_activity_categories as $album_activity_categories ){

            $activity_category['album_id'] = $input['id'];
            $activity_category['activity_category_id'] = $album_activity_categories;
            $activity_category['status'] = 1;

            $activity_category_obj = new Album_activity_categoris($activity_category);/* Create activity_category_obj object */
            $saveData = $activity_category_obj->save(); /* Call save function to user object and insert record in `article_activity_categories` table */
        }

        foreach( $input_album_season_month as $album_season_month ){

            $season_month['album_id'] = $input['id'];
            $season_month['season_month_id'] = $album_season_month;
            $season_month['status'] = 1;

            $season_month_obj = new Album_season_months($season_month);/* Create season_month_obj object */
            $saveData = $season_month_obj->save(); /* Call save function to user object and insert record in `article_season_months` table */
        }

        if($input['status'] == 8){

            Toastr::success('your album in draft');
        }else{

            Toastr::success('your album is in publish');
        }

        return redirect('user/dashboard');


    }

    public function draft_album_image_delete($id){

        $album_data = $this->_image->select( 'album_id' )->where('id','=',$id)->first();

        $this->_image->whereId( $id )->delete();

        return redirect()->back();
    }

}

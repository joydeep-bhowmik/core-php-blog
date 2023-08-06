<?php
require_once('phphelper.php');
require_once('db_config.php');
require_once('./class/pagination.php');

class data{
   public  static  $base='';
   public  static  $error="";
   public  static  $title="";
   public  static  $description="A place where you get to learn cool coding/programming stuffs. Covering popular topics like HTML, CSS, JavaScript, PHP,virtual DOM, and many, many more.";
   public  static  $pathName="";
   public  static  $page=1;
   public  static  $results_per_page=10;
   public  static  $number_of_pages=0;
   public  static  $nextPage="";
   public  static  $prePage="";
   public  static  $pageType="";
   public  static  $posts=[];
   public  static  $pinnedPosts=[];
   public  static  $post=[];
   public  static  $stories=[];
   public  static  $story=[];
   public  static  $storyThumbnail="";
   public  static  $nextStoryLink="";
   public  static  $preStoryLink="";
   public  static  $topicName="";
   public  static function postInfo($post){
    $date=$post['date'];
    $year = date('Y', strtotime($date));
    $month = date('F', strtotime($date));
    $day= date('j', strtotime($date));
    $tagsList=explode(",",$post['tags']);
    $tags=[];
    foreach($tagsList as $tag){
        array_push($tags,"<a href='topics/$tag' class='tag'>$tag</a>");
    }
    $r=[
        'readingTime'=>fns::readingTime($post['content']),
        'tags'=>join(' ',$tags),
        'date'=>$day.' '.$month.', '.$year
    ];
    return $r;
}

}
if(isset($_GET['page'])){
    data::$page=(int)$_GET['page'];
}
data::$pathName=strtok($_SERVER["REQUEST_URI"], '?');
$data=new data();
Route::$base=data::$base;
Route::get('/',function(){
    data::$title=$_SERVER['SERVER_NAME'];
    data::$description="A place where you get to learn cool coding/programming stuffs. Covering popular topics like HTML, CSS, JavaScript, PHP,virtual DOM, and many, many more.";
    data::$pageType='index';
    data::$pinnedPosts=db::select('SELECT * FROM posts WHERE pinned=1 ORDER BY id DESC LIMIT '.data::$results_per_page.'');
    $pagination=new Pagination([
        'results_per_page' =>data::$results_per_page,
        'query' =>'SELECT * FROM posts WHERE pinned=? ORDER BY id DESC',
        'bind'=>[0],
        'page'=>(int)data::$page
     ]);
     if($pagination->number_of_result){
        data::$posts=$pagination->get();
        data::$number_of_pages=$pagination->number_of_pages();
     }else{
        data::$error="No posts found";
     }
});
Route::get('/create',function($param){
    header("location:admin/?new");
});
Route::get('/logout',function($param){
    header("location:admin/?logout");
});
Route::get('/articles/{title}',function($param){
    data::$pageType='article';
    $id=explode("-",trim($param['title']));
    $id=trim(end($id));
    data::$post=db::select('SELECT * FROM posts WHERE id=?',[$id]);
    if(!data::$post){
        data::$post=[];
        data::$title='404 not found';
        data::$pageType='error';
        data::$error="Nothing's here";
    }else{
        data::$post=data::$post [0];
        data::$title=data::$post['title'];
        data::$description=data::$post['description'];
        if(empty(data::$description)){
            data::$description=substr(strip_tags($GLOBALS['Parsedown']->text(data::$post['content'])),0,150);
        }
    }
});
Route::get('/archive',function(){
    data::$title='archive';
    data::$pageType='archive';
});
Route::get('/topics',function(){
    data::$title='All topics';
    data::$pageType='topicListing';
});
Route::get('/topics/{name}',function($param){
    data::$title='Topic -'.$param['name'];
    data::$description="All posts related to topic named ".$param['name'];
    data::$pageType='singleTopic';
    data::$topicName=$param['name'];
    $topic=$param['name'];
    $pagination=new Pagination([
        'results_per_page' =>data::$results_per_page,
        'query' =>'SELECT * FROM posts WHERE tags LIKE ? ORDER BY id DESC',
        'bind'=>["%$topic%"],
        'page'=>(int)data::$page
     ]);
     if($pagination->number_of_result){
        data::$posts=$pagination->get();
        data::$number_of_pages=$pagination->number_of_pages();
     }
     if(!data::$posts){
        data::$posts=[];
        data::$title='404 not found';
        data::$pageType='error';
    }
});
Route::get('/stories',function(){
    data::$pageType='stories';
    data::$title='Stories';
    data::$description="All stories";
    $pagination=new Pagination([
        'results_per_page' =>data::$results_per_page,
        'query' =>'SELECT * FROM stories ORDER BY id DESC',
        'page'=>(int)data::$page
     ]);
     if($pagination->number_of_result){
        data::$stories=$pagination->get();
        data::$number_of_pages=$pagination->number_of_pages();
     }else{
        data::$error="No stories";
     }
});
Route::get('/stories/{story-id}',function($param){
    data::$pageType='singleStory';
    $story_id=$param['story-id'];
    $id=explode("-",trim($story_id));
    $id=trim(end($id));
    $story=db::select('SELECT * FROM stories WHERE id=?',[$id]);
    if($story){
        $story=$story[0];
        $items=db::select('SELECT * FROM files WHERE story_uid=?',[$story['uid']]);
        data::$story=$story;
        data::$title=data::$story['title'];
        data::$description='This web story provides you with details on '.data::$story['title'];
        data::$story['items']=$items;
        data::$storyThumbnail='uploads/'.$items[0]['name'];
        $preStory=db::select('select id,title from stories where id = (select min(id) from stories where id > ?) limit 1 ',[$story['id']]);
        $nextStory=db::select('select id,title  from stories where id = (select max(id) from stories where id < ?)  limit 1',[$story['id']]);
        if($nextStory){
            $slug=trim(join('-',explode(" ",$nextStory[0]['title']))).'-'.$nextStory[0]['id'];
            data::$nextStoryLink='stories/'.$slug;
        }
        if($preStory){
            $slug=trim(join('-',explode(" ",$preStory[0]['title']))).'-'.$preStory[0]['id'];
            data::$preStoryLink='stories/'.$slug;
        }
      
    }else{
        data::$title='404 not found';
        data::$pageType='error';
        data::$error='story not found';
    }
});
Route::get('*',function(){
    data::$title='404 not found';
    data::$pageType='error';
    data::$error='Page not found';
});
Route::get('/2023/03/virtual-dom-diffing-algorithm.html',function(){
    header("Location: /articles/Virtual-Dom-diffing-algorithm-implementation-in-Vanilla-JS-1");
    die();
});

if(data::$posts){
    if(data::$page<data::$number_of_pages){
        data::$nextPage=data::$page+1;
        data::$prePage=(int)data::$nextPage-2;
    }
 }

if(data::$pageType=='error'){
    header('HTTP/1.0 404 Not Found', true, 404);
}
?>
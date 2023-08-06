<?php 
   function post_format($post){
       $slug=trim(join('-',explode(" ",$post['title']))).'-'.$post['id'];
       $date=formats::date($post['date']);
       $date="<p>".$date['day']." ".substr($date['month'],0,3)."<span> ".$date['year']."</span></p>";
       $content=$GLOBALS['Parsedown']->text($post['content']);
       $tagsList=explode(",",$post['tags']);
       $snipped=$post['description'];
       if($snipped==""){
         $snipped=substr(strip_tags($content),0,150)."...";
       }
       $tags=[];
       foreach($tagsList as $tag){
            $tag=trim($tag);
            array_push($tags,"<a href='topics/$tag' class='tag'>$tag</a>");
       }
       return [
           'id'=>$post['id'],
           'slug'=> $slug,
           'date'=>$date,
           'title'=>$post['title'],
           'readingTime'=>fns::readingTime($post['content']).' min read',
           'snipped'=>$snipped,
           'tags'=>join(', ',$tags),
           'content'=> $content,
           'pinned'=>$post['pinned'],
       ];
   }
function getFileType($str){
    $video=["3g2","3gp","aaf","asf","avchd","avi","drc","flv","m2v","m3u8","m4p","m4v","mkv","mng","mov","mp2","mp4","mpe","mpeg","mpg","mpv","mxf","nsv","ogg","ogv","qt","rm","rmvb","roq","svi","vob","webm","wmv","yuv"];
    $extention=explode(".",trim($str));
    $extention=trim(end($extention));
    $type="image";
    if (in_array($extention, $video)){
        $type="video";
    }
    return $type;
   }
?>
<?php 
function stories_template($story){
    $item=db::select('select * from files where story_uid=?',[$story['uid']]);
    $item=$item[0];
    $slug=trim(join('-',explode(" ",$story['title']))).'-'.$story['id'];
    ?>
    <div class="story">
        <a href="stories/<?php echo $slug;?>" target="_self">
        <?php if(getFileType($item['name'])=='image'):?>
            <img src="uploads/<?php echo   $item['name'];?>" alt="">
        </a>
        <?php else:?>
            <video id="video-<?php $item['id'];?>" muted loop>
                <source src="uploads/<?php echo  $item['name'];?>" ></source>
            </video>
        </a>
            <button type="button" class="play-btn" onclick="play('video-<?php $item['id'];?>')">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="white" d="M16.3944 11.9998L10 7.73686V16.2628L16.3944 11.9998ZM19.376 12.4158L8.77735 19.4816C8.54759 19.6348 8.23715 19.5727 8.08397 19.3429C8.02922 19.2608 8 19.1643 8 19.0656V4.93408C8 4.65794 8.22386 4.43408 8.5 4.43408C8.59871 4.43408 8.69522 4.4633 8.77735 4.51806L19.376 11.5838C19.6057 11.737 19.6678 12.0474 19.5146 12.2772C19.478 12.3321 19.4309 12.3792 19.376 12.4158Z" fill="#000"></path></svg>
            </button>
            <button type="button" class="pause-btn" style="display:none" onclick="pause('video-<?php $item['id'];?>')">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="white" d="M6 5H8V19H6V5ZM16 5H18V19H16V5Z" fill="#000"></path></svg>
            </button>
        <?php endif;?>
        <div class="caption"><?php echo  $story['title'];?></div>
    </div>
<?php }?>
<?php 
function posts_template($post){?>
   <article class="post grid1 relative">
      <div class=" col1">
         <div class="v-line"></div>
      </div>
      <div class="col2">
         <span class="post_tags">In <?php echo $post['tags'];?></span>
         <header>
            <a href="articles/<?php echo $post['slug'];?>" class="post-link">
               <h2><?php echo $post['title'];?></h2>
            </a>
            <small><?php echo $post['date'];?></small>
         </header>
         <p class="snipped"><?php echo $post['snipped'];?></p>
      </div>
   </article>
<?php }?>
<?php
function storyDescription($string){
    if(empty($string) or $string==null){
        return [];
    }
    $links=[];
    if(preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $string, $match)){
        foreach($match[0] as $m){
            $string=str_replace($m,"",$string);
            array_push($links,$m);
        }
    }
    return [
        'text'=>$string,
        'outlink'=>  $links[0]
    ];
}
?>
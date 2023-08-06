<?php
   session_start();
   session_regenerate_id();
   require_once('class/Parsedown.php');
   $Parsedown=new Parsedown();
   $Parsedown->setSafeMode(true);
   include('db/data.php');
   require_once('class/minify.php');
   TinyMinify::minifyCurrentFile();
   include('functions.php');
   $user=[
      'name'=>"",
      'email'=>"",
      'comments'=>[]
   ];
  if(isset($_COOKIE['cin'])){
      $user=json_decode($_COOKIE['cin'],true);
  }
  $passToken='activate_admin12';
  if(isset($_GET[$passToken])){
   $_SESSION['allowadmin']=true;
  }
  ?>

<!DOCTYPE html>
<html lang="en">
   <head>
      <base href="/">
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title><?php echo data::$title;?></title>
      <meta content='<?php echo data::$description;?>' name='description'/>
      <meta content='HTML,CSS, SQL, JavaScript, How to, PHP,Virtual DOM, how react works, jQuery, Bootstrap, Colors, MySQL Programming, Web Development, Examples, Learn to code, Source code,Tips, Website' name='Keywords'/>
      <meta content='assets/logo.png' property='og:image'/>
      <meta content='image/png' property='og:image:type'/>
      <meta content='100' property='og:image:width'/>
      <meta content='100' property='og:image:height'/>
      <meta content='<?php echo data::$title;?>' property='og:title'/>
      <meta content='<?php echo data::$description;?>' property='og:description'/>
      <link rel="icon" type="image/x-icon" href="assets/logo.png">
      <link href='css/github-dark.min.css' rel='stylesheet'/>
      <link rel="stylesheet" type="text/css" href="css/main.css?i=13">
      <script src='js/simple.js'></script>
      <script src='js/spa.js'></script>
      <script src='js/highlight.min.js'></script>
      <?php if(data::$pageType=='singleStory'):?>
      <meta name="twitter:card" content="summary_large_image">
      <meta name="twitter:image" content="<?php echo data::$storyThumbnail;?>">
      <meta name="twitter:image:alt" content="<?php echo data::$title;?>">
      <!-- Google tag (gtag.js) -->
      <script async src="https://www.googletagmanager.com/gtag/js?id=G-G5WHF5KQGD"></script>
      <!-- amp stories -->
      <script rel="preload" async src="https://cdn.ampproject.org/v0.js"></script>
      <script  rel="preload" async custom-element="amp-story-captions" src="https://cdn.ampproject.org/v0/amp-story-captions-0.1.js"></script>
      <!-- ## Setup -->
      <!-- AMP Stories are written using AMPHTML and they use their own AMP extension: `amp-story`. The first step is to import the `amp-story` in the header. -->
      <script rel="preload" async custom-element="amp-story" src="https://cdn.ampproject.org/v0/amp-story-1.0.js"></script>
      <!-- AMP Stories can make use of other AMP extensions such as `amp-video`. However, AMP Stories support only a subset of the available AMP extensions. You can find a full list of the supported extensions [here](/content/amp-dev/documentation/components/reference/amp-story.md#children-of-amp-story-grid-layer). -->
      <script rel="preload" async custom-element="amp-video" src="https://cdn.ampproject.org/v0/amp-video-0.1.js"></script>
      
      <style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
      <!-- Stories can be styled using CSS: -->
      <style amp-custom>
         amp-story {
         z-index: 500;
         font-family: -apple-system, BlinkMacSystemFont, "Segoe UI ", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji ", "Segoe UI Emoji ", "Segoe UI Symbol ";
         }
         amp-story-page * {
         text-align: center;
         }
         [template=thirds] {
            padding: 0px;
         }
         amp-story .text{
            color: white;
            mix-blend-mode: difference;
            height:max-content;
            text-transform:capitalize;
            font-size:18px;
            text-transform:capitalize;
            font-weight:500;
            position: absolute;
            bottom:10px;
            width: 100%;
            padding:20px;
         }
    </style>
    <?php endif;?>
   </head>
   <body>
      <noscript>
         <center><p>JavaScript is turned off in your browser!</p></center>
         <!-- <style>
          main amp-story{display:none}
         .Loading-your-story,.singleStory{
            display:none;
         }
         .pagination .col2{
            padding-top:35px;
         }
         .pagination .col2:after{
            content:"Cannot load your story! :(";
            display:block;
            font-weight:500;
         }
         @media (max-width:40rem){
            .pagination {
               border-top:1px solid var(--dim-grey);
               }
            }
                              
         </style> -->
      </noscript>
      <div class="loader" style="display:none"></div>
      <div id="root">
         <nav>
            <div class="logo"><a href="/">Codegleam</a></div>
            <div class="col2">
               <div class="links">
               <a href="stories" title="stories">Stories</a>
               </div>
               <div class="buttons">
                  <button>
                  <label class="switch">
                  <input type="checkbox">
                  <div class="round">
                     <div class="moon"><?php include('assets/moon.svg');?></div>
                     <div class="sun"><?php include('assets/sun.svg');?></div>
                  </div>
                  </label>
                  </button>
               </div>
            </div>
         </nav>
         <main>
            <?php if(data::$pageType=='index'):?>
               <!-- author info -->
            <div class="author_info always-grid">
               <div class="relative col1">
                  <div class="v-line"></div>
                  <a href="stories" title="stories">
                     <div class="profile_img">
                        <img src="assets/logo.png" alt="">
                     </div>
                  </a>
               </div>
               <div class="relative col2">
               <div class="h-line"></div>
               <div class="text">A Personal blog by <a href="https://twitter.com/Joydeep25220381" target="_blank">Joydeep Bhowmik</a>.<br> I will teach you cool coding stuffs.</div>
               </div>
            </div>


            <!-- pinnedpost -->
            <?php if(data::$pinnedPosts):?> 
            <div class="pinned">
               <span class="pinned-label"><?php include('assets/pinned.svg');?>Pinned</span>
               <?php foreach(data::$pinnedPosts as $post):
                  $post=post_format($post);?>
               <?php posts_template($post);?>
               <?php endforeach;?>
            </div>
               <?php endif;?>
            <?php endif;?>


            <?php if(data::$pageType!='index'):?>
               <!-- header title -->
            <div class="header always-grid">
               <div class="col1 relative ">
                  <div class="round"></div>
                  <div class="h-line"></div>
                  <div class="v-line"></div>
               </div>
               <div class="col2">
                  <div class="topic-name">
                  <?php switch(data::$pageType){
                        case 'singleTopic':
                           echo "Topic  — <span style='text-transform:capitalize'>".data::$topicName."</span>";
                        break;
                        case 'article':
                           echo 'Atricle';
                        break;
                        case 'error':
                           echo "404 not found";
                        break;
                        case 'stories':
                           echo 'Stories';
                        break;
                        case 'singleStory':
                           echo '<p class="Loading-your-story">Loading...</p>
                           <style>
                           .singleStory{  
                              border-top-right-radius: var(--default-border-radius);
                              border-top-left-radius: var(--default-border-radius);
                              border-left:1px solid var(--dim-grey);
                              border-right:1px solid var(--dim-grey);
                           }
                           .singleStory .col2{
                              height:10vh;
                           }
                           .singleStory .col2:after{
                              content:"Loading your story..";
                              display:block;
                              font-weight:500;
                              text-align:center;
                           }
                           @media (max-width:40rem){
                              .singleStory{
                                 border-top:1px solid var(--dim-grey);
                              }
                           }
                           </style>
                           <noscript>
                           <p>Failed!!!</p>
                           </noscript>
                           ';
                           break;
                        }?>
                  </div>
               </div>
            </div>
            <?php endif;?>

            <!-- all available posts -->
            <?php if(data::$posts) foreach(data::$posts as $post):
               $post=post_format($post);?>
            <?php posts_template($post);?>
            <?php endforeach;?>


            <?php if(data::$pageType=='article'):
               $post=post_format(data::$post)
               ?>
               <!-- article -->
               <article class="post single grid1 relative">
                  <div class="col1">
                     <div class="v-line"></div>
                  </div>
                  <div class="col2 ">
                  <?php 
                  if(isset($_SESSION['admin']) && $_SESSION['admin']==1):?>
                     <div class="manage" post-id="<?php echo $post['id'];?>">
                        <button type="button" class="pin" ><?php if($post['pinned']==0){echo 'pin';}else{echo 'unpin';}?></button>
                        <button type="button" class="delete">Delete</button>
                        <a href="admin/?edit=<?php echo $post['id'];?>" target="_blank"><button type="button" class="edit">Edit</button></a>
                     </div>
                  <?php endif;?>
                  <span class="post_tags">In <?php echo $post['tags'];?></span>
                  <header>
                     <h1><?php echo $post['title'];?></h1>
                     <small><?php echo $post['date'];?></small>
                  </header>
                  <div class="content markdown"><?php echo $post['content'];?></div>
                  <div class="comment-box" page-id="<?php echo $post['id'];?>" parent-id="0">
                  <b class="heading">Write a comment</b>
                  <span class="err"></span>
                  <div class="name_email">
                     <input type="text" name="name" placeholder="Name*" value="<?php echo $user['name'];?>">
                     <input type="email" name="email" placeholder="Email*" value="<?php echo $user['email'];?>">
                  </div>
                  <textarea name="comment" placeholder="Comment* (markdown supported)"></textarea>
                  <label class="checkbox" for="remember">
                     <input type="checkbox" checked="true" id="remember" name="remember"> Remember me ( allow delete )
                  </label>
                  <button type="submit">Post a comment</button>
               </div>
               <div class="comments"></div>
               <button type="button" class="load-cmn-btn" page='1'>Load Comments</button>
                  </div>
               </article>
            <?php endif;?>

            <?php if(data::$pageType=='stories'):?>
               <!-- stories -->
               <div class="grid1 stories">
                  <div class="col1">
                     <div class="v-line"></div>
                  </div>
                  <div class="col2">
                     <?php foreach(data::$stories as $story):?>
                        <?php stories_template( $story);?>
                     <?php endforeach;?>
                  </div>
               </div>
            <?php endif;?>
            
            <?php if(data::$pageType=='singleStory'):?>
               <!-- single story -->
               <div class="grid1 singleStory">
                  <div class="col1">
                     <div class="v-line"></div>
                  </div>
                  <div class="col2">
                  <?php if(isset($_SESSION['admin']) && $_SESSION['admin']==1):?>
                     <a href="admin/?story=<?php echo data::$story['id'];?>" class="edit-story" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M12.6844 4.02535C12.4588 4.00633 12.2306 3.99658 12 3.99658C7.58172 3.99658 4 7.5783 4 11.9966C4 16.4149 7.58172 19.9966 12 19.9966C16.4183 19.9966 20 16.4149 20 11.9966C20 11.766 19.9902 11.5378 19.9711 11.3122C19.8996 10.4644 19.6953 9.64408 19.368 8.87332L20.8682 7.37102C21.2031 8.01179 21.4706 8.69338 21.6613 9.40637C21.8213 10.0062 21.9258 10.6221 21.9723 11.2479C21.9907 11.4951 22 11.7447 22 11.9966C22 17.5194 17.5228 21.9966 12 21.9966C6.47715 21.9966 2 17.5194 2 11.9966C2 6.47373 6.47715 1.99658 12 1.99658C12.2518 1.99658 12.5015 2.00589 12.7487 2.02419C13.3745 2.07069 13.9904 2.17529 14.5898 2.33568C15.3032 2.52597 15.9848 2.79347 16.6256 3.12837L15.1247 4.62922C14.3525 4.30131 13.5321 4.09695 12.6844 4.02535ZM20.4853 2.09709L21.8995 3.5113L12.7071 12.7037L11.2954 12.7062L11.2929 11.2895L20.4853 2.09709Z" fill="white"></path></svg>
                     </a>
                  <?php endif;?>
                  <!-- next are pre stories -->
                  <div class="story-nav">
                  <?php
                  if(!empty(data::$preStoryLink)){
                     echo '
                     <a href="'.data::$preStoryLink.'" class="pre">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M12.9999 7.82843V20H10.9999V7.82843L5.63589 13.1924L4.22168 11.7782L11.9999 4L19.778 11.7782L18.3638 13.1924L12.9999 7.82843Z" fill="#000"></path></svg>
                     </a>';
                  }
                  if(!empty(data::$nextStoryLink)){
                     echo '
                     <a href="'.data::$nextStoryLink.'" class="next">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M12.9999 16.1716L18.3638 10.8076L19.778 12.2218L11.9999 20L4.22168 12.2218L5.63589 10.8076L10.9999 16.1716V4H12.9999V16.1716Z" fill="#000"></path></svg>
                     </a>';
                  }
                  ?>
                  </div>
               <amp-story standalone title="<?php echo data::$title;?>">
               <?php foreach(data::$story['items'] as $story):?>
                  <amp-story-page id="<?php echo $story['id'];?>">
                     <amp-story-grid-layer template="fill">
                     <?php if(getFileType($story['name'])=='video'):?>
                        <amp-video autoplay loop
                              width="720"
                              height="960"
                              layout="responsive">
                              <source src="uploads/<?php echo $story['name'];?>" type="video/mp4">
                        </amp-video>
                        <?php else:?>
                        <amp-img src="uploads/<?php echo $story['name'];?>"
                           width="720" height="1280"
                           layout="responsive">
                        </amp-img>
                     <?php endif;?>
                     </amp-story-grid-layer>
                     <amp-story-grid-layer template="thirds">
                        <?php 
                        $description=storyDescription($story['description']);
                        if(!empty($description['text'])):?>
                        <div class="text" grid-area="lower-third">
                           <?php echo $description['text'];?>
                        </div>
                        <?php endif;?>
                     </amp-story-grid-layer>
                     <?php if(!empty($description['outlink'])):?>
                        <amp-story-page-outlink layout="nodisplay">
                              <a href="<?php echo $description['outlink'];?>" title="swipe up"></a>
                        </amp-story-page-outlink>
                     <?php endif;?>
                  </amp-story-page>
               <?php endforeach;?>
               </amp-story>
                  </div>
            </div>
            <?php endif;?>
            <!-- pagination -->
            <div class='pagination grid1'>
               <div class="col1 relative">
                  <div class="v-line"></div>
               </div>
               <div class="col2">
                  <?php if(!empty(data::$error)){
                     echo '<h1> '.data::$error.'</h1>
                     <style>
                     @media (max-width:40rem){
                        .pagination{
                           border-top:1px solid var(--dim-grey);
                        }
                     }
                     .pagination .col2{
                        padding:20px;
                     }
                     </style>
                     ';
                  }?>
                <?php 
                for($p = 1; $p<= data::$number_of_pages; $p++) {  
                  $active="";
                  if($p==data::$page){
                     $active='class="active"';
                  }
                  if($p==1){
                     echo '<a href = "'.data::$pathName.'"'.$active.' >' . $p . ' </a>';  
                  }
                  else{
                     echo '<a href = "'.data::$pathName.'?page=' . $p . '"'.$active.' >' . $p . ' </a>';  
                  }
                }
                ?>
               </div>
            </div>
         </main>    
         <footer class="always-grid">
            <div class="col1">
               <div class="round"></div>
               <div class="h-line"></div>
               <div class="v-line"></div>
            </div>
            <div class="col2">
               <div class="socials">
                   <a href="https://twitter.com/Joydeep25220381" target="_blank" class="twitter" title="twitter"><?php include('assets/twitter-line.svg')?></a>
                  <a href="https://github.com/joydeep-bhowmik" target="_blank" class="github" title="github"><?php include('assets/github.svg')?></a>
                  <a href="https://follow.it/code-gleam" target="_blank" class="rss" title="rss"><?php include('assets/rss-line.svg')?></a>
               </div>
            </div>
         </footer>
      </div>
   <style>
      
      <?php if(data::$pageType=='index'){
         echo '.logo{
             font-size: 2rem;
             font-weight:bolder;
         }
         .logo a:-webkit-any-link {
             color:var(--black)
         }
         ';
         }else{
         echo '.logo{
             font-size: 1.5rem;
             font-weight:bolder;
            
         }
         .logo a:-webkit-any-link {
            background: -webkit-linear-gradient(45deg, blue,red) border-box;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
         }
         ';
         }?>
   </style>
<?php if(isset($_SESSION['admin']) && $_SESSION['admin']==1):?>
   <script>
  s(document).on('click','.manage .pin',function(){
   var postId=s('.manage').attr('post-id');
    var type='pin';
    var succesMsg='unpin';
    if(this.innerHTML=='unpin') {
      type='unpin';
      succesMsg='pin';
    }
    
    s.ajax({
      url:'admin/api',
      method:'get',
      data:{
        id:postId,
        type:type,
      },
      success:function(res){
         console.log(postId,type)
        if(res==1){
       
          s('.pin').html(succesMsg);
        }
      }
    })
  });
  s(document).on('click','.manage .delete',function(){
   var postId=s('.manage').attr('post-id');
    if(!confirm("Are you sure? this action cannot be undo")) return;
    s.ajax({
      url:'admin/api',
      method:'get',
      data:{
        id:postId,
        type:'delete',
      },
      success:function(res){
         console.log(res)
        if(res==1){
          s('.delete').html('deleted!');
        }
      }
    })
  });
   </script>
<?php endif;?>
<?php if(data::$pageType!='singleStory'):?>
   <script src="js/main.js"></script>
<?php endif;?>
   </body>
</html>
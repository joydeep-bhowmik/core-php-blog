
<?php
session_start();
session_regenerate_id();
if(!isset($_SESSION['admin']) && $_SESSION['admin']!=1){
    header('location:login');
}
if(isset($_GET['logout'])){
    session_unset();
    session_destroy();
    header('location:login');
}
include('../db/phphelper.php');
include('../db/db_config.php');
include('../functions.php');
$includable='add_post.php';
$page='home';              
if(isset($_GET['new']) or isset($_GET['edit'])){
    $includable='add_post.php';                  
}
if(isset($_GET['story'])){
    $includable='add_story.php';   
    $page='story' ;              
}
function msg($msg){
echo '<div class="msg">'.$msg.' <span class="close">&#10005;</span></div>';
}
$pathname=strtok($_SERVER["REQUEST_URI"], '?');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<base href="/admin">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin-<?php echo $_SERVER['SERVER_NAME'];?></title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.2.0/fonts/remixicon.css" rel="stylesheet">
    <script src='./js/simple.js'></script>
</head>
<body>
    <main class="grid">
            <div class="col1">
                <div class="admin-panel-heading">
                    <h1>Admin panel</h1>
                </div>
                <div class="links">
                    <a href="<?php echo $pathname;?>" class="<?php echo ($page == "home" ? "active" : "")?>">
                    <?php if(isset($_GET['edit'])){echo 'Edit';}else{echo 'Create';}?>
                    </a>
                    <a href="<?php echo $pathname;?>?story" class="<?php echo ($page == "story" ? "active" : "")?>">new story</a>
                    <a href="" target="_blank">View Blog</a>
                    <a href="<?php echo $pathname;?>?logout">Logout</a>
                </div>
            </div>
            <div class="col2">
                <?php include($includable);?>
            </div>
    </main>
</body>
<style>
    :root{
    --border:1px solid rgb(233, 231, 231);
    --front:white;
    --rounded-border:8px;
    --extra-light-grey:#f6f8fa;
    --light-grey:#ccc;
    --dim-grey:#dee6ed;
    --grey: #b3b3b3;
    --blue:#1d9ff0;
    --black:rgb(15 23 42/1);
    --small-fonts:15px;
    --blueish-grey:#8a9ab0;
    --active:#1f2f42;
    --main-font:-apple-system,BlinkMacSystemFont,"Segoe UI",Helvetica,Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji";
    --fs-xs:16px;
    --fs-s:18px;
    --fs-r: 1.4rem;
    --fs-m:1.87rem;
    --fs-l:2.5rem;
    --fs-xl:3.25rem;
    --max-width:700px;
   --secondary: var(--extra-light-grey);
   --textLink:var(--blue);
   --primary:var(--black);
   --default-border-radius:5px;
}
    *{
        box-sizing:border-box;
        padding:0;
        margin:0;
        font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Helvetica,Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji";
    }
    html,body{
		width: 100%;
	}
    body{
        background-color:#f8f8f8;
        overflow-x:hidden;
    }
    h1,h2{
        text-transform:capitalize;
    }
    main{
        background-color:var(--front);
        display:grid;
        grid-template-columns:320px auto;
        min-height:100vh;
    }
    main>div{
        padding:10px;
    }
    main .col1{
        background-color:var(--black);
        max-width:100%;
    }
    main .col2{
        padding:20px;
    }
    input{
        display: block;
    }
    main .admin-panel-heading h1{
        color:var(--front);
        margin:10px;
        font-size:var(--fs-l);
        color:var(--black);
    }
    main .admin-panel-heading{
        background-color:var(--dim-grey);
        padding:15px;
        border-radius:var( --default-border-radius);
    }
    main .col1 a{
        display:block;
        width:100%;
        padding:10px 20px;
        text-decoration:none;
        text-transform:capitalize;
        font-size:var(--fs-s);
        color:var(--blue);
        font-weight:500;
        margin-top:10px;
        border-radius:var( --default-border-radius);
    }
    main .col1 a.active{
        color:var(--black);
        background-color:var(--front)
    }

    input,textarea{
        display:block;
        width:100%;
        padding:10px;
        border:1px solid #e6e6e6;
        margin-top:10px;
        margin-bottom:10px;
    }
    textarea[ placeholder="Description"]{
        min-height:10vh;
    }
    textarea[ placeholder="Content *"]{
        min-height:50vh;
    }
    input[placeholder="Title"]{
        font-size:50px;
        font-weight:bold;
        text-transform:capitalize;
    }

     button[type="submit"]{
        background-color:blue;
        font-weight:bold;
        color:white;
        margin-right:5px;
        padding:8px 10px;
        outline:0;
        border:0px;
        border-radius:5px;
        min-width:100px;
    }

    
    button[type="submit"]{
        text-transform:capitalize;
    }
    .msg{
        background-color:#66ffc2;
        font-size:var(--fs-s);
        text-transform:capitalize;
        padding:16px 20px;
        color:white;
        font-weight:500;
    }
    .msg .close{
        cursor:pointer;
        float:right;
    }
    @media (max-width:60rem){
		main{
			display:block;
		}
      
	}
</style>
<script>
    s(document).on('click','.msg .close',function(){
        s('.msg').remove();
    })
</script>
</html>
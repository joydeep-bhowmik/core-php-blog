
<?php
session_start();
session_regenerate_id();
if(!isset($_SESSION['allowadmin'])){
    header('location:/404');
    exit();
}
if(isset($_SESSION['admin']) && $_SESSION['admin']==1){
    header('location:dashboard');
}
include('../db/phphelper.php');
include('../db/db_config.php');
$admin=$password="";
if(isset($_POST['submit'])){
    
    $admin=sn::test_input($_POST['admin']);
    $password=sn::test_input($_POST['password']);
    $data=db::select('select * from admin where admin=?',[
        $admin
    ]);
    if($data){
        $hash =  $data[0]['password'];
        if (password_verify($password, $hash)) {
            $_SESSION['admin']=$data[0]['id'];       
            if(!empty($_SESSION['admin'])){
                header('location: dashboard');
            }
        } else {
            $err='Invalid username or password' ;
        }
    }else {
        $err='Invalid username or password' ;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin/login</title>
</head>
<body>
    <center><h1>Admin Login</h1></center>
    <form action="" method="post">
        <?php if(isset($err)):?>
            <div class="err"><?php echo $err;?> <b style="float:right;cursor:pointer" onclick="this.parentNode.remove()">&#10005;</b></div>
        <?php endif;?>
        <input type="text" autocomplete="off" name="admin" value="<?php echo $admin;?>" placeholder="Admin">
        <input type="password" autocomplete="off" name="password" value="<?php echo $password;?>" placeholder="Password">
        <button type="submit" name="submit">login</button>
    </form>
</body>
<style>
    *{
        box-sizing:border-box;
        font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Helvetica,Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji";
    }
    form{
        max-width:400px;
        margin:auto;
    }
    input,button{
        padding:10px;
        display:block;
        width: 100%;
        margin:10px 0px 10px 0px;
        border:1px solid #ebebeb;
    }
    button{
        font-weight:bolder;
        text-transform:capitalize;
    }
    body{
        padding:10px;
    }
    .err{
        padding:10px;
        background-color:#f9edbe;
        color:red;
    }
</style>
</html>
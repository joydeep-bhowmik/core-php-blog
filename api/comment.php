<?php
session_start();
session_regenerate_id();
include('../db/phphelper.php');
include('../db/db_config.php');
require_once('../class/Parsedown.php');
$admin=0;
if(isset($_SESSION['admin'])){
    $admin=$_SESSION['admin'];
}

$Parsedown=new Parsedown();
$Parsedown->setSafeMode(true);
function template($post,$type=null){
    $user=[
        'name'=>"",
        'email'=>"",
        'comments'=>[]
    ];
    if(isset($_COOKIE['cin'])){
        $user=json_decode($_COOKIE['cin'],true);
     }
    $date=formats::date($post['date']);
    if($date['year']==date("Y")){
        $date['year']="";
    }else{
        $date['year']=", ".$date['year'];
    }
    $date=substr($date['month'],0,3)." ".$date['day'].$date['year'];
    $text=$GLOBALS['Parsedown']->text($post['comment']);
    $md=$post['comment'];
    $parent='';
    if($post['parent_id']!=0){
        $parent_username=db::select('select * from comments where id=?',[$post['parent_id']]);
        if(!$parent_username){
            $parent_username="<i style='color:red'>Deleted user</i>";
        }
        else{
            $parent_username=$parent_username [0]['name'];
        }
        $parent='<p style="font-size:13px">@ '.$parent_username.'</p>';
    }
    $uid=$post['uid'];
    $editDelete='';
    if(in_array($uid,$user['comments']) || $type!=null || $admin==1){
        $editDelete='<span class="delete" comment-id="'.$post['id'].'" uid="'.$uid.'">DELETE</span>';
    }
    $str='
    <div class="comment" comment-id="'.$post['id'].'">
        <div class="md" style="display:none">'.$md.'</div>
        <div class="info"><span class="name">'.$post['name'].'</span> &bull; '.$date.'</div>
        <div class="text">'.$parent.$text.'</div>
        <span class="reply" comment-id="'.$post['id'].'">REPLY</span>'.$editDelete.'
    </div>';
    return $str;
}
if(!isset($_POST['page_id'],$_POST['type'])) exit('Access Denied');
$type=sn::test_input($_POST['type']);
$page_id=sn::test_input($_POST['page_id']);
$page=1;
if(isset($_POST['page'])){
    $page=$_POST['page'];
}
if($type=='add'){
    $remember=sn::test_input($_POST['remember']);
    $name=sn::test_input($_POST['name']);
    $email=$uid="";
    $email=sn::test_input($_POST['email']);
    if($remember=='true'){
        $uid=$email.time();
        $user=[
            'name'=>$name,
            'email'=>$email,
            'comments'=>[]
        ];
        if(isset($_COOKIE['cin'])){
            $user['comments']=json_decode($_COOKIE['cin'],true) ['comments'];
        }
        array_push($user['comments'],$uid);
        setcookie('cin', json_encode($user), time() + (360 * 86400 ), "/");
    }
    $comment=htmlspecialchars($_POST['comment']);
    $parent_id=sn::test_input($_POST['parent_id']);
    if(sn::empty($name,$comment,$parent_id,$page_id)) exit('E1');
    $insert=db::insert('INSERT INTO comments (name,email,comment,parent_id,page_id,uid) VALUES (?,?,?,?,?,?)',[
        $name,$email,$comment,$parent_id,$page_id,$uid
    ]);
    if($insert){
        $post=db::select('SELECT * FROM comments WHERE id=?',[$insert])[0];
        if($remember=='true'){
            echo template($post,'add');
        }else{
            echo template($post);
        }
    }

}
if($type=='all'){
    $rows=db::select('SELECT * FROM comments WHERE page_id=?',[$page_id]);
    if(!$rows) {
        echo '<br/><center>No comments</center>';
        return;
    };
    foreach($rows as $post){
        echo template($post);
    }
}
if($type=='delete'){
    $uid=sn::test_input($_POST['uid']);
    $rows=db::execute('DELETE FROM comments WHERE uid=?',[$uid]);
    if($rows){
        echo 'deleted';
    }else{
        echo 'E1';
    }
}
?>

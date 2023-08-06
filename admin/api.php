<?php
session_start();
session_regenerate_id();
if(!isset($_SESSION['admin']) && $_SESSION['admin']!=1){
    header('location:login');
}
include('../db/phphelper.php');
include('../db/db_config.php');
if(!isset($_GET['id'],$_GET['type'])) exit('Access Denied');

$id=sn::test_input($_GET['id']);
$type=sn::test_input($_GET['type']);
//pin
if($type=='pin'){
    $pinned=db::execute('UPDATE posts SET pinned=1 WHERE id=?',[$id]);
    if($pinned) {
        echo 1;
    }
    else {
        echo 0;
    }
}
//unpin
if($type=='unpin'){
    $pinned=db::execute('UPDATE posts SET pinned=0 WHERE id=?',[$id]);
    if($pinned) {
        echo 1;
    }
    else {
        echo 0;
    }
}
if($type=='delete'){
    $pinned=db::execute('DELETE FROM posts WHERE id=?',[$id]);
    if($pinned) {
        echo 1;
    }
    else {
        echo 0;
    }
}
?>
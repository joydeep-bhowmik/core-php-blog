
<?php
$title=$tags=$description=$content="";
if(isset($_POST['submit'])){
    if(isset($_POST['title'])){
        $title=sn::test_input($_POST['title']);
    }
    if(isset($_POST['tags'])){
        $tags=sn::test_input($_POST['tags']);
        $tags=preg_replace('/\s+/', '', $tags);
    }
    if(isset($_POST['description'])){
        $description=sn::test_input($_POST['description']);
    }
    if(isset($_POST['content'])){
        $content=html_entity_decode($_POST['content']);
    }
    if(sn::empty($title,$tags,$content)) {
        echo '* marked fields are mendatory';
    }else{
        if(isset($_GET['edit']) && !empty($_GET['edit'])){
            $update=db::execute('update posts set title=?,tags=?,description=?,content=? where id=?',[
                $title,$tags,$description,$content,(int)$_GET['edit']
            ]);
            if($update) msg("post updated");
        }else{
            $insert=db::insert('INSERT INTO posts(title,tags,description,content) VALUES(?,?,?,?)',[
                $title,$tags,$description,$content
            ]);
            if($insert) msg("Post added");
        }
    }
}
if(isset($_GET['edit']) && !empty($_GET['edit'])){
    $id=sn::test_input($_GET['edit']);
    $row=db::select('select * from posts where id=?',[$id])[0];
    $title=$row['title'];
    $tags=$row['tags'];
    $description=$row['description'];
    $content=$row['content'];
}
?>

<?php if(isset($_GET['edit'])){echo '<h1>Update</h1>';}else{echo '<h1>Add</h1>';};?>
<form action="" method="post" class="editor-form">
    <input type="text" name="title" placeholder="Title" autocomplete="off" value="<?php echo $title;?>">
    <input type="text" name="tags" oninput="this.value=this.value.toLowerCase().replace(/\s/g, '')" placeholder="Tags *" autocomplete="off" value="<?php echo $tags;?>">
    <textarea name="description" id=""  placeholder="Description" ><?php echo $description;?></textarea>
    <textarea name="content" id=""  class="editor-textarea" placeholder="Content *" ><?php echo $content;?></textarea>
    <div class="editor-tools">
        <button type="button" onclick="h2()"><i class="ri-heading"></i></button>
        <button type="button" onclick="bold()"><i class="ri-bold"></i></button>
        <button type="button" onclick="italic()"><i class="ri-italic"></i></button>
        <button type="button" onclick="code()"><i class="ri-code-s-slash-fill"></i></button>
        <button type="button" onclick="blockquote() "><i class="ri-double-quotes-l"></i></button>
        <button type="button" onclick="addlink()"><i class="ri-link"></i></button>
        <button type="button" onclick="addimage()"><i class="ri-image-line"></i></button>
        <div class="from-action">
            <button type="reset">Cancel</button>
            <button name="submit" type="submit">
                <?php if(isset($_GET['edit'])){echo 'update';}else{echo 'save';};?>
            </button>
        </div>
    </div>
</form>
<style>
    .editor-tools{
        display:flex;
    }
    .editor-tools button[type="button"]{
        background-color:transparent;
        outline:0;
        border:1px solid #e6e6e6;
        margin-right:5px;
        font-size:20px;
        padding:0 8px;
    }
    .from-action{
        width:max-content;
        margin-left:auto;
    }
    .from-action button{
        outline:none;
        border:0;
        background-color:transparent;
        padding:8px 20px;
        border-radius:5px;
    }
    @media (max-width:60rem){
        .editor-tools{
           display:block;
        }
        .from-action{
            width:max-content;
            margin-top:20px;
        }
	}
</style>

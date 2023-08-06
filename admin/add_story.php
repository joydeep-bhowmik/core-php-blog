<?php
function deleteStoryItem($a){
		$filename=db::select('SELECT * FROM files WHERE id=?',[$a]);
		if($filename ){
			$filename=$filename[0];
			if(!empty($filename['name']))
			{
				$filename=$filename['name'];
				if(unlink('../uploads/'.$filename)){
				db::execute('DELETE FROM files WHERE id=?',[$a]);
					}
			}
		}
}
function destroyStory($a){
	return db::execute('DELETE FROM stories WHERE id=?',[$a]);
}
$title="";
$id="";
$story="";
if(!empty($_GET['story'])){
	$id=sn::test_input($_GET['story']);
	$story=db::select('select * from stories where id=?',[$id]);
	if(!$story) {
		echo msg('story not found');
		return;
	}
	$story=$story[0];
	$title=$story['title'];
	$items=db::select('SELECT * FROM files WHERE story_uid=?',[$story['uid']]);
	if(!$items){
		destroyStory($story['id']);
		msg('Story deleted.');
		return;
	}
}
if(isset($_POST['submit'])){
	$title=sn::test_input($_POST['story-title']);
	if(!empty($title)){
		if(db::select('select * from stories where title=?',[$title]) && empty($story)){
			msg('title already exist');
			return;
		}
		$insertedId=[];
		$token=time();
		$err=false;
		$uid=time().$title;
		$files = array_filter($_FILES['file']['name']); //Use something similar before processing files.
		// Count the number of uploaded files in array
		$total_count = count($_FILES['file']['name']);
		// Loop through every file
		for( $i=0 ; $i < $total_count ; $i++ ) {
			   //The temp file path is obtained
			   $tmpFilePath = $_FILES['file']['tmp_name'][$i];
			   //A file path needs to be present
			   if ($tmpFilePath != ""){
				  //Setup our new file path
				$name=$token . $_FILES['file']['name'][$i];
				$newFilePath = "../uploads/".$name;
				$text=$_POST['text'][$i];
				  // File is uploaded to temp dir
				  if(move_uploaded_file($tmpFilePath, $newFilePath)) {
					 //Other code goes here
					 echo  '<p>'.$name.' uploaded';
					 if(!empty($_GET['story'])) $uid=$story['uid'];
					 $insert=db::insert('INSERT INTO files (name,description,story_uid) VALUES(?,?,?)',[
						$name,$text,$uid
					 ]);
					 if($insert){
						echo ' And recorded'."</p>";
						array_push($insertedId,$insert);
					 }else{
						$err=1;
						echo 'no';
						break;
					 }
				  }else{
					$err=1;
					break;
				  }
			   }
		}
		if(!$err and empty($_GET['story'])){
			$story=db::insert('INSERT INTO stories (uid,title) VALUES(?,?)',[
				$uid,$title
			 ]);
			 if($story){
				echo '<p> story added successfully</p>';
				header("Location:?story=".$story);
			 }
		}else if(!empty($_GET['story'])){
			header("Refresh:0");
		}
		else{
			foreach($insertedId as $id){
				deleteStoryItem($id);
			}
		}
		//header("Refresh:0");
	}
}
if(isset($_POST['delete'])){
	$checked=[];
	if(isset($_POST['checked'])){
		$checked=$_POST['checked'];
	}
	if(!empty($checked)){
		foreach($checked as $ck){
			deleteStoryItem($ck);
		}
	}
	header("Refresh:0");
}
if(isset($_POST['destroy'])){
	foreach($items as $it){
		deleteStoryItem( $it['id']);
	}
	destroyStory($story['id']);
	msg('Story deleted.');
	return;
}
?>
<form action="" method="post" class="story" enctype="multipart/form-data">
	<h1><?php if( empty($_GET['story'])){echo 'add';}else{echo 'edit';}?></h1>
	<input type="text" placeholder="Title" name="story-title" value="<?php echo $title;?>">
	<?php 
	if(!empty($_GET['story'])):
		foreach($items as $it){
			$file='<img src="uploads/'.$it['name'].'"/>';
			if(getFileType($it['name'])!='image'){
				$file='<video><source src="uploads/'.$it['name'].'"/></video>';
			}
			echo '
			<div class="story-item">
			'.$file.'
			<p>'.$it['description'].'</p>
			<input type="checkbox" name="checked[]" id="" value="'.$it['id'].'">
			</div>';
		}
	endif;?>
	<input type="file" name="file[]" accept="video/*,image/*" id="attachment" multiple/>
	<div class="preview"></div>
	<div class="buttons">
	<button type="submit" name="submit" formaction="" value="save">save</button>
	<button type="submit" name="delete" formaction="" class="delete" value="delete">delete</button>
	<button type="submit" name="destroy"  formaction="" onclick="return confirm('destroy the story?')" value="destroy" class="delete">destroy</button>
	</div>
</form>
<script>
const dt = new DataTransfer();

s("#attachment").on('change', function(e){
	for(var i = 0; i < this.files.length; i++){
		let preview=s('.story .preview');
		let file=this.files.item(i);
		if(file.type.includes('video')){
			preview.append(temp(file,i,'video'))
		}else{
			preview.append(temp(file,i))
		}
	};
	for (let file of this.files) {
		dt.items.add(file);
	}
	this.files = dt.files;
	s('.file-preview .delete').on('click',function(){
		let name = this.getAttribute('name');
		this.parentNode.remove();
		for(let i = 0; i < dt.items.length; i++){
			if(name === dt.items[i].getAsFile().name){
				dt.items.remove(i);
				continue;
			}
		}
		document.getElementById('attachment').files = dt.files
		console.log(dt.files)
	});
	console.log(dt.files)
});
function temp(file,index,type=null){
	let filetype=`
	<img src="${URL.createObjectURL(file)}"/>
	`;
	if(type){
		filetype=`
		<video controls>
			<source src="${URL.createObjectURL(file)}" type="video/mp4">
			Your browser does not support the video tag.
		</video>`;
	}
	return(`
	<div class="file-preview">
		<span class="delete" name="${file.name}">&#10005;</span>
		<div class="file">
			${filetype}
		</div>
		<textarea class="text" placeholder="Description" name="text[]"></textarea>
	</div>
	`)
}

</script>
<style>
	<?php if(empty($_GET['story'])){
		echo '.story .delete{display:none}';
	}?>

	.story .preview{
		display:flex;
		flex-wrap:wrap;
		gap:10px;
	}
	.story .preview .file-preview{
		background-color:var(--dim-grey);
		height:240px;
		width:200px;
		border-radius:var(--default-border-radius);
		padding:40px 0px 10px 0px;
		position:relative;
	}
	.story .file-preview .file{
		overflow:hidden;
		height:120px;
		width:100%;
		display:flex;
		justify-content:center;
		align-items:center;
		background-color:#000;
	}
	.story .preview .file-preview .delete{
		padding:10px;
		cursor:pointer;
		font-weight:bolder;
		position:absolute;
		top:0px;
		left:0px;
		background-color:var(--dim-grey);
		border-radius:50%;
		height:40px;
		width: 40px;
		display:flex;
		justify-content:center;
		align-items:center;
	}
	.story .file-preview textarea,.story .file-preview input{
		background-color:transparent;
		border:0;
		outline:0;
		resize:none;
		
	}
	.story .file-preview textarea{
		height:50px;
	}
	.story .file-preview  img,.story .file-preview video{
		height: 100%;
	}
	.story button{
		margin-top:10px;
		padding:10px 20px;
		font-size:18px;
		cursor:pointer;
		color:white;
		font-weight:500;
		border:0;
		outline:0;
		border-radius:var(--default-border-radius);
	}
	.story .delete{
		background-color:red;
	}
	.story .delete:last-of-type{
		margin-left:auto;
	}
	label[for="attachment"]{
		display:block;
		width: 100%;
		border:1px solid red;
	}
	.story-item{
		display:grid;
		grid-template-columns:100px auto 50px;
		border-radius:var(--default-border-radius);
		border:1px solid var(--dim-grey);
		padding:10px;
		margin-bottom:10px;
		width: 100%;
	}
	.story-item img,.story-item video{
		height:50px;
		width: 50px;
		border-radius:50%;
		border:1px solid #ccc;
		background-color:#f8f8f8;
	}
	.story-item p{
		display:flex;
		align-items:center;
		justify-content:center;
		font-weight:500;
		font-size:18px;
	}

    @media (max-width:60rem){

		.story button{
			display:block;
			width: 100%;
		}
		.story .delete:last-of-type{
		margin-left:0px;
	}
	}
</style>
<?php
require_once '../includes/functions.php';
require_admin_login();


if($_SERVER['REQUEST_METHOD']==='POST'){
$id=intval($_POST['id'] ?? 0);
$name=$_POST['name'] ?? '';
$position=$_POST['position'] ?? '';
$bio=$_POST['bio'] ?? '';
$photo=upload_image('photo','../assets/staff/');


if(!$name || !$position) $error='Name & Position required.';
else{
if($id>0){
if($photo){
$stmt=mysqli_prepare($conn,"UPDATE staff SET name=?,position=?,bio=?,photo=? WHERE id=?");
mysqli_stmt_bind_param($stmt,'ssssi',$name,$position,$bio,$photo,$id);
} else {
$stmt=mysqli_prepare($conn,"UPDATE staff SET name=?,position=?,bio=? WHERE id=?");
mysqli_stmt_bind_param($stmt,'sssi',$name,$position,$bio,$id);
}
$success=mysqli_stmt_execute($stmt)?'Staff updated.':'Update failed.';
mysqli_stmt_close($stmt);
} else {
$stmt=mysqli_prepare($conn,"INSERT INTO staff (name,position,bio,photo,created_at) VALUES (?,?,?,?,NOW())");
mysqli_stmt_bind_param($stmt,'ssss',$name,$position,$bio,$photo);
$success=mysqli_stmt_execute($stmt)?'Staff added.':'Failed add.';
mysqli_stmt_close($stmt);
}
}
}
if(isset($_GET['delete'])){
$id=intval($_GET['delete']);
$res=mysqli_query($conn,"SELECT photo FROM staff WHERE id=$id");
$img=mysqli_fetch_assoc($res);
if($img && $img['photo']) @unlink('../assets/staff/'.$img['photo']);
$stmt=mysqli_prepare($conn,"DELETE FROM staff WHERE id=?");
mysqli_stmt_bind_param($stmt,'i',$id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
$success='Staff deleted.';
}
$result=mysqli_query($conn,"SELECT * FROM staff ORDER BY created_at DESC");
?>
<?php include '../includes/header.php'; ?>
<h2 class="text-2xl font-bold mb-4">Manage Staff</h2>
<?php if(isset($success)) echo '<div class="bg-green-100 text-green-700 p-2 rounded mb-2">'.$success.'</div>'; ?>
<?php if(isset($error)) echo '<div class="bg-red-100 text-red-700 p-2 rounded mb-2">'.$error.'</div>'; ?>
<form method="POST" enctype="multipart/form-data" class="grid gap-2 mb-4">
<input type="hidden" name="id">
<input type="text" name="name" placeholder="Name" class="p-2 border rounded">
<input type="text" name="position" placeholder="Position" class="p-2 border rounded">
<textarea name="bio" placeholder="Bio" class="p-2 border rounded"></textarea>
<input type="file" name="photo" class="p-2 border rounded">
<button class="bg-green-600 text-white p-2 rounded">Submit</button>
</form>
<table class="w-full border-collapse border border-slate-300">
<thead class="bg-gray-100"><tr><th>ID</th><th>Name</th><th>Position</th><th>Photo</th><th>Actions</th></tr></thead>
<tbody>
<?php while($row=mysqli_fetch_assoc($result)): ?>
<tr>
	<td><?php echo htmlspecialchars($row['id']); ?></td>
	<td><?php echo htmlspecialchars($row['name']); ?></td>
	<td><?php echo htmlspecialchars($row['position']); ?></td>
	<td>
		<?php if($row['photo']): ?>
			<img src="../assets/staff/<?php echo htmlspecialchars($row['photo']); ?>" alt="Photo" style="height:40px;">
		<?php endif; ?>
	</td>
	<td>
		<a href="?edit=<?php echo $row['id']; ?>" class="text-blue-600">Edit</a>
		<a href="?delete=<?php echo $row['id']; ?>" class="text-red-600" onclick="return confirm('Delete staff?');">Delete</a>
	</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
<?php include '../includes/footer.php'; ?>
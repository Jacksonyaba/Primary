<?php
require_once '../includes/functions.php';
require_admin_login();


if($_SERVER['REQUEST_METHOD']==='POST'){
$caption=$_POST['caption'] ?? '';
$filename=upload_image('photo','../assets/uploads/');
if($filename){
$stmt=mysqli_prepare($conn,"INSERT INTO gallery (filename,caption,uploaded_at) VALUES (?,?,NOW())");
mysqli_stmt_bind_param($stmt,'ss',$filename,$caption);
$success=mysqli_stmt_execute($stmt)?'Image uploaded.':'Upload failed.';
mysqli_stmt_close($stmt);
} else $error='Upload failed or invalid file type.';
}
if(isset($_GET['delete'])){
$id=intval($_GET['delete']);
$res=mysqli_query($conn,"SELECT filename FROM gallery WHERE id=$id");
$img=mysqli_fetch_assoc($res);
if($img) @unlink('../assets/uploads/'.$img['filename']);
$stmt=mysqli_prepare($conn,"DELETE FROM gallery WHERE id=?");
mysqli_stmt_bind_param($stmt,'i',$id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
$success='Image deleted.';
}
$result=mysqli_query($conn,"SELECT * FROM gallery ORDER BY uploaded_at DESC");
?>
<?php include '../includes/header.php'; ?>
<h2 class="text-2xl font-bold mb-4">Manage Gallery</h2>
<?php if(isset($success)) echo '<div class="bg-green-100 text-green-700 p-2 rounded mb-2">'.$success.'</div>'; ?>
<?php if(isset($error)) echo '<div class="bg-red-100 text-red-700 p-2 rounded mb-2">'.$error.'</div>'; ?>
<form method="POST" enctype="multipart/form-data" class="grid gap-2 mb-4">
<input type="text" name="caption" placeholder="Caption" class="p-2 border rounded">
<input type="file" name="photo" class="p-2 border rounded">
<button class="bg-yellow-600 text-white p-2 rounded">Upload</button>
</form>
<table class="w-full border-collapse border border-slate-300">
<thead class="bg-gray-100"><tr><th>ID</th><th>Image</th><th>Caption</th><th>Actions</th></tr></thead>
<tbody>
<?php while($row=mysqli_fetch_assoc($result)): ?>
<tr class="border-t">
<td><?= $row['id'] ?></td>
<td><img src="../assets/uploads/<?= $row['filename'] ?>" width="100"></td>
<td><?= htmlspecialchars($row['caption']) ?></td>
<td><a href="?delete=<?= $row['id'] ?>" class="text-red-600" onclick="return confirm('Delete?')">Delete</a></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
<?php include '../includes/footer.php'; ?>
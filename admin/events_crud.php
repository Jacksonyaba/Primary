<?php
$id=intval($_POST['id'] ?? 0);
$title=$_POST['title'] ?? '';
$content=$_POST['content'] ?? '';
$event_date=$_POST['event_date'] ?? '';


if(!$title || !$content){ $error='Title & Content required.'; }
else{
if($id>0){
$stmt=mysqli_prepare($conn,"UPDATE news SET title=?,content=?,event_date=? WHERE id=?");
mysqli_stmt_bind_param($stmt,'sssi',$title,$content,$event_date,$id);
$success=mysqli_stmt_execute($stmt)?'Event updated.':'Failed update.';
mysqli_stmt_close($stmt);
} else {
$stmt=mysqli_prepare($conn,"INSERT INTO news (title,content,event_date,created_at) VALUES (?,?,?,NOW())");
mysqli_stmt_bind_param($stmt,'sss',$title,$content,$event_date);
$success=mysqli_stmt_execute($stmt)?'Event added.':'Failed add.';
mysqli_stmt_close($stmt);
}
}


if(isset($_GET['delete'])){
$id=intval($_GET['delete']);
$stmt=mysqli_prepare($conn,"DELETE FROM news WHERE id=?");
mysqli_stmt_bind_param($stmt,'i',$id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
$success='Event deleted.';
}


$result=mysqli_query($conn,"SELECT * FROM news ORDER BY created_at DESC");
?>
<?php include '../includes/header.php'; ?>
<h2 class="text-2xl font-bold mb-4">Manage Events & News</h2>
<?php if(isset($success)) echo '<div class="bg-green-100 text-green-700 p-2 rounded mb-2">'.$success.'</div>'; ?>
<?php if(isset($error)) echo '<div class="bg-red-100 text-red-700 p-2 rounded mb-2">'.$error.'</div>'; ?>
<form method="POST" class="grid gap-2 mb-4">
<input type="hidden" name="id">
<input type="text" name="title" placeholder="Title" class="p-2 border rounded">
<textarea name="content" placeholder="Content" class="p-2 border rounded"></textarea>
<input type="date" name="event_date" class="p-2 border rounded">
<button class="bg-blue-600 text-white p-2 rounded">Submit</button>
</form>
<table class="w-full border-collapse border border-slate-300">
<thead class="bg-gray-100">
<tr><th>ID</th><th>Title</th><th>Content</th><th>Date</th><th>Actions</th></tr>
</thead>
<tbody>
<?php while($row=mysqli_fetch_assoc($result)): ?>
<tr class="border-t">
<td><?= $row['id'] ?></td>
<td><?= htmlspecialchars($row['title']) ?></td>
<td><?= htmlspecialchars($row['content']) ?></td>
<td><?= $row['event_date'] ?></td>
<td><a href="?delete=<?= $row['id'] ?>" class="text-red-600" onclick="return confirm('Delete?')">Delete</a></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
<?php include '../includes/footer.php'; ?>
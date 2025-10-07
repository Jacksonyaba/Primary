<?php include 'includes/header.php'; ?>
<h2 class="text-3xl font-bold mb-6 text-center">Admissions Form</h2>
<form action="admissions_process.php" method="POST" class="max-w-xl mx-auto bg-white p-8 rounded shadow-lg grid gap-4">
<input type="text" name="parent_name" placeholder="Parent Name" required class="p-3 border rounded focus:ring-2 focus:ring-blue-300">
<input type="text" name="child_name" placeholder="Child Name" required class="p-3 border rounded focus:ring-2 focus:ring-blue-300">
<input type="text" name="class_applied" placeholder="Class Applied" required class="p-3 border rounded focus:ring-2 focus:ring-blue-300">
<input type="email" name="email" placeholder="Email" class="p-3 border rounded focus:ring-2 focus:ring-blue-300">
<input type="text" name="phone" placeholder="Phone" class="p-3 border rounded focus:ring-2 focus:ring-blue-300">
<textarea name="notes" placeholder="Additional Notes" class="p-3 border rounded focus:ring-2 focus:ring-blue-300"></textarea>
<button class="bg-blue-600 text-white font-semibold py-3 rounded hover:bg-blue-700 transition">Submit Application</button>
</form>
<?php include 'includes/footer.php'; ?>
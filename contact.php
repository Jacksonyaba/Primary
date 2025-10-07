<?php include 'includes/header.php'; ?>
<h2 class="text-3xl font-bold mb-6 text-center">Contact Us</h2>
<form action="contact_process.php" method="POST" class="max-w-xl mx-auto bg-white p-8 rounded shadow-lg grid gap-4">
<input type="text" name="name" placeholder="Your Name" required class="p-3 border rounded focus:ring-2 focus:ring-blue-300">
<input type="email" name="email" placeholder="Your Email" required class="p-3 border rounded focus:ring-2 focus:ring-blue-300">
<input type="text" name="subject" placeholder="Subject" class="p-3 border rounded focus:ring-2 focus:ring-blue-300">
<textarea name="message" placeholder="Your Message" required class="p-3 border rounded focus:ring-2 focus:ring-blue-300"></textarea>
<button class="bg-blue-600 text-white font-semibold py-3 rounded hover:bg-blue-700 transition">Send Message</button>
</form>
<?php include 'includes/footer.php'; ?>
<?php include 'includes/header.php'; ?>

<section class="py-16 bg-blue-50">
    <div class="max-w-3xl mx-auto text-center mb-10">
        <h2 class="text-3xl font-bold text-blue-900 mb-2">Admissions Form</h2>
        <p class="text-gray-600">Apply for admission to Mentor Nursery and Primary School.</p>
    </div>

    <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
        <div class="max-w-xl mx-auto mb-6 bg-green-100 text-green-700 p-4 rounded text-center shadow">
            ✅ Application submitted successfully! We’ll contact you soon.
        </div>
    <?php elseif (isset($_GET['status']) && $_GET['status'] === 'error'): ?>
        <div class="max-w-xl mx-auto mb-6 bg-red-100 text-red-700 p-4 rounded text-center shadow">
            ⚠️ <?= htmlspecialchars($_GET['msg']) ?>
        </div>
    <?php endif; ?>

    <form action="admissions_process.php" method="POST"
        class="max-w-xl mx-auto bg-white p-8 rounded-2xl shadow-lg grid gap-4 border border-blue-100">

        <input type="text" name="name" placeholder="Full Name" required
            class="p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 outline-none transition">

        <input type="email" name="email" placeholder="Email Address" required
            class="p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 outline-none transition">

        <input type="text" name="phone" placeholder="Phone Number" required
            class="p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 outline-none transition">

        <input type="text" name="class" placeholder="Class Applying For" required
            class="p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 outline-none transition">

        <textarea name="message" placeholder="Additional Information (optional)" rows="4"
            class="p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 outline-none transition"></textarea>

        <button type="submit"
            class="bg-blue-700 hover:bg-blue-800 text-white font-semibold py-3 rounded-lg transition duration-300">
            Submit Application
        </button>
    </form>
</section>

<?php include 'includes/footer.php'; ?>
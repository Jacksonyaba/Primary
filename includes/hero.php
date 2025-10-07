<?php include 'includes/header.php'; ?>

<!-- Hero Section -->
<section class="relative bg-blue-100 py-20">
    <div class="max-w-6xl mx-auto px-4 text-center">
        <h1 class="text-3xl md:text-5xl font-bold mb-4">Building Young Minds for a Brighter Future</h1>
        <p class="text-lg md:text-xl mb-6">Quality education in a nurturing environment. Experienced teachers, balanced curriculum, and safe learning space.</p>
        <a href="admissions.php" class="bg-yellow-300 text-blue-800 font-semibold px-6 py-3 rounded-lg shadow hover:bg-yellow-400 transition">Enroll Today</a>
    </div>
</section>

<!-- Features Section -->
<section class="max-w-6xl mx-auto px-4 py-12">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 text-center">
        <div class="bg-white p-6 rounded shadow hover:shadow-lg transition">
            <h3 class="text-xl font-semibold mb-2">Experienced Teachers</h3>
            <p>Our dedicated staff ensures personalized attention and caring support for each child.</p>
        </div>
        <div class="bg-white p-6 rounded shadow hover:shadow-lg transition">
            <h3 class="text-xl font-semibold mb-2">Balanced Curriculum</h3>
            <p>Academics, creativity, and moral development in a holistic learning approach.</p>
        </div>
        <div class="bg-white p-6 rounded shadow hover:shadow-lg transition">
            <h3 class="text-xl font-semibold mb-2">Safe & Inclusive</h3>
            <p>Our school provides a secure, inclusive environment that promotes growth and confidence.</p>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="bg-blue-50 py-12">
    <div class="max-w-6xl mx-auto px-4 grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
        <img src="assets/uploads/classroom.jpg" alt="Classroom Activities" class="rounded shadow w-full">
        <div>
            <h2 class="text-3xl font-bold mb-4">Discover a Place Where Learning Feels Like Play</h2>
            <p class="text-lg mb-4">Join Mentor Nursery and Primary School to nurture your child's potential. Explore engaging lessons, interactive learning, and a warm community that inspires lifelong curiosity.</p>
            <a href="admissions.php" class="bg-blue-600 text-white font-semibold px-6 py-3 rounded-lg shadow hover:bg-blue-700 transition">Apply Now</a>
        </div>
    </div>
</section>

<!-- Admissions Form Section -->
<section class="py-12 bg-white">
    <div class="max-w-xl mx-auto px-4">
        <h2 class="text-3xl font-bold mb-6 text-center">Admissions Form</h2>
        <form action="admissions_process.php" method="POST" class="grid gap-4 bg-blue-50 p-8 rounded shadow-lg">
            <input type="text" name="parent_name" placeholder="Parent Name" required class="p-3 border rounded focus:ring-2 focus:ring-blue-300">
            <input type="text" name="child_name" placeholder="Child Name" required class="p-3 border rounded focus:ring-2 focus:ring-blue-300">
            <input type="text" name="class_applied" placeholder="Class Applied" required class="p-3 border rounded focus:ring-2 focus:ring-blue-300">
            <input type="email" name="email" placeholder="Email" class="p-3 border rounded focus:ring-2 focus:ring-blue-300">
            <input type="text" name="phone" placeholder="Phone" class="p-3 border rounded focus:ring-2 focus:ring-blue-300">
            <textarea name="notes" placeholder="Additional Notes" class="p-3 border rounded focus:ring-2 focus:ring-blue-300"></textarea>
            <button class="bg-blue-600 text-white font-semibold py-3 rounded hover:bg-blue-700 transition">Submit Application</button>
        </form>
    </div>
</section>

<!-- Contact Form Section -->
<section class="py-12 bg-blue-50">
    <div class="max-w-xl mx-auto px-4">
        <h2 class="text-3xl font-bold mb-6 text-center">Contact Us</h2>
        <form action="contact_process.php" method="POST" class="grid gap-4 bg-white p-8 rounded shadow-lg">
            <input type="text" name="name" placeholder="Your Name" required class="p-3 border rounded focus:ring-2 focus:ring-blue-300">
            <input type="email" name="email" placeholder="Your Email" required class="p-3 border rounded focus:ring-2 focus:ring-blue-300">
            <input type="text" name="subject" placeholder="Subject" class="p-3 border rounded focus:ring-2 focus:ring-blue-300">
            <textarea name="message" placeholder="Your Message" required class="p-3 border rounded focus:ring-2 focus:ring-blue-300"></textarea>
            <button class="bg-blue-600 text-white font-semibold py-3 rounded hover:bg-blue-700 transition">Send Message</button>
        </form>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

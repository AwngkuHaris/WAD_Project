<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link rel="stylesheet" href="/project_wad/styles/about_us.css">
</head>
<body>
    <!-- Header Section -->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/header.php'; ?>

    <!-- Breadcrumb Section -->
    <section class="breadcrumb">
        <div class="breadcrumb-container">
            <p><a href="/project_wad/index.php">Home</a> / About</p>
            <h1>About us</h1>
        </div>
    </section>

    <!-- Main About Us Content -->
    <section class="about-hero">
        <div class="hero-container">
            <h2>Welcome to ANMAS Dental Specialist Clinic</h2>
            <h3>A Great Place to Receive Care</h3>
            <p>Offering a variety of services and treatments including general dentistry, cosmetic dentistry, orthodontics, dental care and the prevention of orthodontic diseases.</p>
            <button onclick="location.href='#'">Learn More</button>
        </div>
    </section>

    <!-- Picture Section -->
    <section class="about-picture">
        <img src="/project_wad/images/Anmas_clinic.png" alt="Dental Clinic Picture" class="picture">
    </section>

    <!-- Vision and Mission Section -->
    <section id="vision-mission" class="vision-mission">
        <div class="vision">
            <h2>Vision</h2>
            <p>Educate and treat the patient or community for their future dental health.</p>
        </div>
        <div class="mission">
            <h2>Mission</h2>
            <p>1. Strive to provide the best dental treatment services at a reasonable price, especially for the majority (low income) community in order to receive the best treatment.</p>
            <p>2. Striving to provide excellent and quality services with the use of the latest technology.</p>
            <p>3. Provide detailed explanation in terms of codes and risks so that the patient can make a choice whether to continue treatment or not.</p>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials">
        <h2>What Our Customers Say</h2>
        <div class="testimonial-list">
            <!-- Testimonial Card 1 -->
            <div class="testimonial"> 
                <p>The best service, a conducive and comfortable place, dentists and assistants are always the best and mobilize energy in a great way, THANK YOU, Keep up the good work guys! you guys are amazing  </p>
                <div class="rating">★★★★★</div>
                <p class="author">Khai Mirin</p>
                <div class="avatar">
                    <img src="/project_wad/images/cust1.png" alt="Customer Icon 1">
                </div>
            </div>

            <!-- Testimonial Card 2 -->
            <div class="testimonial">
                <p>I have nice treatment at this dental clinic. The service given was really nice. Will come again after this.Very friendly and helpful when asked. The best service and a very clear treatment explanation </p>
                <div class="rating">★★★★★</div>
                <p class="author">Elisa Maybre </p>
                <div class="avatar">
                    <img src="/project_wad/images/cust2.png" alt="Customer Icon 2"> 
                </div>
            </div>

            <!-- Testimonial Card 3 -->
            <div class="testimonial">
                <p>Good services by the staff and receptionist. The dental was good at explaining your situation and advices you on what treatment shall be done. personally it very recomended doctor and good service, friendly</p>
                <div class="rating">★★★★★</div>
                <p class="author">Mohd Zulhaffiz </p>
                <div class="avatar">
                    <img src="/project_wad/images/cust3.png" alt="Customer Icon 3">
                </div>
            </div>

            <!-- Testimonial Card 4 -->
            <div class="testimonial">
                <p>tok clinic is very comfortable and beautiful. apart from that, the price of the treatment at the store is in line with the treatment that is done. the patch has a warranty and it doesn't break 💜</p>
                <div class="rating">★★★★★</div>
                <p class="author">Whoa Xoxo </p>
                <div class="avatar">
                    <img src="/project_wad/images/cust4.png" alt="Customer Icon 4">
                </div>
            </div>

            <!-- Testimonial Card 5 -->
            <div class="testimonial">
                <p>Consultant from a doctor who is very good and easy to understand..the staff are also friendly..I had a chance to meet Sidai🤣...very very very recommended...The best dental clinic in samarahan 👍🏻</p>
                <div class="rating">★★★★★</div>
                <p class="author">Anderson  </p>
                <div class="avatar">
                    <img src="/project_wad/images/cust5.png" alt="Customer Icon 5">
                </div>
            </div>

            <!-- Testimonial Card 6 -->
            <div class="testimonial">
                <p>Good service 😁 Doctor and assistant are very friendly. The explanation regarding treatment is also very clear and satisfying. 👍 Great customer service. Comfortable clinic environment 😊        </p>
                <div class="rating">★★★★★</div>
                <p class="author">Nazifah Alek </p>
                <div class="avatar">
                    <img src="/project_wad/images/cust6.png" alt="Customer Icon 6">
                </div>
            </div>
        </div>
    </section>

    <!-- Quotes Section -->
    <section class="quotes">
        <div class="quotes-list">
            <blockquote>
                <p>"A smile remains the most inexpensive gift I can bestow on anyone and yet its powers can vanquish kingdoms."</p>
                <footer>- Og Mandido</footer>
            </blockquote>
        </div>
    </section>

    <!-- Footer Section -->
    <?php include('../../footer.php'); ?>
</body>
</html>

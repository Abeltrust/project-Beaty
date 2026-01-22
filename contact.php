
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Us | Beauty Multi-Service</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

  <!-- Navbar & Main Style -->
  <link rel="stylesheet" href="assets/css/navbar.css">
  <link rel="stylesheet" href="assets/css/style.css">

  <style>
    html, body {
  width: 100%;
  overflow-x: hidden;
}

    body{
      background:#FAF8F5;
      font-family:'Poppins',sans-serif;
      padding-top:90px;
    }

    @media(max-width:991px){
      body{ padding-top:64px; }
    }

    /* HERO */
    .contact-hero{
      background:
        linear-gradient(rgba(0,0,0,.55),rgba(0,0,0,.75)),
        url("https://images.unsplash.com/photo-1600585154340-be6161a56a0c")
        center/cover no-repeat;
      color:#fff;
      border-radius:24px;
      padding:4rem 2rem;
    }

    .contact-hero h1{
      font-weight:700;
      font-size:clamp(2rem,4vw,3rem);
    }

    .contact-hero p{
      max-width:700px;
      opacity:.95;
      font-size:1.05rem;
    }

    /* CONTACT CARD */
    .contact-card{
      background:#fff;
      border-radius:22px;
      padding:2rem;
      box-shadow:0 25px 55px rgba(0,0,0,.1);
      height:100%;
    }

    .contact-item{
      display:flex;
      gap:1rem;
      align-items:flex-start;
      margin-bottom:1.5rem;
    }

    .contact-item i{
      font-size:1.4rem;
      color:var(--brand);
    }

    .contact-item h6{
      font-weight:600;
      margin-bottom:.2rem;
    }

    /* FORM */
    .form-control{
      border-radius:14px;
      padding:.75rem 1rem;
      border:1px solid #ddd;
    }

    .form-control:focus{
      border-color:var(--brand);
      box-shadow:none;
    }

    textarea.form-control{
      resize:none;
    }

    /* CTA STRIP */
    .contact-cta{
      background:#fff;
      border-radius:26px;
      padding:2.5rem 2rem;
      box-shadow:0 30px 60px rgba(0,0,0,.12);
      text-align:center;
    }
    @media (max-width: 576px) {
  .contact-wrapper {
    padding: 2rem 1rem;
    max-width: 100%;
  }

  .contact-card {
    padding: 1.5rem;
  }
}


  </style>
</head>
<body>

<?php include "includes/navbar.php"; ?>

<div class="container col-12 col-md-6 mt-4 contact-wrapper">

  <!-- HERO -->
  <section class="contact-hero mb-5">
    <h1>Get in Touch</h1>
    <p class="mt-3">
      Have questions about our products or need expert guidance?
      We’re here to help you choose the perfect materials for your project.
    </p>
  </section>

  <!-- CONTACT CONTENT -->
  <section class="mb-5 ">
    <div class="row g-4 mt-5">

      <!-- CONTACT INFO -->
     <div class="col-12 col-md-6">
        <div class="contact-card">

          <div class="contact-item">
            <i class="bi bi-geo-alt"></i>
            <div>
              <h6>Our Location</h6>
              <p class="text-muted mb-0">
                Lagos, Nigeria
              </p>
            </div>
          </div>

          <div class="contact-item">
            <i class="bi bi-telephone"></i>
            <div>
              <h6>Phone</h6>
              <p class="text-muted mb-0">
                +234 704 307 9022
              </p>
            </div>
          </div>

          <div class="contact-item">
            <i class="bi bi-envelope"></i>
            <div>
              <h6>Email</h6>
              <p class="text-muted mb-0">
                support@beautymultiservice.com
              </p>
            </div>
          </div>

          <div class="contact-item">
            <i class="bi bi-whatsapp"></i>
            <div>
              <h6>WhatsApp</h6>
              <a
                href="https://wa.me/2347043079022"
                target="_blank"
                class="text-decoration-none"
              >
                Chat with us instantly
              </a>
            </div>
          </div>

        </div>
      </div>

      <!-- CONTACT FORM -->
      <div class="col-12 col-lg-8">
        <div class="contact-card">

          <h4 class="mb-3">Send Us a Message</h4>
          <p class="text-muted mb-4">
            Fill out the form below and our team will get back to you shortly.
          </p>

          <form>
            <div class="row g-3">

              <div class="col-12 col-md-6">
                <input type="text" class="form-control" placeholder="Full Name" required>
              </div>

              <div class="col-12 col-md-6">
                <input type="email" class="form-control" placeholder="Email Address" required>
              </div>

              <div class="col-12">
                <input type="text" class="form-control" placeholder="Subject">
              </div>

              <div class="col-12">
                <textarea
                  rows="4"
                  class="form-control"
                  placeholder="Your message..."
                ></textarea>
              </div>

              <div class="col-12">
                <button type="submit" class="btn btn-primary-custom w-100">
                  Send Message
                </button>
              </div>

            </div>
          </form>

        </div>
      </div>

    </div>
  </section>

  <!-- CTA -->
  <section class="mb-5">
    <div class="contact-cta">
      <h4 class="mb-3">Prefer Instant Support?</h4>
      <p class="text-muted mb-4">
        Reach us directly on WhatsApp for fast responses and product inquiries.
      </p>

      <a href="https://wa.me/2347043079022" target="_blank" class="btn btn-success">
        <i class="bi bi-whatsapp me-1"></i> Chat on WhatsApp
      </a>
    </div>
  </section>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

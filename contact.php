<?php include 'header.php'; check_csrf(); ?>

<style>
    .contact-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        transition: transform 0.3s ease;
    }
    
    .contact-card:hover {
        transform: translateY(-5px);
    }
    
    .contact-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 24px;
    }
    
    .contact-info {
        text-align: center;
        padding: 20px;
    }
    
    .contact-form {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 30px;
    }
    
    .map-container {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
</style>

<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
        <li class="breadcrumb-item active">Contact Us</li>
    </ol>
</nav>

<div class="text-center mb-5">
    <h1 class="display-5 fw-bold text-primary mb-3">Contact Us</h1>
    <p class="lead text-muted">We'd love to hear from you. Get in touch with us!</p>
</div>

<div class="row g-4 mb-5">
    <!-- Contact Information Cards -->
    <div class="col-md-4">
        <div class="card contact-card h-100">
            <div class="card-body contact-info">
                <div class="contact-icon bg-primary text-white">
                    <i class="bi bi-telephone"></i>
                </div>
                <h5 class="card-title">Phone</h5>
                <p class="card-text text-muted">Call us for quick assistance</p>
                <a href="tel:+911234567890" class="btn btn-outline-primary">+91 12345 67890</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card contact-card h-100">
            <div class="card-body contact-info">
                <div class="contact-icon bg-success text-white">
                    <i class="bi bi-envelope"></i>
                </div>
                <h5 class="card-title">Email</h5>
                <p class="card-text text-muted">Send us an email anytime</p>
                <a href="mailto:info@cinemagic.com" class="btn btn-outline-success">info@cinemagic.com</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card contact-card h-100">
            <div class="card-body contact-info">
                        <div class="card-body">
                            <h6><i class="bi bi-clock text-primary me-2"></i> Opening Hours</h6>
                            <ul class="list-unstyled mt-3">
                                <li class="mb-2">
                                    <small>Monday - Friday</small><br>
                                    <strong>10:00 AM - 11:00 PM</strong>
                                </li>
                                <li class="mb-2">
                                    <small>Saturday - Sunday</small><br>
                                    <strong>9:00 AM - 12:00 AM</strong>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
    
</div>

<div class="row g-5">
    <!-- Contact Form -->
    <div class="col-lg-6">
        <div class="contact-form">
            <h3 class="mb-4">Send us a Message</h3>
            
            <?php
            $success = '';
            $errors = [];
            $form_data = [
                'name' => '',
                'email' => '',
                'subject' => '',
                'message' => ''
            ];

            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                $form_data['name'] = trim($_POST['name'] ?? '');
                $form_data['email'] = trim($_POST['email'] ?? '');
                $form_data['subject'] = trim($_POST['subject'] ?? '');
                $form_data['message'] = trim($_POST['message'] ?? '');
    
                // Validation
                if(empty($form_data['name'])) $errors[] = "Name is required";
                if(empty($form_data['email']) || !filter_var($form_data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required";
                if(empty($form_data['subject'])) $errors[] = "Subject is required";
                if(empty($form_data['message'])) $errors[] = "Message is required";
    
                if(empty($errors)) {
                    // In a real application, you would send an email here
                    // For demo purposes, we'll just show a success message
                    $success = "Thank you for your message! We'll get back to you within 24 hours.";
        
                    // Clear form fields only on success
                    $form_data = [
                        'name' => '',
                        'email' => '',
                        'subject' => '',
                        'message' => ''
                    ];
                }
            }
            ?>
            
            <?php if($success): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= e($success); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php foreach($errors as $error): ?>
                <div class="alert alert-danger"><?= e($error); ?></div>
            <?php endforeach; ?>
            
            <form method="post">
                <?php csrf_field(); ?>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Your Name *</label>
                            <input type="text" class="form-control" id="name" name="name" 
       value="<?= e($form_data['name']); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Your Email *</label>
                            <input type="email" class="form-control" id="email" name="email" 
       value="<?= e($form_data['email']); ?>" required>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="subject" class="form-label">Subject *</label>
                    <input type="text" class="form-control" id="subject" name="subject" 
       value="<?= e($form_data['subject']); ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="message" class="form-label">Your Message *</label>
                    <textarea class="form-control" id="message" name="message" rows="5" 
          required><?= e($form_data['message']); ?></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-send me-2"></i> Send Message
                </button>
            </form>
        </div>
    </div>

            

        </div>
    </div>
</div>

<!-- FAQ Section -->
<div class="row mt-5">
    <div class="col-12">
        <div class="card border-0">
            <div class="card-body">
                <h3 class="text-center mb-4">Frequently Asked Questions</h3>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-question-circle text-primary me-3 mt-1"></i>
                            <div>
                                <h6>How can I book tickets online?</h6>
                                <p class="text-muted small">Simply select a movie, choose your showtime, pick your seats, and proceed to checkout. It's that easy!</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-question-circle text-primary me-3 mt-1"></i>
                            <div>
                                <h6>What is your cancellation policy?</h6>
                                <p class="text-muted small">Tickets can be cancelled up to 2 hours before the showtime. Refunds are processed within 5-7 business days.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-question-circle text-primary me-3 mt-1"></i>
                            <div>
                                <h6>Do you offer group bookings?</h6>
                                <p class="text-muted small">Yes! Contact us directly for group bookings of 20+ people for special discounts and arrangements.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-question-circle text-primary me-3 mt-1"></i>
                            <div>
                                <h6>Is there parking available?</h6>
                                <p class="text-muted small">We offer free parking for all our customers with over 500 parking spaces available.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
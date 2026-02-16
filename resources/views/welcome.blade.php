<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="Task Management System - Organize and manage your tasks efficiently" />
        <meta name="author" content="TMS Team" />
        <title>Task Management System</title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
        <!-- Font Awesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            
            body {
                font-family: 'Inter', sans-serif;
                background: #f8f9fa;
                color: #2d3436;
            }
            
            h1, h2, h3, h4, h5, h6 {
                font-family: 'Poppins', sans-serif;
                font-weight: 700;
                color:rgb(255, 255, 255);
            }
            .mb-4{
                color: #000000;
            }

            
            a {
                color: #667eea;
                text-decoration: none;
                transition: all 0.3s ease;
            }
            
            a:hover {
                color: #5568d3;
            }
            
            /* Masthead / Hero Section */
            .masthead {
                background: linear-gradient(135deg,rgb(37, 4, 39) 0%, #764ba2 100%);
                color: white;
                padding: 120px 0;
                position: relative;
                overflow: hidden;
                display: flex;
                align-items: center;
                min-height: 100vh;
            }
            
            .masthead::before {
                content: '';
                position: absolute;
                width: 600px;
                height: 600px;
                background: rgba(255, 255, 255, 0.1);
                border-radius: 50%;
                top: -300px;
                right: -300px;
            }
            
            .masthead::after {
                content: '';
                position: absolute;
                width: 400px;
                height: 400px;
                background: rgba(255, 255, 255, 0.05);
                border-radius: 50%;
                bottom: -200px;
                left: -200px;
            }
            
            .masthead-content {
                position: relative;
                z-index: 10;
            }
            
            .masthead h1 {
                font-size: 3.5rem;
                font-weight: 800;
                margin-bottom: 1.5rem;
                line-height: 1.2;
            }
            
            .masthead h3 {
                font-size: 1.5rem;
                font-weight: 300;
                margin-bottom: 2rem;
                opacity: 0.95;
            }
            
            .btn-xl {
                padding: 0.875rem 2rem;
                font-size: 1.1rem;
                border-radius: 0.5rem;
                font-weight: 600;
                transition: all 0.3s ease;
                border: none;
                background: #764ba2;
            }
            .btn-xl:hover {
                background: #764ba2;
                transform: translateY(-2px);
                box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
            }
            
            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
            }
            
            .btn-light:hover {
                background: white;
                transform: translateY(-2px);
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            }
            
            /* Content Sections */
            .content-section {
                padding: 6rem 0;
            }
            
            .content-section-heading {
                margin-bottom: 3rem;
            }
            
            .content-section-heading h3 {
                font-size: 1.1rem;
                color: #764ba2;
                text-transform: uppercase;
                letter-spacing: 2px;
                font-weight: 600;
            }
            
            .content-section-heading h2 {
                font-size: 2.5rem;
                margin-top: 0.5rem;
            }
            
            /* Features Section */
            .feature-card {
                background: white;
                border-radius: 1rem;
                padding: 2rem;
                margin-bottom: 2rem;
                transition: all 0.3s ease;
                border: 1px solid #e9ecef;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            }
            
            .feature-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 12px 24px rgba(102, 126, 234, 0.15);
                border-color: #667eea;
            }
            
            .feature-icon {
                width: 80px;
                height: 80px;
                background: linear-gradient(135deg,rgb(37, 4, 39) 0%, #764ba2 100%);
                border-radius: 1rem;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 2rem;
                color: white;
                margin-bottom: 1.5rem;
            }
            
            .feature-card h4 {
                font-size: 1.3rem;
                margin-bottom: 0.8rem;
                color: #1a1a2e;
            }
            
            .feature-card p {
                color: #636e72;
                line-height: 1.6;
                margin-bottom: 0;
            }
            
            /* Services Section */
            .services-section {
                background: white;
                padding: 6rem 0;
            }
            
            /* CTA Section */
            .cta-section {
                background: linear-gradient(135deg,rgb(37, 4, 39) 0%, #764ba2 100%);
                color: white;
                padding: 5rem 0;
                text-align: center;
            }
            
            .cta-section h2 {
                font-size: 2.5rem;
                margin-bottom: 1.5rem;
                color: white;
            }
            
            .cta-section p {
                font-size: 1.2rem;
                opacity: 0.95;
            }
            
            /* Features Grid */
            .bg-light {
                background: #f8f9fa;
            }
            
            .bg-primary {
                background: white !important;
            }
            
            .bg-primary .text-white {
                color: #1a1a2e !important;
            }
            
            /* Footer */
            .footer {
                background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
                color: white;
                padding: 3rem 0 1rem;
            }
            
            .footer p {
                color: #b8b8b8;
            }
            
            .social-link {
                width: 50px;
                height: 50px;
                border-radius: 50%;
                background: rgba(102, 126, 234, 0.2);
                display: inline-flex;
                align-items: center;
                justify-content: center;
                color: white;
                transition: all 0.3s ease;
                margin: 0 0.5rem;
                border: 2px solid transparent;
            }
            
            .social-link:hover {
                background: #667eea;
                transform: translateY(-3px);
                border-color: #667eea;
            }
            
            /* Scroll to Top */
            .scroll-to-top {
                position: fixed;
                right: 2rem;
                bottom: 2rem;
                width: 50px;
                height: 50px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                color: white;
                z-index: 100;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
                box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
            }
            
            .scroll-to-top.show {
                opacity: 1;
                visibility: visible;
            }
            
            .scroll-to-top:hover {
                transform: translateY(-3px);
                box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
            }
        </style>
    </head>
    <body id="page-top">
        <!-- Header/Hero Section -->
        <header class="masthead">
            <div class="container px-4 px-lg-5">
                <div class="masthead-content text-center">
                    <h1>Task Management System</h1>
                    <h3><em>Organize, Track, and Manage Your Tasks Efficiently</em></h3>
                    <div class="mt-4">
                        <a class="btn btn-light btn-xl me-3" href="#features" style="background: #764ba2; color: white;">
                            <i class="fas fa-arrow-down me-2"></i> Explore
                        </a>
                        <a class="btn btn-xl" href="{{ route('login') }}" style="background: white; color: #667eea;">
                            <i class="fas fa-sign-in-alt me-2"></i> Login
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- About Section -->
        <section class="content-section" id="about">
            <div class="container px-4 px-lg-5">
                <div class="row align-items-center">
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <div>
                            <h2 class="mb-4">What is Task Management System?</h2>
                            <p class="lead mb-3">
                                A Task Management System (TMS) is a powerful platform designed to help individuals and teams organize, track, and manage tasks or projects efficiently.
                            </p>
                            <p class="text-muted mb-4">
                                Our TMS ensures that tasks are completed on time, responsibilities are clear, and progress is monitored in real-time. Whether you're managing a small team or a large organization, our system adapts to your needs.
                            </p>
                            <a class="btn btn-primary btn-xl" href="#features">
                                <i class="fas fa-star me-2"></i> View Features
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); height: 400px; border-radius: 1rem; display: flex; align-items: center; justify-content: center; color: white;">
                            <div class="text-center">
                                <i class="fas fa-tasks" style="font-size: 5rem; opacity: 0.3;"></i>
                                <p class="mt-3" style="font-size: 1.2rem;">Efficient Task Management</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="services-section" id="features">
            <div class="container px-4 px-lg-5">
                <div class="content-section-heading text-center">
                    <h3>Key Features</h3>
                    <h2 class="mb-4">What We Offer</h2>
                </div>
                <div class="row gx-4 gx-lg-5">
                    <div class="col-lg-3 col-md-6">
                        <div class="feature-card">
                            <div class="feature-icon rounded">
                                <i class="fas fa-plus-circle"></i>
                            </div>
                            <h4>Task Creation</h4>
                            <p>Easily create and assign tasks to team members with clear descriptions and deadlines.</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="feature-card">
                            <div class="feature-icon rounded">
                                <i class="fas fa-sync-alt"></i>
                            </div>
                            <h4>Status Tracking</h4>
                            <p>Monitor task progress in real-time with visual status updates and progress indicators.</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="feature-card">
                            <div class="feature-icon rounded">
                                <i class="fas fa-flag"></i>
                            </div>
                            <h4>Priority Management</h4>
                            <p>Set task priorities and deadlines to organize work effectively and avoid missed dates.</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="feature-card">
                            <div class="feature-icon rounded">
                                <i class="fas fa-bell"></i>
                            </div>
                            <h4>Notifications</h4>
                            <p>Stay updated with real-time notifications and reminders for upcoming or overdue tasks.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="cta-section">
            <div class="container px-4 px-lg-5 text-center">
                <h2>Ready to Get Started?</h2>
                <p class="mb-4" style="color:white;">Join thousands of teams managing their tasks more efficiently</p>
                <a class="btn btn-light btn-xl" href="{{ route('login') }}" style="background: #764ba2; color: white;">
                    <i class="fas fa-rocket me-2"></i> Get Started Now
                </a>
            </div>
        </section>

        <!-- Footer -->
        <footer class="footer">
            <div class="container px-4 px-lg-5">
                <div class="row">
                    <div class="col-lg-6 text-center text-lg-start mb-4 mb-lg-0">
                        <h5 class="mb-2">Task Management System</h5>
                        <p class="text-muted">Organize, track, and manage your tasks efficiently.</p>
                    </div>
                    <div class="col-lg-6 text-center text-lg-end">
                        <a href="#" class="social-link">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-link">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-link">
                            <i class="fab fa-github"></i>
                        </a>
                    </div>
                </div>
                <hr class="my-4" style="border-color: rgba(255,255,255,0.1);">
                <div class="text-center">
                    <p class="text-muted small mb-0">&copy; 2026 Task Management System. All rights reserved.</p>
                </div>
            </div>
        </footer>

        <!-- Scroll to Top Button -->
        <a class="scroll-to-top rounded" id="scrollToTop" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script>
            // Scroll to Top Button
            const scrollToTopBtn = document.getElementById('scrollToTop');
            
            window.addEventListener('scroll', () => {
                if (window.pageYOffset > 300) {
                    scrollToTopBtn.classList.add('show');
                } else {
                    scrollToTopBtn.classList.remove('show');
                }
            });
            
            scrollToTopBtn.addEventListener('click', (e) => {
                e.preventDefault();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
            
            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');
                    if (href !== '#page-top' && document.querySelector(href)) {
                        e.preventDefault();
                        document.querySelector(href).scrollIntoView({ behavior: 'smooth' });
                    }
                });
            });
        </script>
    </body>
</html>

@extends('layouts.app')

@section('title', 'About Us | MAM TOURS')

@section('content')
    <!-- Hero Section -->
    <section class="page-hero modern-page-hero">
        <div class="hero-geometric-shapes">
            <div class="hero-shape hero-shape-1"></div>
            <div class="hero-shape hero-shape-2"></div>
            <div class="hero-shape hero-shape-3"></div>
            <div class="hero-shape hero-shape-4"></div>
        </div>
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="hero-title"><i class="fas fa-star"></i> About MAM TOURS</h1>
            <p class="hero-subtitle">Your Trusted Partner in Premium Car Rental Across Uganda</p>
        </div>
    </section>

    <!-- Real-Time Statistics Bar -->
    <section class="stats-bar">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-number" id="statVehicles">—</span>
                    <span class="stat-label">Vehicles in Fleet</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number" id="statBookings">—</span>
                    <span class="stat-label">Happy Customers</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">24/7</span>
                    <span class="stat-label">Support Available</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><i class="fas fa-map-marked-alt"></i></span>
                    <span class="stat-label">Nationwide Coverage</span>
                </div>
            </div>
        </div>
    </section>

    <!-- About Content -->
    <section class="content-section">
        <div class="container">
            <div class="about-grid">
                <div class="about-card">
                    <h2 class="section-title"><i class="fas fa-users"></i> Who We Are</h2>
                    <p class="content-text">
                        MAM TOURS is Uganda's premier car rental service, dedicated to making every journey memorable. 
                        Based in Kampala with service coverage across Uganda, we provide reliable, affordable, and 
                        premium car rental experiences for both locals and international travelers. From city trips 
                        to safari adventures, we're your trusted travel companion.
                    </p>
                </div>
                <div class="about-card">
                    <h2 class="section-title"><i class="fas fa-bullseye"></i> Our Mission</h2>
                    <p class="content-text">
                        To make every journey memorable by providing hassle-free, affordable, and premium car rental 
                        experiences tailored to the Ugandan market. We believe in transparency, reliability, and putting 
                        our customers first - always. With support for Mobile Money payments and 24/7 WhatsApp assistance, 
                        we're here when you need us.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Journey Timeline -->
    <section class="content-section alt-bg">
        <div class="container">
            <h2 class="section-title text-center"><i class="fas fa-road"></i> Our Journey</h2>
            <p class="section-description text-center">Building trust across Uganda, one journey at a time</p>
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <h3>Founded in Kampala</h3>
                        <p>Started with a vision to revolutionize car rental services in Uganda with a focus on customer satisfaction and transparency.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <h3>Expanded Fleet</h3>
                        <p>Grew our fleet to include economy cars, SUVs, and 4x4 vehicles perfect for Uganda's diverse terrain and safari adventures.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <h3>Digital Innovation</h3>
                        <p>Launched online booking platform with Mobile Money integration, making car rental accessible to all Ugandans.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <h3>Nationwide Service</h3>
                        <p>Extended coverage across Uganda - from Kampala to Entebbe, national parks, and beyond.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Core Values -->
    <section class="content-section">
        <div class="container">
            <h2 class="section-title text-center"><i class="fas fa-sparkles"></i> Why Choose MAM TOURS?</h2>
            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon"><i class="fas fa-car"></i></div>
                    <h3>Premium Fleet</h3>
                    <p>Handpicked vehicles maintained to perfection, ready for Uganda's roads</p>
                </div>
                <div class="value-card">
                    <div class="value-icon"><i class="fas fa-tag"></i></div>
                    <h3>Transparent UGX Pricing</h3>
                    <p>Best rates in Ugandan Shillings with zero hidden charges</p>
                </div>
                <div class="value-card">
                    <div class="value-icon"><i class="fas fa-shield"></i></div>
                    <h3>Full Coverage</h3>
                    <p>Comprehensive insurance for complete peace of mind</p>
                </div>
                <div class="value-card">
                    <div class="value-icon"><i class="fas fa-mobile-alt"></i></div>
                    <h3>Mobile Money</h3>
                    <p>Pay with MTN or Airtel Money - convenient and secure</p>
                </div>
                <div class="value-card">
                    <div class="value-icon"><i class="fas fa-headset"></i></div>
                    <h3>24/7 WhatsApp Support</h3>
                    <p>Always available on WhatsApp when you need us most</p>
                </div>
                <div class="value-card">
                    <div class="value-icon"><i class="fas fa-check-circle"></i></div>
                    <h3>Trusted Service</h3>
                    <p>Thousands of happy customers, countless journeys</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Service Coverage Map -->
    <section class="content-section alt-bg">
        <div class="container">
            <h2 class="section-title text-center"><i class="fas fa-map-marked-alt"></i> Where We Serve</h2>
            <p class="section-description text-center">Comprehensive coverage across Uganda</p>
            <div class="service-areas-grid">
                <div class="service-area-card">
                    <div class="area-icon"><i class="fas fa-city"></i></div>
                    <h3>Kampala</h3>
                    <p>Full service in Uganda's capital - business trips, events, and daily rentals</p>
                </div>
                <div class="service-area-card">
                    <div class="area-icon"><i class="fas fa-plane"></i></div>
                    <h3>Entebbe</h3>
                    <p>Airport pickup and drop-off at Entebbe International Airport (EBB)</p>
                </div>
                <div class="service-area-card">
                    <div class="area-icon"><i class="fas fa-tree"></i></div>
                    <h3>National Parks</h3>
                    <p>Murchison Falls, Queen Elizabeth, Kidepo - 4x4 safari vehicles</p>
                </div>
                <div class="service-area-card">
                    <div class="area-icon"><i class="fas fa-paw"></i></div>
                    <h3>Gorilla Trekking</h3>
                    <p>Bwindi & Mgahinga - sturdy vehicles for mountain terrain</p>
                </div>
                <div class="service-area-card">
                    <div class="area-icon"><i class="fas fa-water"></i></div>
                    <h3>Jinja & Eastern</h3>
                    <p>Source of the Nile, adventure sports, and eastern Uganda</p>
                </div>
                <div class="service-area-card">
                    <div class="area-icon"><i class="fas fa-mountain"></i></div>
                    <h3>Western Uganda</h3>
                    <p>Fort Portal, Kasese, and the Rwenzori Mountains region</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Customer Testimonials -->
    <section class="content-section">
        <div class="container">
            <h2 class="section-title text-center"><i class="fas fa-quote-left"></i> What Our Customers Say</h2>
            <p class="section-description text-center">Real experiences from travelers across Uganda</p>
            
            <div class="testimonials-grid" id="aboutTestimonials">
                <!-- Default testimonials (will be replaced by dynamic content) -->
                <div class="testimonial-card">
                    <div class="testimonial-stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    <p class="testimonial-text">"Smooth booking and a great SUV for our trip to Murchison Falls. MAM Tours made it easy from Kampala."</p>
                    <div class="testimonial-author">Mawejje, Kampala</div>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    <p class="testimonial-text">"Picked us up at Entebbe Airport on time. Car was clean and perfect for a week in Uganda. Will book again."</p>
                    <div class="testimonial-author">Sarah & Mike, UK</div>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    <p class="testimonial-text">"Used them for our wedding in Entebbe. Professional and reliable. Highly recommend for events in Uganda."</p>
                    <div class="testimonial-author">Okoth, Entebbe</div>
                </div>
            </div>
            
            <div class="testimonials-loading" id="aboutTestimonialsLoading" style="display: none;">
                <i class="fas fa-spinner fa-spin"></i> Loading reviews...
            </div>
            
            <div class="text-center" style="margin-top: 2rem;">
                @auth
                    <a href="{{ route('reviews.create') }}" class="cta-button">
                        <i class="fas fa-plus"></i> Share Your Experience
                    </a>
                @else
                    <a href="{{ route('login') }}" class="cta-button">
                        <i class="fas fa-sign-in-alt"></i> Login to Leave a Review
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Payment Methods -->
    <section class="content-section alt-bg">
        <div class="container">
            <h2 class="section-title text-center"><i class="fas fa-credit-card"></i> Flexible Payment Options</h2>
            <p class="section-description text-center">Pay your way - we support multiple payment methods</p>
            <div class="payment-methods-grid">
                <div class="payment-method-card">
                    <div class="payment-icon"><i class="fas fa-mobile-alt"></i></div>
                    <h3>MTN Mobile Money</h3>
                    <p>Pay instantly with MTN MoMo - Uganda's most popular payment method</p>
                </div>
                <div class="payment-method-card">
                    <div class="payment-icon"><i class="fas fa-mobile-alt"></i></div>
                    <h3>Airtel Money</h3>
                    <p>Quick and secure payments via Airtel Money</p>
                </div>
                <div class="payment-method-card">
                    <div class="payment-icon"><i class="fas fa-university"></i></div>
                    <h3>Bank Transfer</h3>
                    <p>Direct bank transfers in UGX accepted</p>
                </div>
                <div class="payment-method-card">
                    <div class="payment-icon"><i class="fas fa-money-bill-wave"></i></div>
                    <h3>Cash Payment</h3>
                    <p>Pay in cash at pickup - UGX, USD, EUR accepted</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section modern-cta">
        <div class="cta-geometric-shapes">
            <div class="cta-shape cta-shape-1"></div>
            <div class="cta-shape cta-shape-2"></div>
            <div class="cta-shape cta-shape-3"></div>
        </div>
        <div class="container">
            <div class="cta-content modern-cta-content">
                <h2 class="cta-title"><i class="fas fa-rocket"></i> Ready for Your Next Adventure?</h2>
                <p class="cta-subtitle">Join thousands of satisfied travelers who trust MAM TOURS across Uganda</p>
                <div class="cta-actions">
                    <a href="{{ url('/bookings') }}" class="cta-button modern-cta-button">
                        <i class="fas fa-calendar-check"></i> Book Your Perfect Ride
                    </a>
                    <a href="https://wa.me/256755943973?text=Hello%20MAM%20Tours%2C%20I%20would%20like%20to%20inquire%20about%20car%20rental%20services." target="_blank" rel="noopener" class="cta-button cta-button-secondary">
                        <i class="fab fa-whatsapp"></i> Chat on WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section footer-brand">
                    <div class="footer-logo">
                        <div class="footer-logo-container">
                            <img src="{{ asset('images/MAM TOURS LOGO.jpg') }}" alt="MAM TOURS logo" class="footer-logo-img">
                            <div class="footer-logo-overlay">
                                <i class="fas fa-car"></i>
                            </div>
                        </div>
                        <div class="footer-logo-text">
                            <h3 class="footer-title">MAM TOURS</h3>
                            <span class="footer-subtitle">Car Rental</span>
                        </div>
                    </div>
                    <p class="footer-text">Your trusted car rental partner in Uganda – reliable, affordable, road ready across the Pearl of Africa.</p>
                </div>
                <div class="footer-section">
                    <h4 class="footer-heading">Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li><a href="{{ url('/about') }}">About Us</a></li>
                        <li><a href="{{ url('/bookings') }}">Book a Car</a></li>
                        <li><a href="{{ url('/faqs') }}">FAQs</a></li>
                        <li><a href="{{ url('/contact') }}">Contact Us</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4 class="footer-heading">Contact – Uganda</h4>
                    <p class="footer-text">Kampala, Uganda</p>
                    <p class="footer-text"><a href="tel:+256755943973" class="footer-link">+256 755 943 973</a></p>
                    <a href="https://wa.me/256755943973" target="_blank" rel="noopener" class="footer-link">WhatsApp</a> ·
                    <a href="{{ url('/contact') }}" class="footer-link">Contact form</a>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="footer-bottom-logo">
                    <div class="footer-bottom-logo-container">
                        <img src="{{ asset('images/MAM TOURS LOGO.jpg') }}" alt="MAM TOURS" class="footer-bottom-logo-img">
                    </div>
                    <span>MAM TOURS</span>
                </div>
                <p>&copy; <span class="current-year"></span> MAM TOURS. All rights reserved. Car rental in Uganda.</p>
            </div>
        </div>
    </footer>

    <!-- Floating WhatsApp Button -->
    <a href="https://wa.me/256755943973?text=Hello%20MAM%20Tours%2C%20I%20would%20like%20to%20inquire%20about%20car%20rental%20services." target="_blank" rel="noopener" class="whatsapp-float" title="Chat with us on WhatsApp">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
        </svg>
    </a>
@endsection

@section('scripts')
    <script>
    (function(){
      // Set current year
      var y=new Date().getFullYear();
      document.querySelectorAll('.current-year').forEach(function(el){ el.textContent=y; });
      
      // Load real-time statistics
      var statVehicles = document.getElementById('statVehicles');
      var statBookings = document.getElementById('statBookings');
      
      if(statVehicles && statBookings){
        fetch('/api/health')
          .then(function(r){ return r.json(); })
          .then(function(d){
            if(d && typeof d.cars_count !== 'undefined') {
              statVehicles.textContent = d.cars_count;
            }
            if(d && typeof d.bookings_count !== 'undefined') {
              statBookings.textContent = d.bookings_count + '+';
            }
          })
          .catch(function(){
            statVehicles.textContent = '50+';
            statBookings.textContent = '1000+';
          });
      }
      
      // Load dynamic reviews
      var testimonialsGrid = document.getElementById('aboutTestimonials');
      var testimonialsLoading = document.getElementById('aboutTestimonialsLoading');
      
      if (testimonialsGrid && testimonialsLoading) {
        testimonialsLoading.style.display = 'block';
        
        fetch('/api/reviews')
          .then(function(response){ return response.json(); })
          .then(function(reviews){
            testimonialsLoading.style.display = 'none';
            
            if (reviews && reviews.length > 0) {
              testimonialsGrid.innerHTML = '';
              
              // Show up to 6 reviews
              var displayReviews = reviews.slice(0, 6);
              
              displayReviews.forEach(function(review){
                var stars = '';
                for(var i = 0; i < 5; i++) {
                  stars += '<i class="fas fa-star' + (i < review.rating ? '' : ' inactive') + '"></i>';
                }
                
                var testimonialCard = document.createElement('div');
                testimonialCard.className = 'testimonial-card';
                testimonialCard.innerHTML = 
                  '<div class="testimonial-stars">' + stars + '</div>' +
                  '<p class="testimonial-text">"' + review.review_text + '"</p>' +
                  '<div class="testimonial-author">' + review.name + '</div>';
                
                testimonialsGrid.appendChild(testimonialCard);
              });
            }
          })
          .catch(function(error){
            console.log('Using default testimonials');
            testimonialsLoading.style.display = 'none';
          });
      }
    })();
    </script>
@endsection

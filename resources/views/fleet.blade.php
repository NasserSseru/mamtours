@extends('layouts.app')

@section('title', 'Our Premium Fleet | MAM TOURS')

@section('content')
    <!-- Hero Section -->
    <section class="page-hero">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="hero-title"><i class="fas fa-car-side"></i> Our Premium Fleet</h1>
            <p class="hero-subtitle">Handpicked vehicles maintained to the highest standards - Uganda road ready</p>
        </div>
    </section>

    <!-- Fleet Showcase Section -->
    <section class="cars-section modern-section">
        <div class="container">
            <!-- Search and Filter Bar -->
            <div class="vehicle-search-bar">
                <input type="text" 
                       id="vehicleSearch" 
                       class="search-input" 
                       placeholder="Search by brand, model, or features...">
                <select id="vehicleSort" class="sort-select">
                    <option value="default">Sort by: Default</option>
                    <option value="price-low">Price: Low to High</option>
                    <option value="price-high">Price: High to Low</option>
                    <option value="seats">Most Seats</option>
                    <option value="popular">Most Popular</option>
                    <option value="rating">Highest Rated</option>
                </select>
            </div>

            <!-- Price Filter -->
            <div class="price-filter-section">
                <h3 class="filter-title"><i class="fas fa-filter"></i> Filter by Price</h3>
                <div class="price-filter-options">
                    <button class="price-filter-btn active" data-filter="all">All Vehicles</button>
                    <button class="price-filter-btn" data-filter="budget">Budget (Under 100k)</button>
                    <button class="price-filter-btn" data-filter="mid">Mid-Range (100k - 200k)</button>
                    <button class="price-filter-btn" data-filter="premium">Premium (200k+)</button>
                </div>
            </div>
            
            <!-- Category Filters -->
            <div class="category-filters" id="categoryFilters"></div>
            
            <!-- Vehicles Grid -->
            <div id="vehiclesByCategory" class="vehicles-grid modern-vehicles-grid"></div>

            <!-- Call to Action -->
            <div class="fleet-cta">
                <h3>Ready to Book Your Vehicle?</h3>
                <p>Choose your perfect ride and start your adventure today</p>
                <a href="{{ url('/bookings') }}" class="cta-btn"><i class="fas fa-calendar-check"></i> Book Now</a>
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
                    <p class="footer-text">Your trusted partner for reliable and affordable car rental services.</p>
                </div>
                <div class="footer-section">
                    <h4 class="footer-heading">Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li><a href="{{ url('/fleet') }}">Our Fleet</a></li>
                        <li><a href="{{ url('/about') }}">About Us</a></li>
                        <li><a href="{{ url('/contact') }}">Contact Us</a></li>
                        <li><a href="{{ url('/faqs') }}">FAQs</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4 class="footer-heading">Contact</h4>
                    <p class="footer-text">Get in touch with us for bookings and inquiries.</p>
                    <a href="{{ url('/contact') }}" class="footer-link">Contact Us →</a>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="footer-bottom-logo">
                    <div class="footer-bottom-logo-container">
                        <img src="{{ asset('images/MAM TOURS LOGO.jpg') }}" alt="MAM TOURS" class="footer-bottom-logo-img">
                    </div>
                    <span>MAM TOURS</span>
                </div>
                <p>&copy; <span class="current-year"></span> MAM TOURS. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Floating WhatsApp Button -->
    <a href="https://wa.me/256755943973?text=Hello%20MAM%20Tours%2C%20I%20would%20like%20to%20inquire%20about%20car%20rental%20services." target="_blank" rel="noopener" class="whatsapp-float" title="Chat with us on WhatsApp">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
        </svg>
    </a>

    <script>
    (function(){
      var y=new Date().getFullYear();
      document.querySelectorAll('.current-year').forEach(function(el){ el.textContent=y; });
    })();
    </script>
@endsection

@section('scripts')
    <script src="{{ asset('js/booking-enhanced-v2.js') }}"></script>
    <script>
        // Price filtering functionality
        document.addEventListener('DOMContentLoaded', function() {
            const priceFilterBtns = document.querySelectorAll('.price-filter-btn');
            
            priceFilterBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Remove active class from all buttons
                    priceFilterBtns.forEach(b => b.classList.remove('active'));
                    // Add active class to clicked button
                    this.classList.add('active');
                    
                    const filter = this.dataset.filter;
                    filterVehiclesByPrice(filter);
                });
            });
        });

        function filterVehiclesByPrice(filter) {
            const vehicleCards = document.querySelectorAll('.vehicle-card');
            
            vehicleCards.forEach(card => {
                const priceText = card.querySelector('.vehicle-price')?.textContent || '';
                const price = parseInt(priceText.replace(/[^0-9]/g, ''));
                
                let show = true;
                
                if (filter === 'budget') {
                    show = price < 100000;
                } else if (filter === 'mid') {
                    show = price >= 100000 && price < 200000;
                } else if (filter === 'premium') {
                    show = price >= 200000;
                }
                
                card.style.display = show ? 'block' : 'none';
            });
        }
    </script>
@endsection

@section('styles')
    <style>
        .price-filter-section {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .filter-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1a2332;
            margin-bottom: 1rem;
        }

        .price-filter-options {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .price-filter-btn {
            padding: 0.75rem 1.5rem;
            border: 2px solid #ddd;
            background: white;
            color: #1a2332;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .price-filter-btn:hover {
            border-color: #ff9800;
            color: #ff9800;
        }

        .price-filter-btn.active {
            background: linear-gradient(135deg, #ff9800 0%, #ff7c00 100%);
            color: white;
            border-color: #ff9800;
        }

        .fleet-cta {
            text-align: center;
            padding: 3rem 2rem;
            background: linear-gradient(135deg, #ff9800 0%, #ff7c00 100%);
            border-radius: 12px;
            margin-top: 3rem;
            color: white;
        }

        .fleet-cta h3 {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }

        .fleet-cta p {
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
            opacity: 0.9;
        }

        .cta-btn {
            display: inline-block;
            padding: 1rem 2rem;
            background: white;
            color: #ff9800;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .cta-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        @media (max-width: 768px) {
            .price-filter-options {
                flex-direction: column;
            }

            .price-filter-btn {
                width: 100%;
            }
        }
    </style>
@endsection

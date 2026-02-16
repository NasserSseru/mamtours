@extends('layouts.app')

@section('title', 'Explore Uganda | MAM TOURS')

@section('content')
    <!-- Hero Section -->
    <section class="page-hero explore-hero">
        <div class="hero-geometric-shapes">
            <div class="hero-shape hero-shape-1"></div>
            <div class="hero-shape hero-shape-2"></div>
            <div class="hero-shape hero-shape-3"></div>
            <div class="hero-shape hero-shape-4"></div>
        </div>
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="hero-title"><i class="fas fa-map-marked-alt"></i> Explore Uganda</h1>
            <p class="hero-subtitle">Discover the Pearl of Africa - National Parks, Wildlife & Adventure</p>
        </div>
    </section>

    <!-- Quick Stats -->
    <section class="stats-bar">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-number">10+</span>
                    <span class="stat-label">National Parks</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">1,000+</span>
                    <span class="stat-label">Bird Species</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">50%</span>
                    <span class="stat-label">World's Gorillas</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><i class="fas fa-car"></i></span>
                    <span class="stat-label">4x4 Ready</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Introduction -->
    <section class="content-section">
        <div class="container">
            <div class="intro-content">
                <h2 class="section-title text-center"><i class="fas fa-leaf"></i> The Pearl of Africa</h2>
                <p class="intro-text">
                    Uganda is home to some of Africa's most spectacular wildlife, breathtaking landscapes, and unforgettable adventures. 
                    From tracking mountain gorillas in misty forests to witnessing the powerful Murchison Falls, every destination offers 
                    a unique experience. With MAM TOURS, explore Uganda's treasures in comfort and style with our 4x4 safari vehicles.
                </p>
            </div>
        </div>
    </section>


    <!-- National Parks Grid -->
    <section class="content-section alt-bg">
        <div class="container">
            <h2 class="section-title text-center"><i class="fas fa-mountain"></i> Uganda's National Parks & Reserves</h2>
            <p class="section-description text-center">Discover wildlife, adventure, and natural beauty</p>
            
            <div class="parks-grid">
                <!-- Murchison Falls National Park -->
                <div class="park-card">
                    <div class="park-image">
                        <img src="https://images.unsplash.com/photo-1516426122078-c23e76319801?w=800&h=600&fit=crop" alt="Murchison Falls" onerror="this.src='https://via.placeholder.com/800x600/4a90e2/ffffff?text=Murchison+Falls'">
                        <div class="park-badge">Most Popular</div>
                    </div>
                    <div class="park-content">
                        <h3 class="park-title">Murchison Falls National Park</h3>
                        <div class="park-location">
                            <i class="fas fa-map-marker-alt"></i> Northwestern Uganda
                        </div>
                        <p class="park-description">
                            Uganda's largest national park, home to the spectacular Murchison Falls where the Nile explodes through a narrow gorge. 
                            Experience incredible wildlife including elephants, lions, giraffes, and hippos.
                        </p>
                        <div class="park-highlights">
                            <h4><i class="fas fa-star"></i> Highlights</h4>
                            <ul>
                                <li><i class="fas fa-check"></i> Murchison Falls boat cruise</li>
                                <li><i class="fas fa-check"></i> Big Five wildlife viewing</li>
                                <li><i class="fas fa-check"></i> Nile Delta birding</li>
                                <li><i class="fas fa-check"></i> Game drives</li>
                            </ul>
                        </div>
                        <div class="park-info">
                            <div class="info-item">
                                <i class="fas fa-road"></i>
                                <span>305 km from Kampala (5-6 hours)</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-clock"></i>
                                <span>Best: Dec-Feb, Jun-Sep</span>
                            </div>
                        </div>
                        <div class="park-directions">
                            <button class="directions-btn" onclick="showDirections('murchison')">
                                <i class="fas fa-directions"></i> Get Directions
                            </button>
                            <a href="{{ url('/bookings') }}" class="book-btn">
                                <i class="fas fa-car"></i> Book 4x4 Vehicle
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Queen Elizabeth National Park -->
                <div class="park-card">
                    <div class="park-image">
                        <img src="https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?w=800&h=600&fit=crop" alt="Queen Elizabeth" onerror="this.src='https://via.placeholder.com/800x600/4a90e2/ffffff?text=Queen+Elizabeth+Park'">
                    </div>
                    <div class="park-content">
                        <h3 class="park-title">Queen Elizabeth National Park</h3>
                        <div class="park-location">
                            <i class="fas fa-map-marker-alt"></i> Western Uganda
                        </div>
                        <p class="park-description">
                            Famous for tree-climbing lions in Ishasha sector and the Kazinga Channel boat safari. 
                            Diverse ecosystems from savannah to wetlands, hosting over 95 mammal species.
                        </p>
                        <div class="park-highlights">
                            <h4><i class="fas fa-star"></i> Highlights</h4>
                            <ul>
                                <li><i class="fas fa-check"></i> Tree-climbing lions</li>
                                <li><i class="fas fa-check"></i> Kazinga Channel cruise</li>
                                <li><i class="fas fa-check"></i> Chimpanzee tracking</li>
                                <li><i class="fas fa-check"></i> 600+ bird species</li>
                            </ul>
                        </div>
                        <div class="park-info">
                            <div class="info-item">
                                <i class="fas fa-road"></i>
                                <span>420 km from Kampala (6-7 hours)</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-clock"></i>
                                <span>Best: Jun-Sep, Dec-Feb</span>
                            </div>
                        </div>
                        <div class="park-directions">
                            <button class="directions-btn" onclick="showDirections('queen-elizabeth')">
                                <i class="fas fa-directions"></i> Get Directions
                            </button>
                            <a href="{{ url('/bookings') }}" class="book-btn">
                                <i class="fas fa-car"></i> Book 4x4 Vehicle
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Bwindi Impenetrable National Park -->
                <div class="park-card featured-park">
                    <div class="park-image">
                        <img src="https://images.unsplash.com/photo-1551632811-561732d1e306?w=800&h=600&fit=crop" alt="Bwindi" onerror="this.src='https://via.placeholder.com/800x600/4a90e2/ffffff?text=Bwindi+Forest'">
                        <div class="park-badge featured">UNESCO Site</div>
                    </div>
                    <div class="park-content">
                        <h3 class="park-title">Bwindi Impenetrable National Park</h3>
                        <div class="park-location">
                            <i class="fas fa-map-marker-alt"></i> Southwestern Uganda
                        </div>
                        <p class="park-description">
                            Home to nearly half of the world's remaining mountain gorillas. A UNESCO World Heritage Site offering 
                            the once-in-a-lifetime experience of gorilla trekking through ancient rainforest.
                        </p>
                        <div class="park-highlights">
                            <h4><i class="fas fa-star"></i> Highlights</h4>
                            <ul>
                                <li><i class="fas fa-check"></i> Mountain gorilla trekking</li>
                                <li><i class="fas fa-check"></i> Batwa cultural experience</li>
                                <li><i class="fas fa-check"></i> 350+ bird species</li>
                                <li><i class="fas fa-check"></i> Forest walks</li>
                            </ul>
                        </div>
                        <div class="park-info">
                            <div class="info-item">
                                <i class="fas fa-road"></i>
                                <span>540 km from Kampala (8-9 hours)</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-clock"></i>
                                <span>Best: Jun-Aug, Dec-Feb</span>
                            </div>
                        </div>
                        <div class="park-directions">
                            <button class="directions-btn" onclick="showDirections('bwindi')">
                                <i class="fas fa-directions"></i> Get Directions
                            </button>
                            <a href="{{ url('/bookings') }}" class="book-btn">
                                <i class="fas fa-car"></i> Book 4x4 Vehicle
                            </a>
                        </div>
                    </div>
                </div>


                <!-- Lake Mburo National Park -->
                <div class="park-card">
                    <div class="park-image">
                        <img src="https://images.unsplash.com/photo-1568375274532-f1c7d5fc6e9c?w=800&h=600&fit=crop" alt="Lake Mburo" onerror="this.src='https://via.placeholder.com/800x600/4a90e2/ffffff?text=Lake+Mburo'">
                    </div>
                    <div class="park-content">
                        <h3 class="park-title">Lake Mburo National Park</h3>
                        <div class="park-location">
                            <i class="fas fa-map-marker-alt"></i> Western Uganda
                        </div>
                        <p class="park-description">
                            Uganda's smallest savannah park, perfect for a quick safari. The only park with zebras and impalas. 
                            Enjoy boat rides, horseback safaris, and walking safaris.
                        </p>
                        <div class="park-highlights">
                            <h4><i class="fas fa-star"></i> Highlights</h4>
                            <ul>
                                <li><i class="fas fa-check"></i> Zebras and impalas</li>
                                <li><i class="fas fa-check"></i> Boat safaris on the lake</li>
                                <li><i class="fas fa-check"></i> Walking safaris</li>
                                <li><i class="fas fa-check"></i> Horseback riding</li>
                            </ul>
                        </div>
                        <div class="park-info">
                            <div class="info-item">
                                <i class="fas fa-road"></i>
                                <span>240 km from Kampala (3-4 hours)</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-clock"></i>
                                <span>Year-round destination</span>
                            </div>
                        </div>
                        <div class="park-directions">
                            <button class="directions-btn" onclick="showDirections('lake-mburo')">
                                <i class="fas fa-directions"></i> Get Directions
                            </button>
                            <a href="{{ url('/bookings') }}" class="book-btn">
                                <i class="fas fa-car"></i> Book 4x4 Vehicle
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Kidepo Valley National Park -->
                <div class="park-card">
                    <div class="park-image">
                        <img src="https://images.unsplash.com/photo-1549366021-9f761d450615?w=800&h=600&fit=crop" alt="Kidepo Valley" onerror="this.src='https://via.placeholder.com/800x600/4a90e2/ffffff?text=Kidepo+Valley'">
                        <div class="park-badge">Remote Gem</div>
                    </div>
                    <div class="park-content">
                        <h3 class="park-title">Kidepo Valley National Park</h3>
                        <div class="park-location">
                            <i class="fas fa-map-marker-alt"></i> Northeastern Uganda
                        </div>
                        <p class="park-description">
                            Uganda's most remote and pristine wilderness. Voted Africa's best wilderness park, offering 
                            spectacular scenery and rare wildlife species not found elsewhere in Uganda.
                        </p>
                        <div class="park-highlights">
                            <h4><i class="fas fa-star"></i> Highlights</h4>
                            <ul>
                                <li><i class="fas fa-check"></i> Cheetahs and ostriches</li>
                                <li><i class="fas fa-check"></i> Karamojong culture</li>
                                <li><i class="fas fa-check"></i> Dramatic landscapes</li>
                                <li><i class="fas fa-check"></i> Rare wildlife species</li>
                            </ul>
                        </div>
                        <div class="park-info">
                            <div class="info-item">
                                <i class="fas fa-road"></i>
                                <span>570 km from Kampala (10-12 hours)</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-clock"></i>
                                <span>Best: Sep-Mar</span>
                            </div>
                        </div>
                        <div class="park-directions">
                            <button class="directions-btn" onclick="showDirections('kidepo')">
                                <i class="fas fa-directions"></i> Get Directions
                            </button>
                            <a href="{{ url('/bookings') }}" class="book-btn">
                                <i class="fas fa-car"></i> Book 4x4 Vehicle
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Kibale National Park -->
                <div class="park-card">
                    <div class="park-image">
                        <img src="https://images.unsplash.com/photo-1564760055775-d63b17a55c44?w=800&h=600&fit=crop" alt="Kibale" onerror="this.src='https://via.placeholder.com/800x600/4a90e2/ffffff?text=Kibale+Forest'">
                    </div>
                    <div class="park-content">
                        <h3 class="park-title">Kibale National Park</h3>
                        <div class="park-location">
                            <i class="fas fa-map-marker-alt"></i> Western Uganda
                        </div>
                        <p class="park-description">
                            The primate capital of the world with 13 primate species including over 1,500 chimpanzees. 
                            Offers the best chimpanzee tracking experience in East Africa.
                        </p>
                        <div class="park-highlights">
                            <h4><i class="fas fa-star"></i> Highlights</h4>
                            <ul>
                                <li><i class="fas fa-check"></i> Chimpanzee tracking</li>
                                <li><i class="fas fa-check"></i> 13 primate species</li>
                                <li><i class="fas fa-check"></i> Bigodi Wetland Sanctuary</li>
                                <li><i class="fas fa-check"></i> Nature walks</li>
                            </ul>
                        </div>
                        <div class="park-info">
                            <div class="info-item">
                                <i class="fas fa-road"></i>
                                <span>358 km from Kampala (5-6 hours)</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-clock"></i>
                                <span>Best: Feb-May, Sep-Nov</span>
                            </div>
                        </div>
                        <div class="park-directions">
                            <button class="directions-btn" onclick="showDirections('kibale')">
                                <i class="fas fa-directions"></i> Get Directions
                            </button>
                            <a href="{{ url('/bookings') }}" class="book-btn">
                                <i class="fas fa-car"></i> Book 4x4 Vehicle
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Other Attractions -->
    <section class="content-section">
        <div class="container">
            <h2 class="section-title text-center"><i class="fas fa-compass"></i> More Destinations</h2>
            <div class="attractions-grid">
                <div class="attraction-card">
                    <div class="attraction-icon"><i class="fas fa-water"></i></div>
                    <h3>Jinja - Source of the Nile</h3>
                    <p>White water rafting, bungee jumping, and boat cruises. 80 km from Kampala.</p>
                    <button class="directions-btn small" onclick="showDirections('jinja')">
                        <i class="fas fa-directions"></i> Directions
                    </button>
                </div>
                <div class="attraction-card">
                    <div class="attraction-icon"><i class="fas fa-mountain"></i></div>
                    <h3>Rwenzori Mountains</h3>
                    <p>Mountains of the Moon - hiking and mountaineering. 375 km from Kampala.</p>
                    <button class="directions-btn small" onclick="showDirections('rwenzori')">
                        <i class="fas fa-directions"></i> Directions
                    </button>
                </div>
                <div class="attraction-card">
                    <div class="attraction-icon"><i class="fas fa-tree"></i></div>
                    <h3>Mgahinga Gorilla Park</h3>
                    <p>Gorilla tracking and golden monkey viewing. 540 km from Kampala.</p>
                    <button class="directions-btn small" onclick="showDirections('mgahinga')">
                        <i class="fas fa-directions"></i> Directions
                    </button>
                </div>
                <div class="attraction-card">
                    <div class="attraction-icon"><i class="fas fa-hiking"></i></div>
                    <h3>Sipi Falls</h3>
                    <p>Three-tiered waterfalls and coffee tours. 277 km from Kampala.</p>
                    <button class="directions-btn small" onclick="showDirections('sipi')">
                        <i class="fas fa-directions"></i> Directions
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Directions Modal -->
    <div class="directions-modal" id="directionsModal" onclick="closeDirections(event)">
        <div class="modal-content directions-content" onclick="event.stopPropagation()">
            <button class="modal-close" onclick="closeDirections()"><i class="fas fa-times"></i></button>
            <h2 id="directionTitle"><i class="fas fa-map-marked-alt"></i> Directions</h2>
            <div id="directionDetails"></div>
        </div>
    </div>

    <!-- CTA Section -->
    <section class="cta-section modern-cta">
        <div class="cta-geometric-shapes">
            <div class="cta-shape cta-shape-1"></div>
            <div class="cta-shape cta-shape-2"></div>
            <div class="cta-shape cta-shape-3"></div>
        </div>
        <div class="container">
            <div class="cta-content modern-cta-content">
                <h2 class="cta-title"><i class="fas fa-car-side"></i> Ready to Explore Uganda?</h2>
                <p class="cta-subtitle">Book your 4x4 safari vehicle and start your adventure today</p>
                <div class="cta-actions">
                    <a href="{{ url('/bookings') }}" class="cta-button modern-cta-button">
                        <i class="fas fa-calendar-check"></i> Book Your Safari Vehicle
                    </a>
                    <a href="https://wa.me/256755943973?text=Hello%20MAM%20Tours%2C%20I%20want%20to%20explore%20Uganda" target="_blank" rel="noopener" class="cta-button cta-button-secondary">
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
                        <li><a href="{{ url('/explore-uganda') }}">Explore Uganda</a></li>
                        <li><a href="{{ url('/bookings') }}">Book a Car</a></li>
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
    <a href="https://wa.me/256755943973?text=Hello%20MAM%20Tours%2C%20I%20want%20to%20explore%20Uganda" target="_blank" rel="noopener" class="whatsapp-float" title="Chat with us on WhatsApp">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
        </svg>
    </a>
@endsection

@section('scripts')
    <script src="{{ asset('js/explore-uganda.js') }}"></script>
    <script>
    (function(){
      var y=new Date().getFullYear();
      document.querySelectorAll('.current-year').forEach(function(el){ el.textContent=y; });
    })();
    </script>
@endsection

// API Base URL
const API_BASE = '/api';

// DOM Elements
const bookingForm = document.getElementById('bookingForm');
const carSelect = document.getElementById('carSelect');
const selectedCarIdInput = document.getElementById('selectedCarId');
const carInfo = document.getElementById('carInfo');
const submitBtn = document.getElementById('submitBtn');
const submitText = document.getElementById('submitText');
const submitLoader = document.getElementById('submitLoader');
const toast = document.getElementById('toast');
const toastTitle = document.getElementById('toastTitle');
const toastMessage = document.getElementById('toastMessage');
const startDateInput = document.getElementById('startDate');
const endDateInput = document.getElementById('endDate');

// State
let allCars = [];
let selectedCar = null;
let selectedCategory = 'all';
let categories = [];
let comparisonList = [];
const isBookingPage = !!bookingForm;
const isHome = !isBookingPage;

function getCarImage(brand, model, category) {
    const m = (model || '').toLowerCase().trim();
    const b = (brand || '').toLowerCase().trim();
    const c = (category || '').toLowerCase().trim();
    const map = {
        'noah': 'images/Toyota Noah.jpg',
        'prado': 'images/Prado.jpg',
        'hilux': 'images/Hilux.jpg',
        'hiace': 'images/Toyota Hiace.jpg',
        'fortuner': 'images/Toyota Fortuner.jpg',
        'harrier': 'images/Harrier.jpg',
        'rav4': 'images/Rav 4.jpeg',
        'rav 4': 'images/Rav 4.jpeg',
        'auris': 'images/Auris.jpg',
        'avensis': 'images/Toyota Avensis.jpg',
        'fielder': 'images/Toyota Fielder.jpg',
        'isis': 'images/Toyota Isis.jpg',
        'spacio': 'images/Spacio.jpg',
        'rumion': 'images/Rumion.jpg',
        'runx': 'images/Toyota Runx.jpg',
        'allex': 'images/Toyota Allex.jpg',
        'passo': 'images/Passo.jpg',
        'premio': 'images/Premio.jpg',
        'alphard': 'images/Alphard.jpeg',
        'land cruiser': 'images/Land cruiser.jpg',
        'landcruiser': 'images/Land cruiser.jpg',
        'hilux surf': 'images/Hilux Surf.jpg',
        'kluger': 'images/Kruger.jpg',
        'vanguard': 'images/Vangurad Toyota.jpg',
        'wrangler': 'images/jeep wrangler.jpg',
        'grand cherokee': 'images/Jeep Grand Cherokee.jpg',
        's500': 'images/s class.jpeg',
        's class': 'images/s class.jpeg',
        's-class': 'images/s class.jpeg',
        'gle': 'images/Gle.jpeg',
        'xf': 'images/Jaguar xf 2015.jpg',
    };
    if (map[m]) return map[m];
    if (m === 'xf' && b.includes('jaguar')) return 'images/Jaguar xf 2015.jpg';
    if (b.includes('jeep')) {
        if (m.includes('wrangler')) return 'images/jeep wrangler.jpg';
        if (m.includes('grand cherokee')) return 'images/Jeep Grand Cherokee.jpg';
    }
    if (b.includes('mercedes')) {
        if (m.includes('s class') || m.includes('s500')) return 'images/s class.jpeg';
        if (m.includes('gle')) return 'images/Gle.jpeg';
    }
    if (c === 'suv') return 'images/Rav 4.jpeg';
    if (c === 'minivan' || c === 'van') return 'images/Toyota Noah.jpg';
    if (c === 'pickup') return 'images/Hilux.jpg';
    if (c === 'luxury sedan' || c === 'luxury suv') return 'images/s class.jpeg';
    if (c === 'sedan' || c === 'hatchback') return 'images/Sedan car.jpg';
    if (b.includes('toyota')) return 'images/Toyota.jpeg';
    return 'images/car logo.png';
}

window.getCarImage = getCarImage;

// Get default description based on category
function getDefaultDescription(vehicle) {
    if (vehicle.description) return vehicle.description;
    
    const category = (vehicle.category || '').toLowerCase();
    const descriptions = {
        'suv': `Perfect for Uganda's diverse terrain. This ${vehicle.brand} ${vehicle.model} offers comfort and capability for both city driving and safari adventures.`,
        'minivan': `Spacious and comfortable ${vehicle.brand} ${vehicle.model}, ideal for family trips or group travel across Uganda. Perfect for airport transfers and long journeys.`,
        'sedan': `Elegant and fuel-efficient ${vehicle.brand} ${vehicle.model}, perfect for business trips and city driving in Kampala and beyond.`,
        'hatchback': `Compact and economical ${vehicle.brand} ${vehicle.model}, great for navigating Kampala's streets with ease and efficiency.`,
        'pickup': `Rugged ${vehicle.brand} ${vehicle.model} built for Uganda's roads. Perfect for cargo transport and off-road adventures.`,
        'luxury': `Experience premium comfort in this ${vehicle.brand} ${vehicle.model}. Perfect for special occasions, business meetings, and VIP transport.`,
        '4x4': `Adventure-ready ${vehicle.brand} ${vehicle.model} designed for Uganda's national parks and mountain terrain. Ideal for gorilla trekking and safari trips.`
    };
    
    return descriptions[category] || `Reliable ${vehicle.brand} ${vehicle.model} ready for your journey across Uganda. Well-maintained and road-ready.`;
}

// Get default features based on category
function getDefaultFeatures(vehicle) {
    if (vehicle.features && vehicle.features.length > 0) return vehicle.features;
    
    const category = (vehicle.category || '').toLowerCase();
    const baseFeatures = ['Air Conditioning', 'Power Steering', 'Radio/USB'];
    
    const categoryFeatures = {
        'suv': [...baseFeatures, '4WD Capability', 'Roof Rack', 'High Ground Clearance'],
        'minivan': [...baseFeatures, 'Spacious Interior', 'Multiple Seating Rows', 'Large Luggage Space'],
        'sedan': [...baseFeatures, 'Fuel Efficient', 'Comfortable Seats', 'Smooth Ride'],
        'hatchback': [...baseFeatures, 'Compact Size', 'Easy Parking', 'Fuel Efficient'],
        'pickup': [...baseFeatures, 'Cargo Bed', 'Towing Capacity', 'Off-Road Ready'],
        'luxury': [...baseFeatures, 'Leather Seats', 'Premium Sound', 'Climate Control', 'Sunroof'],
        '4x4': [...baseFeatures, '4WD System', 'Off-Road Tires', 'High Clearance', 'Winch Ready']
    };
    
    return categoryFeatures[category] || baseFeatures;
}

// Enhanced vehicle rendering with more details
function renderVehicleCard(vehicle) {
    const availability = vehicle.isAvailable ? 'Available' : 'Unavailable';
    const badgeClass = vehicle.isAvailable ? '' : 'unavailable';
    const buttonDisabled = !vehicle.isAvailable ? 'disabled' : '';
    
    // Use carPicture if available, otherwise fall back to getCarImage
    let carImage;
    if (vehicle.carPicture) {
        // Check if it's a full URL (ImgBB) or a local path
        if (vehicle.carPicture.startsWith('http://') || vehicle.carPicture.startsWith('https://')) {
            carImage = vehicle.carPicture; // ImgBB URL
        } else if (vehicle.carPicture.startsWith('images/') || vehicle.carPicture.startsWith('/')) {
            carImage = vehicle.carPicture; // Default or absolute path
        } else {
            carImage = `/storage/${vehicle.carPicture}`; // Local storage path
        }
    } else {
        carImage = getCarImage(vehicle.brand, vehicle.model, vehicle.category);
    }
    
    const description = getDefaultDescription(vehicle);
    const features = getDefaultFeatures(vehicle);
    const transmission = vehicle.transmission || 'Automatic';
    const fuelType = vehicle.fuel_type || 'Petrol';
    const year = vehicle.year || '';
    const doors = vehicle.doors || '4';
    const luggage = vehicle.luggage_capacity || '3 Bags';
    const rating = vehicle.rating || 0;
    const isFeatured = vehicle.is_featured || false;
    
    // Generate star rating
    const stars = Array.from({length: 5}, (_, i) => 
        i < Math.floor(rating) ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>'
    ).join('');
    
    return `
        <div class="vehicle-card enhanced-card" data-vehicle-id="${vehicle.id}">
            ${isFeatured ? '<div class="featured-ribbon"><i class="fas fa-star"></i> Featured</div>' : ''}
            
            <div class="vehicle-image-container">
                <img src="${carImage}" 
                     alt="${vehicle.brand} ${vehicle.model}" 
                     class="vehicle-image"
                     onerror="this.src='images/car logo.png'">
                <span class="vehicle-badge ${badgeClass}">${availability}</span>
                ${year ? `<span class="vehicle-year-badge">${year}</span>` : ''}
                <div class="vehicle-overlay">
                    <button class="quick-view-btn" onclick="showQuickView(${vehicle.id})" title="Quick View">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="compare-btn" onclick="toggleCompare(${vehicle.id})" title="Add to Compare">
                        <i class="fas fa-balance-scale"></i>
                    </button>
                </div>
            </div>
            
            <div class="vehicle-info">
                <div class="vehicle-header">
                    <h3 class="vehicle-title">
                        ${vehicle.brand} ${vehicle.model}
                    </h3>
                    ${rating > 0 ? `<div class="vehicle-rating" title="${rating} stars">${stars}</div>` : ''}
                </div>
                
                <span class="vehicle-category">${vehicle.category || 'Other'}</span>
                
                <p class="vehicle-description">${description.substring(0, 120)}...</p>
                
                <div class="vehicle-specs-grid">
                    <div class="vehicle-spec">
                        <span class="vehicle-spec-icon"><i class="fas fa-users"></i></span>
                        <span class="vehicle-spec-text">${vehicle.seats} Seats</span>
                    </div>
                    <div class="vehicle-spec">
                        <span class="vehicle-spec-icon"><i class="fas fa-cog"></i></span>
                        <span class="vehicle-spec-text">${transmission}</span>
                    </div>
                    <div class="vehicle-spec">
                        <span class="vehicle-spec-icon"><i class="fas fa-gas-pump"></i></span>
                        <span class="vehicle-spec-text">${fuelType}</span>
                    </div>
                    <div class="vehicle-spec">
                        <span class="vehicle-spec-icon"><i class="fas fa-suitcase"></i></span>
                        <span class="vehicle-spec-text">${luggage}</span>
                    </div>
                    <div class="vehicle-spec">
                        <span class="vehicle-spec-icon"><i class="fas fa-door-open"></i></span>
                        <span class="vehicle-spec-text">${doors} Doors</span>
                    </div>
                    <div class="vehicle-spec">
                        <span class="vehicle-spec-icon"><i class="fas fa-id-card"></i></span>
                        <span class="vehicle-spec-text">${vehicle.numberPlate}</span>
                    </div>
                </div>
                
                <div class="vehicle-features">
                    <h4 class="features-title"><i class="fas fa-check-circle"></i> Key Features</h4>
                    <ul class="features-list">
                        ${features.slice(0, 4).map(f => `<li><i class="fas fa-check"></i> ${f}</li>`).join('')}
                    </ul>
                </div>
                
                <div class="vehicle-pricing">
                    <div class="price-info">
                        <span class="price-label">Daily Rate</span>
                        <span class="vehicle-price">UGX ${vehicle.dailyRate.toLocaleString()}</span>
                    </div>
                    ${vehicle.booking_count > 0 ? `<div class="booking-stats"><i class="fas fa-fire"></i> ${vehicle.booking_count} bookings</div>` : ''}
                </div>
                
                <div class="vehicle-actions">
                    <button class="select-vehicle-btn primary-action" 
                            onclick="selectVehicle(${vehicle.id}, '${vehicle.brand} ${vehicle.model}')"
                            ${buttonDisabled}>
                        <i class="fas fa-calendar-check"></i>
                        ${vehicle.isAvailable ? 'Book Now' : 'Not Available'}
                    </button>
                    <button class="details-btn secondary-action" 
                            onclick="showQuickView(${vehicle.id})">
                        <i class="fas fa-info-circle"></i> Details
                    </button>
                </div>
            </div>
        </div>
    `;
}

// Show quick view modal
function showQuickView(carId) {
    const vehicle = allCars.find(c => c.id === carId);
    if (!vehicle) return;
    
    const features = getDefaultFeatures(vehicle);
    const description = getDefaultDescription(vehicle);
    
    // Handle car image path
    let carImage;
    if (vehicle.carPicture) {
        if (vehicle.carPicture.startsWith('images/')) {
            carImage = vehicle.carPicture;
        } else if (vehicle.carPicture.startsWith('/')) {
            carImage = vehicle.carPicture;
        } else {
            carImage = `images/${vehicle.carPicture}`;
        }
    } else {
        carImage = getCarImage(vehicle.brand, vehicle.model, vehicle.category);
    }
    
    const modalHTML = `
        <div class="quick-view-modal" id="quickViewModal" onclick="closeQuickView(event)">
            <div class="modal-content" onclick="event.stopPropagation()">
                <button class="modal-close" onclick="closeQuickView()"><i class="fas fa-times"></i></button>
                
                <div class="modal-grid">
                    <div class="modal-image">
                        <img src="${carImage}" alt="${vehicle.brand} ${vehicle.model}" onerror="this.src='images/car logo.png'">
                        ${vehicle.is_featured ? '<div class="featured-badge"><i class="fas fa-star"></i> Featured</div>' : ''}
                    </div>
                    
                    <div class="modal-details">
                        <h2>${vehicle.brand} ${vehicle.model}</h2>
                        <span class="modal-category">${vehicle.category || 'Other'}</span>
                        
                        <p class="modal-description">${description}</p>
                        
                        <div class="modal-specs">
                            <div class="spec-item">
                                <i class="fas fa-users"></i>
                                <span>${vehicle.seats} Seats</span>
                            </div>
                            <div class="spec-item">
                                <i class="fas fa-cog"></i>
                                <span>${vehicle.transmission || 'Automatic'}</span>
                            </div>
                            <div class="spec-item">
                                <i class="fas fa-gas-pump"></i>
                                <span>${vehicle.fuel_type || 'Petrol'}</span>
                            </div>
                            <div class="spec-item">
                                <i class="fas fa-suitcase"></i>
                                <span>${vehicle.luggage_capacity || '3 Bags'}</span>
                            </div>
                            <div class="spec-item">
                                <i class="fas fa-door-open"></i>
                                <span>${vehicle.doors || '4'} Doors</span>
                            </div>
                            <div class="spec-item">
                                <i class="fas fa-id-card"></i>
                                <span>${vehicle.numberPlate}</span>
                            </div>
                        </div>
                        
                        <div class="modal-features">
                            <h3><i class="fas fa-check-circle"></i> All Features</h3>
                            <ul>
                                ${features.map(f => `<li><i class="fas fa-check"></i> ${f}</li>`).join('')}
                            </ul>
                        </div>
                        
                        <div class="modal-price">
                            <span class="price-label">Daily Rate</span>
                            <span class="price-value">UGX ${vehicle.dailyRate.toLocaleString()}</span>
                        </div>
                        
                        <button class="modal-book-btn" onclick="selectVehicle(${vehicle.id}, '${vehicle.brand} ${vehicle.model}'); closeQuickView();" ${!vehicle.isAvailable ? 'disabled' : ''}>
                            <i class="fas fa-calendar-check"></i> ${vehicle.isAvailable ? 'Book This Vehicle' : 'Not Available'}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    document.body.style.overflow = 'hidden';
}

// Close quick view modal
function closeQuickView(event) {
    if (event && event.target.className !== 'quick-view-modal') return;
    const modal = document.getElementById('quickViewModal');
    if (modal) {
        modal.remove();
        document.body.style.overflow = '';
    }
}

// Toggle comparison
function toggleCompare(carId) {
    const index = comparisonList.indexOf(carId);
    if (index > -1) {
        comparisonList.splice(index, 1);
    } else {
        if (comparisonList.length >= 3) {
            showToast('Limit Reached', 'You can compare up to 3 vehicles at a time');
            return;
        }
        comparisonList.push(carId);
    }
    updateComparisonBar();
}

// Update comparison bar
function updateComparisonBar() {
    let bar = document.getElementById('comparisonBar');
    if (!bar && comparisonList.length > 0) {
        bar = document.createElement('div');
        bar.id = 'comparisonBar';
        bar.className = 'comparison-bar';
        document.body.appendChild(bar);
    }
    
    if (comparisonList.length === 0 && bar) {
        bar.remove();
        return;
    }
    
    if (bar) {
        bar.innerHTML = `
            <div class="comparison-content">
                <span class="comparison-title"><i class="fas fa-balance-scale"></i> Compare (${comparisonList.length}/3)</span>
                <button class="comparison-btn" onclick="showComparison()" ${comparisonList.length < 2 ? 'disabled' : ''}>
                    <i class="fas fa-eye"></i> View Comparison
                </button>
                <button class="comparison-clear" onclick="clearComparison()">
                    <i class="fas fa-times"></i> Clear
                </button>
            </div>
        `;
    }
}

// Show comparison modal
function showComparison() {
    if (comparisonList.length < 2) return;
    
    const vehicles = comparisonList.map(id => allCars.find(c => c.id === id)).filter(Boolean);
    
    const modalHTML = `
        <div class="quick-view-modal" id="comparisonModal" onclick="closeComparison(event)">
            <div class="modal-content comparison-modal" onclick="event.stopPropagation()">
                <button class="modal-close" onclick="closeComparison()"><i class="fas fa-times"></i></button>
                
                <h2><i class="fas fa-balance-scale"></i> Vehicle Comparison</h2>
                
                <div class="comparison-grid">
                    ${vehicles.map(v => {
                        let vImage;
                        if (v.carPicture) {
                            if (v.carPicture.startsWith('images/') || v.carPicture.startsWith('/')) {
                                vImage = v.carPicture;
                            } else {
                                vImage = `images/${v.carPicture}`;
                            }
                        } else {
                            vImage = getCarImage(v.brand, v.model, v.category);
                        }
                        return `
                        <div class="comparison-card">
                            <img src="${vImage}" alt="${v.brand} ${v.model}" onerror="this.src='images/car logo.png'">
                            <h3>${v.brand} ${v.model}</h3>
                            <div class="comparison-specs">
                                <div class="spec-row"><strong>Price:</strong> UGX ${v.dailyRate.toLocaleString()}/day</div>
                                <div class="spec-row"><strong>Seats:</strong> ${v.seats}</div>
                                <div class="spec-row"><strong>Transmission:</strong> ${v.transmission || 'Automatic'}</div>
                                <div class="spec-row"><strong>Fuel:</strong> ${v.fuel_type || 'Petrol'}</div>
                                <div class="spec-row"><strong>Category:</strong> ${v.category || 'Other'}</div>
                                <div class="spec-row"><strong>Status:</strong> ${v.isAvailable ? '✅ Available' : '❌ Unavailable'}</div>
                            </div>
                            <button class="select-vehicle-btn" onclick="selectVehicle(${v.id}, '${v.brand} ${v.model}'); closeComparison();" ${!v.isAvailable ? 'disabled' : ''}>
                                ${v.isAvailable ? 'Book Now' : 'Not Available'}
                            </button>
                        </div>
                        `;
                    }).join('')}
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    document.body.style.overflow = 'hidden';
}

// Close comparison modal
function closeComparison(event) {
    if (event && event.target.className !== 'quick-view-modal') return;
    const modal = document.getElementById('comparisonModal');
    if (modal) {
        modal.remove();
        document.body.style.overflow = '';
    }
}

// Clear comparison
function clearComparison() {
    comparisonList = [];
    updateComparisonBar();
}

// Make functions globally available
window.showQuickView = showQuickView;
window.closeQuickView = closeQuickView;
window.toggleCompare = toggleCompare;
window.showComparison = showComparison;
window.closeComparison = closeComparison;
window.clearComparison = clearComparison;

// Render vehicles
function renderVehicles(vehicles = allCars) {
    const container = document.getElementById('vehiclesByCategory') || document.getElementById('vehiclesGrid');
    if (!container) return;
    
    if (vehicles.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <div class="empty-state-icon">🚗</div>
                <h3 class="empty-state-title">No Vehicles Found</h3>
                <p class="empty-state-text">Try adjusting your filters or search terms</p>
            </div>
        `;
        return;
    }
    
    // Sort featured vehicles first
    const sorted = [...vehicles].sort((a, b) => {
        if (a.is_featured && !b.is_featured) return -1;
        if (!a.is_featured && b.is_featured) return 1;
        return 0;
    });
    
    container.innerHTML = sorted.map(car => renderVehicleCard(car)).join('');
}

// Filter and search functionality
function filterVehicles() {
    const searchTerm = document.getElementById('vehicleSearch')?.value.toLowerCase() || '';
    const selectedCategory = document.querySelector('.category-filter.active')?.dataset.category || 'all';
    
    const filtered = allCars.filter(car => {
        const matchesSearch = car.brand.toLowerCase().includes(searchTerm) || 
                            car.model.toLowerCase().includes(searchTerm) ||
                            (car.description || '').toLowerCase().includes(searchTerm);
        const matchesCategory = selectedCategory === 'all' || 
                              (car.category && car.category.toLowerCase() === selectedCategory);
        return matchesSearch && matchesCategory;
    });
    
    renderVehicles(filtered);
}

// Sort functionality
function sortVehicles() {
    const sortBy = document.getElementById('vehicleSort')?.value;
    let sorted = [...allCars];
    
    switch(sortBy) {
        case 'price-low':
            sorted.sort((a, b) => a.dailyRate - b.dailyRate);
            break;
        case 'price-high':
            sorted.sort((a, b) => b.dailyRate - a.dailyRate);
            break;
        case 'seats':
            sorted.sort((a, b) => b.seats - a.seats);
            break;
        case 'popular':
            sorted.sort((a, b) => (b.booking_count || 0) - (a.booking_count || 0));
            break;
        case 'rating':
            sorted.sort((a, b) => (b.rating || 0) - (a.rating || 0));
            break;
        default:
            sorted.sort((a, b) => a.brand.localeCompare(b.brand));
    }
    
    renderVehicles(sorted);
}

// Select vehicle
function selectVehicle(carId, carName) {
    selectedCar = allCars.find(c => c.id === carId);
    if (!selectedCar) return;
    
    if (selectedCarIdInput) selectedCarIdInput.value = carId;
    if (carSelect) carSelect.value = carName;
    if (carInfo) carInfo.textContent = `${carName} selected - UGX ${selectedCar.dailyRate.toLocaleString()}/day`;
    
    // Scroll to booking form
    if (bookingForm) {
        bookingForm.scrollIntoView({ behavior: 'smooth' });
    }
}

// Load cars
async function loadCars() {
    try {
        const response = await fetch(`${API_BASE}/cars`);
        allCars = await response.json();
        
        // Extract categories
        categories = [...new Set(allCars.map(c => c.category).filter(Boolean))];
        
        renderVehicles();
        setupCategoryFilters();
    } catch (error) {
        console.error('Error loading cars:', error);
        showToast('Error', 'Failed to load vehicles.');
    }
}

// Setup category filters
function setupCategoryFilters() {
    const filterContainer = document.getElementById('categoryFilters');
    if (!filterContainer) return;
    
    let html = '<button class="category-filter active" data-category="all"><i class="fas fa-car"></i> All Vehicles</button>';
    
    const categoryIcons = {
        'suv': 'fa-truck-pickup',
        'sedan': 'fa-car',
        'minivan': 'fa-shuttle-van',
        'hatchback': 'fa-car-side',
        'pickup': 'fa-truck',
        'luxury': 'fa-gem',
        '4x4': 'fa-mountain'
    };
    
    categories.forEach(cat => {
        const icon = categoryIcons[cat.toLowerCase()] || 'fa-car';
        html += `<button class="category-filter" data-category="${cat.toLowerCase()}"><i class="fas ${icon}"></i> ${cat}</button>`;
    });
    
    filterContainer.innerHTML = html;
    
    // Add event listeners
    document.querySelectorAll('.category-filter').forEach(btn => {
        btn.addEventListener('click', (e) => {
            document.querySelectorAll('.category-filter').forEach(b => b.classList.remove('active'));
            e.currentTarget.classList.add('active');
            filterVehicles();
        });
    });
}

// Show toast
function showToast(title, message) {
    if (!toast) return;
    if (toastTitle) toastTitle.textContent = title;
    if (toastMessage) toastMessage.textContent = message;
    toast.classList.add('show');
    
    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}

// Make functions globally available
window.selectVehicle = selectVehicle;
window.filterVehicles = filterVehicles;
window.sortVehicles = sortVehicles;

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    loadCars();
    
    // Setup search
    document.getElementById('vehicleSearch')?.addEventListener('input', filterVehicles);
    
    // Setup sort
    document.getElementById('vehicleSort')?.addEventListener('change', sortVehicles);
    
    // Setup booking form
    if (bookingForm) {
        bookingForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            if (!selectedCar) {
                showToast('Error', 'Please select a vehicle');
                return;
            }
            
            const formData = {
                carId: selectedCar.id,
                customerName: document.getElementById('customerName').value,
                startDate: startDateInput.value,
                endDate: endDateInput.value
            };
            
            try {
                const response = await fetch(`${API_BASE}/bookings`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(formData)
                });
                
                if (response.ok) {
                    showToast('Success', 'Booking created successfully! Redirecting...');
                    bookingForm.reset();
                    selectedCar = null;
                    if (selectedCarIdInput) selectedCarIdInput.value = '';
                    if (carSelect) carSelect.value = '';
                    if (carInfo) carInfo.textContent = 'Click on a vehicle above to select it';
                    
                    // Redirect to dashboard after 2 seconds
                    setTimeout(() => {
                        window.location.href = '/dashboard';
                    }, 2000);
                } else {
                    const error = await response.json();
                    showToast('Error', error.error || 'Failed to create booking');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Error', 'Failed to create booking');
            }
        });
    }
});

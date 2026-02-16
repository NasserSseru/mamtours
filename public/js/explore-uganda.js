// Directions data for Uganda destinations
const directionsData = {
    'murchison': {
        title: 'Murchison Falls National Park',
        distance: '305 km from Kampala',
        duration: '5-6 hours drive',
        route: [
            'Start from Kampala city center',
            'Take Kampala-Gulu Highway (A104) northbound',
            'Pass through Luwero town (75 km)',
            'Continue through Nakasongola (105 km)',
            'Pass Masindi town (215 km) - good place for lunch',
            'Turn right at Kichumbanyobo junction',
            'Follow signs to Murchison Falls National Park',
            'Enter through Kichumbanyobo Gate or Bugungu Gate'
        ],
        tips: [
            '4x4 vehicle recommended for park roads',
            'Fuel up in Masindi - last major town',
            'Park entry fees: Foreign adults $40, East African residents UGX 20,000',
            'Book accommodation in advance during peak season',
            'Carry cash for park fees and tips'
        ],
        coordinates: '2.2495° N, 31.7167° E'
    },
    'queen-elizabeth': {
        title: 'Queen Elizabeth National Park',
        distance: '420 km from Kampala',
        duration: '6-7 hours drive',
        route: [
            'Start from Kampala via Masaka Road',
            'Pass through Mpigi (37 km)',
            'Continue to Masaka town (130 km) - breakfast stop',
            'Head towards Mbarara (270 km)',
            'Turn left at Katunguru towards Kasese',
            'Enter park through Katunguru Gate or Kabatoro Gate',
            'Follow signs to your lodge/sector'
        ],
        tips: [
            'Stop at the Equator monument (72 km from Kampala)',
            'Mbarara is good for lunch and fuel',
            'Park entry fees: Foreign adults $40, East African residents UGX 20,000',
            'Visit Ishasha sector for tree-climbing lions',
            'Book Kazinga Channel boat cruise in advance'
        ],
        coordinates: '0.1807° S, 29.8756° E'
    },
    'bwindi': {
        title: 'Bwindi Impenetrable National Park',
        distance: '540 km from Kampala',
        duration: '8-9 hours drive',
        route: [
            'Start from Kampala via Masaka Road',
            'Pass through Masaka (130 km)',
            'Continue to Mbarara (270 km) - lunch stop',
            'Head towards Kabale (420 km)',
            'From Kabale, follow signs to Bwindi',
            'Choose sector: Buhoma, Ruhija, Rushaga, or Nkuringo',
            'Roads are winding - drive carefully'
        ],
        tips: [
            'Gorilla permits cost $700 per person - book months in advance',
            'Stay overnight near the park before trekking',
            '4x4 vehicle essential - roads are steep and winding',
            'Carry warm clothing - it gets cold in the mountains',
            'Hire a porter to support local community',
            'Fitness level: moderate to challenging'
        ],
        coordinates: '1.0644° S, 29.6783° E'
    },
    'lake-mburo': {
        title: 'Lake Mburo National Park',
        distance: '240 km from Kampala',
        duration: '3-4 hours drive',
        route: [
            'Start from Kampala via Masaka Road',
            'Pass through Mpigi (37 km)',
            'Continue to Masaka (130 km)',
            'Turn right at Lyantonde towards Mbarara',
            'Look for park signs at Sanga',
            'Enter through Sanga Gate or Nshara Gate',
            'Perfect for a weekend getaway'
        ],
        tips: [
            'Closest park to Kampala - ideal for short trips',
            'Walking safaris available - unique experience',
            'Boat rides on Lake Mburo at sunset',
            'Horseback safaris offered',
            'Park entry fees: Foreign adults $40, East African residents UGX 20,000',
            'Good for families and first-time safari goers'
        ],
        coordinates: '0.6083° S, 30.9500° E'
    },
    'kidepo': {
        title: 'Kidepo Valley National Park',
        distance: '570 km from Kampala',
        duration: '10-12 hours drive (or fly)',
        route: [
            'Start from Kampala via Kampala-Gulu Highway',
            'Pass through Luwero, Nakasongola, Karuma',
            'Continue to Gulu town (333 km) - overnight recommended',
            'From Gulu, head to Kitgum (150 km)',
            'Continue to Kidepo (87 km from Kitgum)',
            'Road conditions vary - 4x4 essential',
            'Consider flying from Entebbe (1.5 hours)'
        ],
        tips: [
            'Most remote park - plan well',
            'Fly from Entebbe for convenience (Aerolink Uganda)',
            'Limited accommodation - book in advance',
            'Carry extra fuel and supplies',
            'Visit Karamojong manyattas for cultural experience',
            'Best wildlife viewing: September to March',
            'Unique species: cheetahs, ostriches, bat-eared foxes'
        ],
        coordinates: '3.9167° N, 33.7333° E'
    },
    'kibale': {
        title: 'Kibale National Park',
        distance: '358 km from Kampala',
        duration: '5-6 hours drive',
        route: [
            'Start from Kampala via Mityana Road',
            'Pass through Mityana (77 km)',
            'Continue to Fort Portal (320 km)',
            'From Fort Portal, head south to Kibale (35 km)',
            'Enter through Kanyanchu Visitor Center',
            'Scenic drive through tea plantations'
        ],
        tips: [
            'Chimpanzee permits: $200 per person',
            'Morning tracking starts at 8:00 AM',
            'Afternoon tracking at 2:00 PM',
            'Visit Bigodi Wetland Sanctuary nearby',
            'Combine with Queen Elizabeth NP (2 hours away)',
            'Fort Portal is a charming town - explore crater lakes'
        ],
        coordinates: '0.5667° N, 30.4000° E'
    },
    'jinja': {
        title: 'Jinja - Source of the Nile',
        distance: '80 km from Kampala',
        duration: '1.5-2 hours drive',
        route: [
            'Start from Kampala via Jinja Road',
            'Pass through Mukono (21 km)',
            'Continue through Lugazi, Mabira Forest',
            'Arrive in Jinja town',
            'Visit Source of the Nile monument',
            'Easy day trip or weekend getaway'
        ],
        tips: [
            'White water rafting on the Nile',
            'Bungee jumping at Adrift',
            'Boat cruise to the Source',
            'Visit Mabira Forest for zip-lining',
            'Try local Nile perch at riverside restaurants',
            'Good for adventure seekers'
        ],
        coordinates: '0.4244° N, 33.2041° E'
    },
    'rwenzori': {
        title: 'Rwenzori Mountains National Park',
        distance: '375 km from Kampala',
        duration: '6-7 hours drive',
        route: [
            'Start from Kampala via Fort Portal',
            'Pass through Mityana, Mubende',
            'Continue to Fort Portal (320 km)',
            'Head to Kasese (75 km from Fort Portal)',
            'Park headquarters at Nyakalengija',
            'Mountaineering base at Kilembe'
        ],
        tips: [
            'Multi-day treks available (3-12 days)',
            'Margherita Peak: 5,109m - third highest in Africa',
            'Hire experienced guides and porters',
            'Proper hiking gear essential',
            'Best season: June-August, December-February',
            'Fitness level: challenging'
        ],
        coordinates: '0.3833° N, 29.9167° E'
    },
    'mgahinga': {
        title: 'Mgahinga Gorilla National Park',
        distance: '540 km from Kampala',
        duration: '8-9 hours drive',
        route: [
            'Same route as Bwindi initially',
            'Pass through Masaka, Mbarara, Kabale',
            'From Kabale, head to Kisoro (80 km)',
            'From Kisoro, drive to Mgahinga (14 km)',
            'Mountainous terrain - drive carefully'
        ],
        tips: [
            'Gorilla permits: $700 per person',
            'Golden monkey tracking: $100 per person',
            'Volcano hiking available',
            'Smaller park than Bwindi',
            'Can combine with Bwindi visit',
            'Border with Rwanda and DRC - carry ID'
        ],
        coordinates: '1.3833° S, 29.6500° E'
    },
    'sipi': {
        title: 'Sipi Falls',
        distance: '277 km from Kampala',
        duration: '5-6 hours drive',
        route: [
            'Start from Kampala via Jinja Road',
            'Pass through Jinja (80 km)',
            'Continue to Mbale (235 km)',
            'From Mbale, head to Sipi (42 km)',
            'Winding mountain roads - scenic drive'
        ],
        tips: [
            'Three beautiful waterfalls',
            'Coffee tours available',
            'Rock climbing and abseiling',
            'Hiking trails with stunning views',
            'Mount Elgon nearby for serious hikers',
            'Cool climate - bring warm clothes'
        ],
        coordinates: '1.3500° N, 34.3667° E'
    }
};

// Show directions modal
function showDirections(destination) {
    const data = directionsData[destination];
    if (!data) return;
    
    const modal = document.getElementById('directionsModal');
    const title = document.getElementById('directionTitle');
    const details = document.getElementById('directionDetails');
    
    title.innerHTML = `<i class="fas fa-map-marked-alt"></i> ${data.title}`;
    
    let html = `
        <div class="direction-summary">
            <div class="summary-item">
                <i class="fas fa-road"></i>
                <div>
                    <strong>Distance</strong>
                    <span>${data.distance}</span>
                </div>
            </div>
            <div class="summary-item">
                <i class="fas fa-clock"></i>
                <div>
                    <strong>Duration</strong>
                    <span>${data.duration}</span>
                </div>
            </div>
            <div class="summary-item">
                <i class="fas fa-map-pin"></i>
                <div>
                    <strong>Coordinates</strong>
                    <span>${data.coordinates}</span>
                </div>
            </div>
        </div>
        
        <div class="direction-route">
            <h3><i class="fas fa-route"></i> Route</h3>
            <ol class="route-steps">
                ${data.route.map(step => `<li>${step}</li>`).join('')}
            </ol>
        </div>
        
        <div class="direction-tips">
            <h3><i class="fas fa-lightbulb"></i> Travel Tips</h3>
            <ul class="tips-list">
                ${data.tips.map(tip => `<li><i class="fas fa-check-circle"></i> ${tip}</li>`).join('')}
            </ul>
        </div>
        
        <div class="direction-actions">
            <a href="https://www.google.com/maps/dir/?api=1&destination=${data.coordinates}" 
               target="_blank" 
               rel="noopener" 
               class="google-maps-btn">
                <i class="fas fa-map"></i> Open in Google Maps
            </a>
            <a href="{{ url('/bookings') }}" class="book-vehicle-btn">
                <i class="fas fa-car"></i> Book Vehicle Now
            </a>
        </div>
    `;
    
    details.innerHTML = html;
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

// Close directions modal
function closeDirections(event) {
    if (event && event.target.id !== 'directionsModal') return;
    const modal = document.getElementById('directionsModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
}

// Make functions globally available
window.showDirections = showDirections;
window.closeDirections = closeDirections;

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDirections();
    }
});

// Booking Form Handler - SIMPLIFIED (NO KYC)
(function() {
    'use strict';
    
console.log('=== BOOKING FORM LOADED ===');

const bookingForm = document.getElementById('bookingForm');
const paymentMethodSelect = document.getElementById('paymentMethod');
const mobileMoneyDetails = document.getElementById('mobileMoneyDetails');
const submitBtn = document.getElementById('submitBooking');
const toast = document.getElementById('toast');
const toastTitle = document.getElementById('toastTitle');
const toastMessage = document.getElementById('toastMessage');

console.log('Elements found:', {
    form: !!bookingForm,
    paymentSelect: !!paymentMethodSelect,
    submitBtn: !!submitBtn
});

// Show/hide mobile money details
if (paymentMethodSelect) {
    paymentMethodSelect.addEventListener('change', (e) => {
        if (mobileMoneyDetails) {
            mobileMoneyDetails.style.display = 
                (e.target.value === 'mtn_mobile_money' || e.target.value === 'airtel_money') 
                    ? 'block' 
                    : 'none';
        }
    });
}

// Calculate pricing
function calculatePricing() {
    const startDate = document.getElementById('startDate');
    const endDate = document.getElementById('endDate');
    const pricingSummary = document.getElementById('pricingSummary');
    const pricingDetails = document.getElementById('pricingDetails');
    
    if (!startDate || !endDate || !selectedCar) return;
    
    if (startDate.value && endDate.value) {
        const start = new Date(startDate.value);
        const end = new Date(endDate.value);
        const days = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
        
        if (days > 0) {
            const totalPrice = days * selectedCar.dailyRate;
            
            if (pricingDetails) {
                pricingDetails.innerHTML = `
                    <div class="pricing-row">
                        <span>Daily Rate:</span>
                        <span>UGX ${selectedCar.dailyRate.toLocaleString()}</span>
                    </div>
                    <div class="pricing-row">
                        <span>Number of Days:</span>
                        <span>${days}</span>
                    </div>
                    <div class="pricing-row total">
                        <span>Total Price:</span>
                        <span>UGX ${totalPrice.toLocaleString()}</span>
                    </div>
                `;
            }
            
            if (pricingSummary) {
                pricingSummary.style.display = 'block';
            }
        }
    }
}

// Event listeners for date changes
if (document.getElementById('startDate')) {
    document.getElementById('startDate').addEventListener('change', calculatePricing);
}
if (document.getElementById('endDate')) {
    document.getElementById('endDate').addEventListener('change', calculatePricing);
}

// Show toast notification
function showToast(title, message, type = 'info') {
    if (!toast) return;
    
    if (toastTitle) toastTitle.textContent = title;
    if (toastMessage) toastMessage.textContent = message;
    
    toast.className = `toast show toast-${type}`;
    
    setTimeout(() => {
        toast.classList.remove('show');
    }, 4000);
}

// Form submission
if (bookingForm) {
    bookingForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        console.log('=== BOOKING FORM SUBMISSION ===');
        
        if (!selectedCar) {
            showToast('Error', 'Please select a vehicle', 'error');
            console.error('No vehicle selected');
            return;
        }
        
        // Validate dates
        const startDate = new Date(document.getElementById('startDate').value);
        const endDate = new Date(document.getElementById('endDate').value);
        
        if (startDate >= endDate) {
            showToast('Error', 'End date must be after start date', 'error');
            console.error('Invalid dates');
            return;
        }
        
        // Create FormData (NO ID DOCUMENT REQUIRED)
        const formData = new FormData();
        formData.append('carId', selectedCar.id);
        formData.append('customerName', document.getElementById('customerName').value);
        formData.append('customerEmail', document.getElementById('customerEmail').value);
        formData.append('customerPhone', document.getElementById('customerPhone').value);
        formData.append('startDate', document.getElementById('startDate').value);
        formData.append('endDate', document.getElementById('endDate').value);
        formData.append('paymentMethod', document.getElementById('paymentMethod').value);
        
        // Add mobile money number if applicable
        const mobileMoneyNumber = document.getElementById('mobileMoneyNumber');
        if (mobileMoneyNumber && mobileMoneyNumber.value) {
            formData.append('mobileMoneyNumber', mobileMoneyNumber.value);
        }
        
        console.log('FormData prepared');
        
        // Log FormData contents
        console.log('FormData contents:');
        for (let [key, value] of formData.entries()) {
            console.log(`  ${key}: ${value}`);
        }
        
        // Disable submit button
        if (submitBtn) {
            submitBtn.disabled = true;
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoading = submitBtn.querySelector('.btn-loading');
            if (btnText) btnText.style.display = 'none';
            if (btnLoading) btnLoading.style.display = 'inline';
        }
        
        try {
            console.log('Sending request to /api/bookings');
            
            // Get CSRF token from meta tag
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            console.log('CSRF token:', csrfToken ? 'Found' : 'Not found');
            
            const headers = {
                'Accept': 'application/json'
            };
            
            // Add CSRF token if available
            if (csrfToken) {
                headers['X-CSRF-TOKEN'] = csrfToken;
            }
            
            const response = await fetch('/api/bookings', {
                method: 'POST',
                body: formData,
                headers: headers
            });
            
            console.log('Response status:', response.status);
            
            const data = await response.json();
            console.log('Response data:', data);
            
            if (response.ok) {
                showToast('Success', 'Booking submitted! Admin will confirm shortly.', 'success');
                bookingForm.reset();
                selectedCar = null;
                if (document.getElementById('selectedCarId')) {
                    document.getElementById('selectedCarId').value = '';
                }
                if (document.getElementById('carSelect')) {
                    document.getElementById('carSelect').value = '';
                }
                
                // Redirect after 2 seconds
                setTimeout(() => {
                    window.location.href = '/dashboard';
                }, 2000);
            } else {
                console.error('Error response:', data);
                const errorMessage = data.message || 'Failed to submit booking';
                console.error('Error details:', {
                    status: response.status,
                    message: errorMessage,
                    errors: data.errors
                });
                showToast('Error', errorMessage, 'error');
            }
        } catch (error) {
            console.error('Fetch error:', error);
            console.error('Error stack:', error.stack);
            showToast('Error', 'Failed to submit booking. Check console for details.', 'error');
        } finally {
            // Re-enable submit button
            if (submitBtn) {
                submitBtn.disabled = false;
                const btnText = submitBtn.querySelector('.btn-text');
                const btnLoading = submitBtn.querySelector('.btn-loading');
                if (btnText) btnText.style.display = 'inline';
                if (btnLoading) btnLoading.style.display = 'none';
            }
        }
    });
}

console.log('=== BOOKING FORM READY ===');

})(); // End of IIFE

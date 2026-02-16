<template>
  <div class="mobile-money-payment">
    <div class="payment-header">
      <h3>Mobile Money Payment</h3>
      <p class="payment-subtitle">Pay securely with MTN or Airtel Money</p>
    </div>

    <div v-if="!paymentInitiated" class="payment-form">
      <!-- Provider Selection -->
      <div class="provider-selection">
        <button 
          @click="selectedProvider = 'mtn'"
          :class="['provider-btn', { active: selectedProvider === 'mtn' }]"
        >
          <div class="provider-logo mtn-logo">
            <span class="provider-initial">M</span>
          </div>
          <span>MTN Mobile Money</span>
        </button>
        
        <button 
          @click="selectedProvider = 'airtel'"
          :class="['provider-btn', { active: selectedProvider === 'airtel' }]"
        >
          <div class="provider-logo airtel-logo">
            <span class="provider-initial">A</span>
          </div>
          <span>Airtel Money</span>
        </button>
      </div>

      <!-- Phone Number Input -->
      <div class="form-group">
        <label for="phone">Mobile Money Number</label>
        <div class="phone-input-group">
          <span class="country-code">+256</span>
          <input 
            v-model="phoneNumber"
            type="tel"
            id="phone"
            placeholder="7XX XXX XXX"
            maxlength="9"
            @input="formatPhoneNumber"
            class="form-control"
          >
        </div>
        <small class="form-hint">Enter your {{ selectedProvider === 'mtn' ? 'MTN' : 'Airtel' }} number</small>
      </div>

      <!-- Amount Display -->
      <div class="amount-display">
        <div class="amount-label">Amount to Pay</div>
        <div class="amount-value">UGX {{ formatCurrency(amount) }}</div>
      </div>

      <!-- Payment Button -->
      <button 
        @click="initiatePayment"
        :disabled="!isValid || processing"
        class="btn btn-primary btn-lg"
      >
        <span v-if="processing">
          <i class="fas fa-spinner fa-spin"></i> Processing...
        </span>
        <span v-else>
          <i class="fas fa-mobile-alt"></i> Pay with {{ selectedProvider === 'mtn' ? 'MTN' : 'Airtel' }}
        </span>
      </button>

      <div class="payment-security">
        <i class="fas fa-lock"></i>
        <span>Secure payment powered by Mobile Money</span>
      </div>
    </div>

    <!-- Payment Instructions -->
    <div v-else class="payment-instructions">
      <div class="instruction-icon">
        <i class="fas fa-mobile-alt"></i>
      </div>
      
      <h4>Complete Payment on Your Phone</h4>
      
      <div class="steps">
        <div class="step">
          <span class="step-number">1</span>
          <p>Check your phone for a payment prompt</p>
        </div>
        <div class="step">
          <span class="step-number">2</span>
          <p>Enter your Mobile Money PIN</p>
        </div>
        <div class="step">
          <span class="step-number">3</span>
          <p>Confirm the payment of <strong>UGX {{ formatCurrency(amount) }}</strong></p>
        </div>
      </div>

      <!-- USSD Code Alternative -->
      <div class="ussd-alternative">
        <p class="ussd-title">Or dial this code:</p>
        <div class="ussd-code">
          <code>{{ ussdCode }}</code>
          <button @click="copyUssdCode" class="copy-btn" title="Copy code">
            <i class="fas fa-copy"></i>
          </button>
        </div>
      </div>

      <!-- Status -->
      <div class="payment-status">
        <div class="status-spinner">
          <i class="fas fa-spinner fa-spin"></i>
        </div>
        <p>Waiting for payment confirmation...</p>
        <small>This may take up to 2 minutes</small>
      </div>

      <button @click="cancelPayment" class="btn btn-secondary">
        Cancel Payment
      </button>
    </div>

    <!-- Error Message -->
    <div v-if="error" class="alert alert-error">
      <i class="fas fa-exclamation-circle"></i>
      {{ error }}
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';

interface Props {
  amount: number;
  bookingId?: number;
  onSuccess?: () => void;
  onError?: (error: string) => void;
}

const props = defineProps<Props>();
const emit = defineEmits<{
  (e: 'success', transactionId: string): void;
  (e: 'error', error: string): void;
  (e: 'cancel'): void;
}>();

const selectedProvider = ref<'mtn' | 'airtel'>('mtn');
const phoneNumber = ref('');
const processing = ref(false);
const paymentInitiated = ref(false);
const error = ref<string | null>(null);
const transactionId = ref<string | null>(null);

const isValid = computed(() => {
  return phoneNumber.value.length === 9 && /^7\d{8}$/.test(phoneNumber.value);
});

const ussdCode = computed(() => {
  if (selectedProvider.value === 'mtn') {
    return '*165*3#';
  } else {
    return '*185*9#';
  }
});

const formatPhoneNumber = (event: Event) => {
  const input = event.target as HTMLInputElement;
  let value = input.value.replace(/\D/g, '');
  
  // Ensure it starts with 7
  if (value.length > 0 && value[0] !== '7') {
    value = '7' + value;
  }
  
  phoneNumber.value = value.slice(0, 9);
};

const formatCurrency = (value: number): string => {
  return new Intl.NumberFormat('en-UG').format(value);
};

const initiatePayment = async () => {
  error.value = null;
  processing.value = true;

  try {
    const response = await fetch('/api/payments/mobile-money/initiate', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${localStorage.getItem('auth_token')}`,
      },
      body: JSON.stringify({
        provider: selectedProvider.value,
        phone_number: `256${phoneNumber.value}`,
        amount: props.amount,
        booking_id: props.bookingId,
      }),
    });

    const data = await response.json();

    if (response.ok) {
      transactionId.value = data.transaction_id;
      paymentInitiated.value = true;
      
      // Start polling for payment status
      pollPaymentStatus();
    } else {
      error.value = data.error || 'Failed to initiate payment';
    }
  } catch (err) {
    error.value = 'Network error. Please check your connection.';
  } finally {
    processing.value = false;
  }
};

const pollPaymentStatus = async () => {
  if (!transactionId.value) return;

  const maxAttempts = 60; // 2 minutes (2 seconds interval)
  let attempts = 0;

  const poll = setInterval(async () => {
    attempts++;

    try {
      const response = await fetch(`/api/payments/mobile-money/status/${transactionId.value}`, {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('auth_token')}`,
        },
      });

      const data = await response.json();

      if (data.status === 'completed') {
        clearInterval(poll);
        emit('success', transactionId.value!);
      } else if (data.status === 'failed') {
        clearInterval(poll);
        error.value = data.message || 'Payment failed';
        paymentInitiated.value = false;
      } else if (attempts >= maxAttempts) {
        clearInterval(poll);
        error.value = 'Payment timeout. Please contact support.';
        paymentInitiated.value = false;
      }
    } catch (err) {
      // Continue polling on network errors
    }
  }, 2000);
};

const copyUssdCode = () => {
  navigator.clipboard.writeText(ussdCode.value);
  // Show toast notification
  alert('USSD code copied!');
};

const cancelPayment = () => {
  paymentInitiated.value = false;
  emit('cancel');
};
</script>

<style scoped>
.mobile-money-payment {
  max-width: 500px;
  margin: 0 auto;
  padding: 20px;
}

.payment-header {
  text-align: center;
  margin-bottom: 30px;
}

.payment-header h3 {
  font-size: 24px;
  margin-bottom: 8px;
  color: #333;
}

.payment-subtitle {
  color: #666;
  font-size: 14px;
}

.provider-selection {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
  margin-bottom: 24px;
}

.provider-btn {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
  padding: 16px;
  border: 2px solid #e0e0e0;
  border-radius: 12px;
  background: white;
  cursor: pointer;
  transition: all 0.2s;
}

.provider-btn:hover {
  border-color: #ccc;
  transform: translateY(-2px);
}

.provider-btn.active {
  border-color: #FFD700;
  background: #FFFBF0;
}

.provider-logo {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 24px;
  color: white;
}

.mtn-logo {
  background: linear-gradient(135deg, #FFCB05 0%, #FFA500 100%);
}

.airtel-logo {
  background: linear-gradient(135deg, #FF0000 0%, #CC0000 100%);
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  font-weight: 500;
  color: #333;
}

.phone-input-group {
  display: flex;
  gap: 8px;
}

.country-code {
  padding: 12px 16px;
  background: #f5f5f5;
  border: 1px solid #ddd;
  border-radius: 8px;
  font-weight: 500;
  color: #666;
}

.form-control {
  flex: 1;
  padding: 12px 16px;
  border: 1px solid #ddd;
  border-radius: 8px;
  font-size: 16px;
}

.form-control:focus {
  outline: none;
  border-color: #FFD700;
}

.form-hint {
  display: block;
  margin-top: 4px;
  font-size: 12px;
  color: #666;
}

.amount-display {
  background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
  padding: 20px;
  border-radius: 12px;
  text-align: center;
  margin-bottom: 24px;
  color: white;
}

.amount-label {
  font-size: 14px;
  margin-bottom: 4px;
  opacity: 0.9;
}

.amount-value {
  font-size: 32px;
  font-weight: bold;
}

.btn {
  width: 100%;
  padding: 14px;
  border: none;
  border-radius: 8px;
  font-size: 16px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-primary {
  background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
  color: #333;
}

.btn-primary:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(255, 215, 0, 0.4);
}

.btn-primary:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.btn-secondary {
  background: #f5f5f5;
  color: #666;
  margin-top: 12px;
}

.btn-lg {
  padding: 16px;
  font-size: 18px;
}

.payment-security {
  text-align: center;
  margin-top: 16px;
  font-size: 12px;
  color: #666;
}

.payment-security i {
  margin-right: 4px;
  color: #4CAF50;
}

.payment-instructions {
  text-align: center;
}

.instruction-icon {
  width: 80px;
  height: 80px;
  margin: 0 auto 20px;
  background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 36px;
  color: white;
}

.steps {
  text-align: left;
  margin: 24px 0;
}

.step {
  display: flex;
  gap: 16px;
  margin-bottom: 16px;
  align-items: flex-start;
}

.step-number {
  width: 32px;
  height: 32px;
  background: #FFD700;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  flex-shrink: 0;
}

.ussd-alternative {
  background: #f5f5f5;
  padding: 16px;
  border-radius: 8px;
  margin: 24px 0;
}

.ussd-title {
  font-size: 14px;
  color: #666;
  margin-bottom: 8px;
}

.ussd-code {
  display: flex;
  align-items: center;
  gap: 8px;
  justify-content: center;
}

.ussd-code code {
  font-size: 24px;
  font-weight: bold;
  color: #333;
  background: white;
  padding: 8px 16px;
  border-radius: 4px;
}

.copy-btn {
  padding: 8px 12px;
  background: white;
  border: 1px solid #ddd;
  border-radius: 4px;
  cursor: pointer;
}

.payment-status {
  margin: 24px 0;
}

.status-spinner {
  font-size: 32px;
  color: #FFD700;
  margin-bottom: 12px;
}

.alert {
  padding: 12px 16px;
  border-radius: 8px;
  margin-top: 16px;
}

.alert-error {
  background: #ffebee;
  color: #c62828;
  border: 1px solid #ef9a9a;
}

@media (max-width: 480px) {
  .mobile-money-payment {
    padding: 16px;
  }
  
  .provider-selection {
    grid-template-columns: 1fr;
  }
  
  .amount-value {
    font-size: 28px;
  }
}
</style>

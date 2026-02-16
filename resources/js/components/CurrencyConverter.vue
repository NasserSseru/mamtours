<template>
  <div class="currency-converter">
    <div class="converter-header">
      <h4>Currency Converter</h4>
      <button @click="toggleExpanded" class="toggle-btn">
        <i :class="['fas', expanded ? 'fa-chevron-up' : 'fa-chevron-down']"></i>
      </button>
    </div>

    <div v-if="expanded" class="converter-body">
      <!-- Amount Input -->
      <div class="amount-input-group">
        <input 
          v-model.number="amount"
          type="number"
          min="0"
          step="1000"
          class="amount-input"
          placeholder="Enter amount"
        >
        <select v-model="fromCurrency" class="currency-select">
          <option value="UGX">UGX</option>
          <option value="USD">USD</option>
          <option value="KES">KES</option>
          <option value="TZS">TZS</option>
          <option value="EUR">EUR</option>
          <option value="GBP">GBP</option>
        </select>
      </div>

      <!-- Conversion Arrow -->
      <div class="conversion-arrow">
        <button @click="swapCurrencies" class="swap-btn" title="Swap currencies">
          <i class="fas fa-exchange-alt"></i>
        </button>
      </div>

      <!-- Result Display -->
      <div class="result-display">
        <div class="result-amount">{{ formattedResult }}</div>
        <select v-model="toCurrency" class="currency-select">
          <option value="UGX">UGX</option>
          <option value="USD">USD</option>
          <option value="KES">KES</option>
          <option value="TZS">TZS</option>
          <option value="EUR">EUR</option>
          <option value="GBP">GBP</option>
        </select>
      </div>

      <!-- Exchange Rate Info -->
      <div class="exchange-rate-info">
        <small>
          1 {{ fromCurrency }} = {{ exchangeRate.toFixed(4) }} {{ toCurrency }}
        </small>
        <small class="update-time">Updated: {{ lastUpdated }}</small>
      </div>

      <!-- Quick Conversions -->
      <div class="quick-conversions">
        <h5>Quick Reference</h5>
        <div class="quick-grid">
          <div class="quick-item">
            <span class="quick-label">1 USD</span>
            <span class="quick-value">≈ {{ formatNumber(rates.USD_TO_UGX) }} UGX</span>
          </div>
          <div class="quick-item">
            <span class="quick-label">1 EUR</span>
            <span class="quick-value">≈ {{ formatNumber(rates.EUR_TO_UGX) }} UGX</span>
          </div>
          <div class="quick-item">
            <span class="quick-label">1 GBP</span>
            <span class="quick-value">≈ {{ formatNumber(rates.GBP_TO_UGX) }} UGX</span>
          </div>
          <div class="quick-item">
            <span class="quick-label">1 KES</span>
            <span class="quick-value">≈ {{ formatNumber(rates.KES_TO_UGX) }} UGX</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';

const expanded = ref(false);
const amount = ref(100000);
const fromCurrency = ref('UGX');
const toCurrency = ref('USD');
const lastUpdated = ref('Just now');

// Exchange rates (relative to UGX)
const rates = ref({
  UGX: 1,
  USD_TO_UGX: 3700,
  EUR_TO_UGX: 4100,
  GBP_TO_UGX: 4800,
  KES_TO_UGX: 29,
  TZS_TO_UGX: 1.6,
});

const exchangeRate = computed(() => {
  const fromRate = getRate(fromCurrency.value);
  const toRate = getRate(toCurrency.value);
  return toRate / fromRate;
});

const convertedAmount = computed(() => {
  return amount.value * exchangeRate.value;
});

const formattedResult = computed(() => {
  return formatNumber(convertedAmount.value);
});

const getRate = (currency: string): number => {
  if (currency === 'UGX') return 1;
  if (currency === 'USD') return 1 / rates.value.USD_TO_UGX;
  if (currency === 'EUR') return 1 / rates.value.EUR_TO_UGX;
  if (currency === 'GBP') return 1 / rates.value.GBP_TO_UGX;
  if (currency === 'KES') return 1 / rates.value.KES_TO_UGX;
  if (currency === 'TZS') return 1 / rates.value.TZS_TO_UGX;
  return 1;
};

const formatNumber = (value: number): string => {
  return new Intl.NumberFormat('en-UG', {
    minimumFractionDigits: 0,
    maximumFractionDigits: 2,
  }).format(value);
};

const toggleExpanded = () => {
  expanded.value = !expanded.value;
};

const swapCurrencies = () => {
  const temp = fromCurrency.value;
  fromCurrency.value = toCurrency.value;
  toCurrency.value = temp;
};

onMounted(async () => {
  // Fetch live exchange rates
  try {
    const response = await fetch('/api/exchange-rates');
    if (response.ok) {
      const data = await response.json();
      rates.value = { ...rates.value, ...data.rates };
      lastUpdated.value = new Date(data.updated_at).toLocaleTimeString();
    }
  } catch (error) {
    console.log('Using default exchange rates');
  }
});
</script>

<style scoped>
.currency-converter {
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  overflow: hidden;
}

.converter-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 16px;
  background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
  color: #333;
  cursor: pointer;
}

.converter-header h4 {
  font-size: 16px;
  font-weight: 600;
  margin: 0;
}

.toggle-btn {
  background: none;
  border: none;
  color: #333;
  font-size: 16px;
  cursor: pointer;
  padding: 4px;
}

.converter-body {
  padding: 20px;
}

.amount-input-group {
  display: flex;
  gap: 8px;
  margin-bottom: 16px;
}

.amount-input {
  flex: 1;
  padding: 12px;
  border: 2px solid #e0e0e0;
  border-radius: 8px;
  font-size: 16px;
  font-weight: 500;
}

.amount-input:focus {
  outline: none;
  border-color: #FFD700;
}

.currency-select {
  padding: 12px;
  border: 2px solid #e0e0e0;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 500;
  background: white;
  cursor: pointer;
}

.currency-select:focus {
  outline: none;
  border-color: #FFD700;
}

.conversion-arrow {
  text-align: center;
  margin: 12px 0;
}

.swap-btn {
  background: #f5f5f5;
  border: 2px solid #e0e0e0;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s;
}

.swap-btn:hover {
  background: #FFD700;
  border-color: #FFD700;
  transform: rotate(180deg);
}

.result-display {
  display: flex;
  gap: 8px;
  align-items: center;
  margin-bottom: 12px;
}

.result-amount {
  flex: 1;
  padding: 16px;
  background: linear-gradient(135deg, #f5f5f5 0%, #e0e0e0 100%);
  border-radius: 8px;
  font-size: 20px;
  font-weight: bold;
  color: #333;
}

.exchange-rate-info {
  display: flex;
  justify-content: space-between;
  padding: 12px;
  background: #f5f5f5;
  border-radius: 8px;
  margin-bottom: 16px;
}

.exchange-rate-info small {
  font-size: 12px;
  color: #666;
}

.update-time {
  font-style: italic;
}

.quick-conversions {
  border-top: 1px solid #e0e0e0;
  padding-top: 16px;
}

.quick-conversions h5 {
  font-size: 14px;
  color: #666;
  margin-bottom: 12px;
}

.quick-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 8px;
}

.quick-item {
  display: flex;
  justify-content: space-between;
  padding: 8px 12px;
  background: #f5f5f5;
  border-radius: 6px;
  font-size: 12px;
}

.quick-label {
  font-weight: 500;
  color: #666;
}

.quick-value {
  font-weight: 600;
  color: #333;
}

@media (max-width: 480px) {
  .quick-grid {
    grid-template-columns: 1fr;
  }
}
</style>

<template>
  <div class="booking-form">
    <form @submit.prevent="submitBooking" class="space-y-6">
      <!-- Car Selection -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Select Vehicle
        </label>
        <select v-model="form.car_id" class="form-select" required>
          <option value="">Choose a vehicle...</option>
          <option v-for="car in cars" :key="car.id" :value="car.id">
            {{ car.brand }} {{ car.model }} - UGX {{ formatCurrency(car.dailyRate) }}/day
          </option>
        </select>
      </div>

      <!-- Date Selection -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Start Date
          </label>
          <input 
            type="date" 
            v-model="form.start_date" 
            :min="minDate"
            class="form-input" 
            required
            @change="calculatePricing"
          >
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">
            End Date
          </label>
          <input 
            type="date" 
            v-model="form.end_date" 
            :min="form.start_date || minDate"
            class="form-input" 
            required
            @change="calculatePricing"
          >
        </div>
      </div>

      <!-- Pricing Summary -->
      <div v-if="pricing.total > 0" class="bg-gray-50 rounded-lg p-4">
        <h3 class="text-lg font-semibold text-gray-800 mb-3">Pricing Summary</h3>
        <div class="space-y-2">
          <div class="flex justify-between">
            <span>Base Price ({{ pricing.days }} days)</span>
            <span>UGX {{ formatCurrency(pricing.subtotal) }}</span>
          </div>
          <div v-if="pricing.tax > 0" class="flex justify-between">
            <span>Tax</span>
            <span>UGX {{ formatCurrency(pricing.tax) }}</span>
          </div>
          <hr class="my-2">
          <div class="flex justify-between text-lg font-bold">
            <span>Total</span>
            <span class="text-blue-600">UGX {{ formatCurrency(pricing.total) }}</span>
          </div>
        </div>
      </div>

      <!-- Submit Button -->
      <button 
        type="submit" 
        :disabled="!canSubmit || loading"
        class="w-full btn btn-primary"
        :class="{ 'opacity-50 cursor-not-allowed': !canSubmit || loading }"
      >
        <span v-if="loading">Processing...</span>
        <span v-else>Book Now - UGX {{ formatCurrency(pricing.total) }}</span>
      </button>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue';
import type { Car, BookingPricing } from '../types';

interface Props {
  cars?: Car[];
}

const props = withDefaults(defineProps<Props>(), {
  cars: () => []
});

const emit = defineEmits<{
  (e: 'success', booking: any): void;
  (e: 'error', error: string): void;
}>();

const loading = ref(false);
const form = ref({
  car_id: '',
  start_date: '',
  end_date: ''
});

const pricing = ref<BookingPricing>({
  days: 0,
  daily_rate: 0,
  subtotal: 0,
  tax: 0,
  total: 0
});

const minDate = computed(() => {
  return new Date().toISOString().split('T')[0];
});

const selectedCar = computed(() => {
  return props.cars.find(car => car.id.toString() === form.value.car_id);
});

const canSubmit = computed(() => {
  return form.value.car_id && form.value.start_date && form.value.end_date && pricing.value.total > 0;
});

const formatCurrency = (amount: number): string => {
  return new Intl.NumberFormat('en-UG').format(amount);
};

const calculatePricing = (): void => {
  if (!selectedCar.value || !form.value.start_date || !form.value.end_date) {
    pricing.value = { days: 0, daily_rate: 0, subtotal: 0, tax: 0, total: 0 };
    return;
  }

  const startDate = new Date(form.value.start_date);
  const endDate = new Date(form.value.end_date);
  const days = Math.ceil((endDate.getTime() - startDate.getTime()) / (1000 * 60 * 60 * 24));

  if (days <= 0) {
    pricing.value = { days: 0, daily_rate: 0, subtotal: 0, tax: 0, total: 0 };
    return;
  }

  const dailyRate = selectedCar.value.dailyRate;
  const subtotal = dailyRate * days;
  const tax = subtotal * 0.18; // 18% VAT
  const total = subtotal + tax;

  pricing.value = {
    days,
    daily_rate: dailyRate,
    subtotal,
    tax,
    total
  };
};

const submitBooking = async (): Promise<void> => {
  if (!canSubmit.value) return;

  loading.value = true;
  
  try {
    const response = await fetch('/api/bookings', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      },
      body: JSON.stringify({
        carId: form.value.car_id,
        startDate: form.value.start_date,
        endDate: form.value.end_date,
        pricing: pricing.value
      })
    });

    const data = await response.json();

    if (response.ok) {
      emit('success', data.booking);
      if (data.redirect_url) {
        window.location.href = data.redirect_url;
      }
    } else {
      emit('error', data.error || 'Failed to create booking');
    }
  } catch (error) {
    emit('error', 'Failed to create booking. Please try again.');
  } finally {
    loading.value = false;
  }
};

watch(() => form.value.car_id, calculatePricing);
watch(() => form.value.start_date, calculatePricing);
watch(() => form.value.end_date, calculatePricing);
</script>

import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import type { Booking, BookingFormData } from '../types';
import { useAuthStore } from './auth';

export const useBookingsStore = defineStore('bookings', () => {
    // State
    const bookings = ref<Booking[]>([]);
    const selectedBooking = ref<Booking | null>(null);
    const loading = ref(false);
    const error = ref<string | null>(null);

    // Getters
    const activeBookings = computed(() => 
        bookings.value.filter(b => b.status === 'active' || b.status === 'confirmed')
    );
    
    const pastBookings = computed(() => 
        bookings.value.filter(b => b.status === 'completed' || b.status === 'cancelled')
    );
    
    const pendingBookings = computed(() => 
        bookings.value.filter(b => b.status === 'pending')
    );

    const totalSpent = computed(() => 
        bookings.value
            .filter(b => b.status === 'completed')
            .reduce((sum, b) => sum + (b.pricing?.total || 0), 0)
    );

    // Actions
    async function fetchBookings(): Promise<void> {
        const authStore = useAuthStore();
        if (!authStore.token) return;

        loading.value = true;
        error.value = null;

        try {
            const response = await fetch('/api/bookings', {
                headers: {
                    'Authorization': `Bearer ${authStore.token}`,
                },
            });

            if (response.ok) {
                const data = await response.json();
                bookings.value = data;
            } else {
                error.value = 'Failed to fetch bookings';
            }
        } catch (err) {
            error.value = 'Network error. Please try again.';
        } finally {
            loading.value = false;
        }
    }

    async function fetchBookingById(id: number): Promise<Booking | null> {
        const authStore = useAuthStore();
        if (!authStore.token) return null;

        loading.value = true;
        error.value = null;

        try {
            const response = await fetch(`/api/bookings/${id}`, {
                headers: {
                    'Authorization': `Bearer ${authStore.token}`,
                },
            });

            if (response.ok) {
                const data = await response.json();
                selectedBooking.value = data;
                return data;
            } else {
                error.value = 'Booking not found';
                return null;
            }
        } catch (err) {
            error.value = 'Network error. Please try again.';
            return null;
        } finally {
            loading.value = false;
        }
    }

    async function createBooking(formData: BookingFormData): Promise<Booking | null> {
        const authStore = useAuthStore();
        
        loading.value = true;
        error.value = null;

        try {
            const headers: Record<string, string> = {
                'Content-Type': 'application/json',
            };

            if (authStore.token) {
                headers['Authorization'] = `Bearer ${authStore.token}`;
            }

            const response = await fetch('/api/bookings', {
                method: 'POST',
                headers,
                body: JSON.stringify(formData),
            });

            const data = await response.json();

            if (response.ok) {
                const booking = data.booking;
                bookings.value.push(booking);
                selectedBooking.value = booking;
                return booking;
            } else {
                error.value = data.error || 'Failed to create booking';
                return null;
            }
        } catch (err) {
            error.value = 'Network error. Please try again.';
            return null;
        } finally {
            loading.value = false;
        }
    }

    async function confirmBooking(id: number): Promise<boolean> {
        const authStore = useAuthStore();
        if (!authStore.token) return false;

        loading.value = true;
        error.value = null;

        try {
            const response = await fetch(`/api/bookings/${id}/confirm`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${authStore.token}`,
                },
            });

            if (response.ok) {
                const data = await response.json();
                const index = bookings.value.findIndex(b => b.id === id);
                if (index !== -1) {
                    bookings.value[index] = data.booking;
                }
                if (selectedBooking.value?.id === id) {
                    selectedBooking.value = data.booking;
                }
                return true;
            } else {
                error.value = 'Failed to confirm booking';
                return false;
            }
        } catch (err) {
            error.value = 'Network error. Please try again.';
            return false;
        } finally {
            loading.value = false;
        }
    }

    async function cancelBooking(id: number): Promise<boolean> {
        const authStore = useAuthStore();
        if (!authStore.token) return false;

        loading.value = true;
        error.value = null;

        try {
            const response = await fetch(`/api/bookings/${id}/cancel`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${authStore.token}`,
                },
            });

            if (response.ok) {
                const data = await response.json();
                const index = bookings.value.findIndex(b => b.id === id);
                if (index !== -1) {
                    bookings.value[index] = data.booking;
                }
                if (selectedBooking.value?.id === id) {
                    selectedBooking.value = data.booking;
                }
                return true;
            } else {
                error.value = 'Failed to cancel booking';
                return false;
            }
        } catch (err) {
            error.value = 'Network error. Please try again.';
            return false;
        } finally {
            loading.value = false;
        }
    }

    function selectBooking(booking: Booking): void {
        selectedBooking.value = booking;
    }

    function clearSelection(): void {
        selectedBooking.value = null;
    }

    function clearError(): void {
        error.value = null;
    }

    return {
        // State
        bookings,
        selectedBooking,
        loading,
        error,
        // Getters
        activeBookings,
        pastBookings,
        pendingBookings,
        totalSpent,
        // Actions
        fetchBookings,
        fetchBookingById,
        createBooking,
        confirmBooking,
        cancelBooking,
        selectBooking,
        clearSelection,
        clearError,
    };
});

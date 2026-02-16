import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import type { Car, CarSearchParams, ApiResponse } from '../types';

export const useCarsStore = defineStore('cars', () => {
    // State
    const cars = ref<Car[]>([]);
    const selectedCar = ref<Car | null>(null);
    const loading = ref(false);
    const error = ref<string | null>(null);
    const currentPage = ref(1);
    const totalPages = ref(1);
    const total = ref(0);

    // Getters
    const availableCars = computed(() => cars.value.filter(car => car.isAvailable));
    const carsByCategory = computed(() => {
        const grouped: Record<string, Car[]> = {};
        cars.value.forEach(car => {
            const category = car.category || 'Uncategorized';
            if (!grouped[category]) {
                grouped[category] = [];
            }
            grouped[category].push(car);
        });
        return grouped;
    });

    // Actions
    async function fetchCars(page: number = 1, perPage: number = 15): Promise<void> {
        loading.value = true;
        error.value = null;

        try {
            const response = await fetch(`/api/v2/cars?page=${page}&per_page=${perPage}`);
            const data: ApiResponse<Car[]> = await response.json();

            if (response.ok) {
                cars.value = data.data;
                if (data.meta) {
                    currentPage.value = data.meta.current_page;
                    totalPages.value = data.meta.last_page;
                    total.value = data.meta.total;
                }
            } else {
                error.value = 'Failed to fetch cars';
            }
        } catch (err) {
            error.value = 'Network error. Please try again.';
        } finally {
            loading.value = false;
        }
    }

    async function fetchCarById(id: number): Promise<Car | null> {
        loading.value = true;
        error.value = null;

        try {
            const response = await fetch(`/api/v2/cars/${id}`);
            const data: ApiResponse<Car> = await response.json();

            if (response.ok) {
                selectedCar.value = data.data;
                return data.data;
            } else {
                error.value = 'Car not found';
                return null;
            }
        } catch (err) {
            error.value = 'Network error. Please try again.';
            return null;
        } finally {
            loading.value = false;
        }
    }

    async function searchCars(params: CarSearchParams): Promise<void> {
        loading.value = true;
        error.value = null;

        try {
            const queryParams = new URLSearchParams();
            Object.entries(params).forEach(([key, value]) => {
                if (value !== undefined && value !== null) {
                    queryParams.append(key, value.toString());
                }
            });

            const response = await fetch(`/api/v2/cars/search?${queryParams}`);
            const data: ApiResponse<Car[]> = await response.json();

            if (response.ok) {
                cars.value = data.data;
                if (data.meta) {
                    currentPage.value = data.meta.current_page;
                    totalPages.value = data.meta.last_page;
                    total.value = data.meta.total;
                }
            } else {
                error.value = 'Search failed';
            }
        } catch (err) {
            error.value = 'Network error. Please try again.';
        } finally {
            loading.value = false;
        }
    }

    function selectCar(car: Car): void {
        selectedCar.value = car;
    }

    function clearSelection(): void {
        selectedCar.value = null;
    }

    function clearError(): void {
        error.value = null;
    }

    return {
        // State
        cars,
        selectedCar,
        loading,
        error,
        currentPage,
        totalPages,
        total,
        // Getters
        availableCars,
        carsByCategory,
        // Actions
        fetchCars,
        fetchCarById,
        searchCars,
        selectCar,
        clearSelection,
        clearError,
    };
});

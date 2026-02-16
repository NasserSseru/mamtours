import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import type { User } from '../types';

export const useAuthStore = defineStore('auth', () => {
    // State
    const user = ref<User | null>(null);
    const token = ref<string | null>(localStorage.getItem('auth_token'));
    const loading = ref(false);
    const error = ref<string | null>(null);

    // Getters
    const isAuthenticated = computed(() => !!token.value && !!user.value);
    const isAdmin = computed(() => user.value?.role === 'admin');
    const isKycVerified = computed(() => user.value?.kyc_verified ?? false);

    // Actions
    async function login(email: string, password: string): Promise<boolean> {
        loading.value = true;
        error.value = null;

        try {
            const response = await fetch('/api/auth/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ email, password }),
            });

            const data = await response.json();

            if (response.ok) {
                token.value = data.token;
                user.value = data.user;
                localStorage.setItem('auth_token', data.token);
                return true;
            } else {
                error.value = data.message || 'Login failed';
                return false;
            }
        } catch (err) {
            error.value = 'Network error. Please try again.';
            return false;
        } finally {
            loading.value = false;
        }
    }

    async function logout(): Promise<void> {
        loading.value = true;

        try {
            if (token.value) {
                await fetch('/api/auth/logout', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token.value}`,
                    },
                });
            }
        } catch (err) {
            console.error('Logout error:', err);
        } finally {
            user.value = null;
            token.value = null;
            localStorage.removeItem('auth_token');
            loading.value = false;
        }
    }

    async function fetchUser(): Promise<void> {
        if (!token.value) return;

        loading.value = true;
        error.value = null;

        try {
            const response = await fetch('/api/user', {
                headers: {
                    'Authorization': `Bearer ${token.value}`,
                },
            });

            if (response.ok) {
                const data = await response.json();
                user.value = data;
            } else {
                // Token might be invalid
                await logout();
            }
        } catch (err) {
            error.value = 'Failed to fetch user data';
        } finally {
            loading.value = false;
        }
    }

    function clearError(): void {
        error.value = null;
    }

    return {
        // State
        user,
        token,
        loading,
        error,
        // Getters
        isAuthenticated,
        isAdmin,
        isKycVerified,
        // Actions
        login,
        logout,
        fetchUser,
        clearError,
    };
}, {
    persist: {
        key: 'auth',
        storage: localStorage,
        paths: ['token', 'user'],
    },
});

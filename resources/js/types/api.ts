import type { Car, Booking, User, ApiResponse } from './index';

// API Client Types
export interface ApiClient {
    get<T>(url: string, params?: Record<string, any>): Promise<ApiResponse<T>>;
    post<T>(url: string, data?: Record<string, any>): Promise<ApiResponse<T>>;
    put<T>(url: string, data?: Record<string, any>): Promise<ApiResponse<T>>;
    delete<T>(url: string): Promise<ApiResponse<T>>;
}

// API Endpoints
export const API_ENDPOINTS = {
    // Cars
    CARS_LIST: '/api/v2/cars',
    CARS_DETAIL: (id: number) => `/api/v2/cars/${id}`,
    CARS_SEARCH: '/api/v2/cars/search',
    
    // Bookings
    BOOKINGS_LIST: '/api/bookings',
    BOOKINGS_CREATE: '/api/bookings',
    BOOKINGS_DETAIL: (id: number) => `/api/bookings/${id}`,
    BOOKINGS_CONFIRM: (id: number) => `/api/bookings/${id}/confirm`,
    BOOKINGS_CANCEL: (id: number) => `/api/bookings/${id}/cancel`,
    
    // Auth
    AUTH_LOGIN: '/api/auth/login',
    AUTH_LOGOUT: '/api/auth/logout',
    AUTH_USER: '/api/user',
    
    // Payments
    PAYMENT_INTENT: '/api/payments/intent',
    PAYMENT_CAPTURE: (id: string) => `/api/payments/capture/${id}`,
    
    // Health
    HEALTH: '/api/health',
} as const;

// HTTP Methods
export type HttpMethod = 'GET' | 'POST' | 'PUT' | 'DELETE' | 'PATCH';

// Request Config
export interface RequestConfig {
    method: HttpMethod;
    headers?: Record<string, string>;
    body?: any;
    params?: Record<string, any>;
}

// Error Response
export interface ApiError {
    error: string;
    message?: string;
    errors?: Record<string, string[]>;
    code?: string;
}

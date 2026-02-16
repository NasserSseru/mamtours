// API Response Types
export interface ApiResponse<T> {
    version?: string;
    data: T;
    meta?: PaginationMeta;
    links?: PaginationLinks;
}

export interface PaginationMeta {
    current_page: number;
    per_page: number;
    total: number;
    last_page: number;
}

export interface PaginationLinks {
    first: string | null;
    last: string | null;
    prev: string | null;
    next: string | null;
}

// Car Types
export interface Car {
    id: number;
    brand: string;
    model: string;
    numberPlate: string;
    dailyRate: number;
    seats: number;
    category: string | null;
    isAvailable: boolean;
    carPicture: string | null;
    created_at?: string;
    updated_at?: string;
}

export interface CarSearchParams {
    query?: string;
    min_price?: number;
    max_price?: number;
    min_seats?: number;
    max_seats?: number;
    category?: string;
    available_from?: string;
    available_to?: string;
}

// Booking Types
export interface Booking {
    id: number;
    car_id: number;
    user_id: number | null;
    customerName: string;
    startDate: string;
    endDate: string;
    status: BookingStatus;
    pricing: BookingPricing;
    payment_method: PaymentMethod;
    payment_status: PaymentStatus;
    phone_number: string | null;
    mobile_money_number: string | null;
    kyc_id: number | null;
    created_at: string;
    updated_at: string;
    car?: Car;
}

export type BookingStatus = 'pending' | 'confirmed' | 'active' | 'completed' | 'cancelled';
export type PaymentStatus = 'pending' | 'paid' | 'failed' | 'refunded';
export type PaymentMethod = 'stripe' | 'mtn_mobile_money' | 'airtel_money' | 'bank_transfer' | 'cash';

export interface BookingPricing {
    days: number;
    daily_rate: number;
    subtotal: number;
    tax: number;
    total: number;
}

export interface BookingFormData {
    carId: number;
    customerName: string;
    startDate: string;
    endDate: string;
    payment_method: PaymentMethod;
    mobile_money_number?: string;
    phone_number?: string;
    id_type: 'nin' | 'passport';
    id_number: string;
    permit_number: string;
}

// User Types
export interface User {
    id: number;
    name: string;
    email: string;
    phone: string | null;
    role: UserRole;
    kyc_verified: boolean;
    kyc_status: KycStatus;
    profile_picture: string | null;
    created_at: string;
    updated_at: string;
}

export type UserRole = 'customer' | 'admin';
export type KycStatus = 'not_submitted' | 'pending' | 'approved' | 'rejected';

// KYC Types
export interface KycVerification {
    id: number;
    user_id: number;
    id_type: 'nin' | 'passport';
    id_number: string;
    permit_number: string;
    id_document_path: string | null;
    permit_document_path: string | null;
    status: KycStatus;
    verified_at: string | null;
    created_at: string;
    updated_at: string;
}

// Payment Types
export interface PaymentIntent {
    id: string;
    amount: number;
    currency: string;
    status: string;
    client_secret?: string;
}

// Notification Types
export interface Notification {
    id: number;
    user_id: number;
    type: string;
    title: string;
    message: string;
    read: boolean;
    created_at: string;
}

// Analytics Types
export interface AnalyticsEvent {
    event_type: string;
    event_name: string;
    properties?: Record<string, any>;
}

// Form Validation Types
export interface ValidationErrors {
    [key: string]: string[];
}

export interface FormState<T> {
    data: T;
    errors: ValidationErrors;
    loading: boolean;
    success: boolean;
}

// Component Props Types
export interface BookingFormProps {
    car: Car;
    onSuccess?: (booking: Booking) => void;
    onError?: (error: string) => void;
}

export interface PaymentProcessorProps {
    booking: Booking;
    onSuccess?: () => void;
    onError?: (error: string) => void;
}

export interface NotificationCenterProps {
    userId: number;
}

export interface MapComponentProps {
    center?: { lat: number; lng: number };
    zoom?: number;
    markers?: MapMarker[];
    onMarkerClick?: (marker: MapMarker) => void;
}

export interface MapMarker {
    id: number;
    position: { lat: number; lng: number };
    title: string;
    icon?: string;
}

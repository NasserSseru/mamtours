import type { MapMarker } from '../types';

export class MapService {
    private map: google.maps.Map | null = null;
    private markers: google.maps.Marker[] = [];
    private apiKey: string;

    constructor(apiKey: string) {
        this.apiKey = apiKey;
    }

    /**
     * Initialize Google Maps
     */
    async initialize(elementId: string, options?: google.maps.MapOptions): Promise<void> {
        if (!window.google) {
            await this.loadGoogleMapsScript();
        }

        const element = document.getElementById(elementId);
        if (!element) {
            throw new Error(`Element with id "${elementId}" not found`);
        }

        const defaultOptions: google.maps.MapOptions = {
            center: { lat: 0.3476, lng: 32.5825 }, // Kampala, Uganda
            zoom: 12,
            ...options
        };

        this.map = new google.maps.Map(element, defaultOptions);
    }

    /**
     * Load Google Maps script dynamically
     */
    private loadGoogleMapsScript(): Promise<void> {
        return new Promise((resolve, reject) => {
            if (window.google) {
                resolve();
                return;
            }

            const script = document.createElement('script');
            script.src = `https://maps.googleapis.com/maps/api/js?key=${this.apiKey}&libraries=places`;
            script.async = true;
            script.defer = true;
            script.onload = () => resolve();
            script.onerror = () => reject(new Error('Failed to load Google Maps script'));
            document.head.appendChild(script);
        });
    }

    /**
     * Add a marker to the map
     */
    addMarker(marker: MapMarker, onClick?: (marker: MapMarker) => void): google.maps.Marker | null {
        if (!this.map) return null;

        const googleMarker = new google.maps.Marker({
            position: marker.position,
            map: this.map,
            title: marker.title,
            icon: marker.icon
        });

        if (onClick) {
            googleMarker.addListener('click', () => onClick(marker));
        }

        this.markers.push(googleMarker);
        return googleMarker;
    }

    /**
     * Add multiple markers
     */
    addMarkers(markers: MapMarker[], onClick?: (marker: MapMarker) => void): void {
        markers.forEach(marker => this.addMarker(marker, onClick));
    }

    /**
     * Clear all markers
     */
    clearMarkers(): void {
        this.markers.forEach(marker => marker.setMap(null));
        this.markers = [];
    }

    /**
     * Set map center
     */
    setCenter(lat: number, lng: number): void {
        if (!this.map) return;
        this.map.setCenter({ lat, lng });
    }

    /**
     * Set map zoom
     */
    setZoom(zoom: number): void {
        if (!this.map) return;
        this.map.setZoom(zoom);
    }

    /**
     * Fit bounds to show all markers
     */
    fitBounds(): void {
        if (!this.map || this.markers.length === 0) return;

        const bounds = new google.maps.LatLngBounds();
        this.markers.forEach(marker => {
            const position = marker.getPosition();
            if (position) {
                bounds.extend(position);
            }
        });

        this.map.fitBounds(bounds);
    }

    /**
     * Calculate route between two points
     */
    async calculateRoute(
        origin: { lat: number; lng: number },
        destination: { lat: number; lng: number }
    ): Promise<google.maps.DirectionsResult | null> {
        if (!window.google) return null;

        const directionsService = new google.maps.DirectionsService();

        try {
            const result = await directionsService.route({
                origin,
                destination,
                travelMode: google.maps.TravelMode.DRIVING
            });

            return result;
        } catch (error) {
            console.error('Error calculating route:', error);
            return null;
        }
    }

    /**
     * Display route on map
     */
    displayRoute(result: google.maps.DirectionsResult): google.maps.DirectionsRenderer | null {
        if (!this.map) return null;

        const directionsRenderer = new google.maps.DirectionsRenderer();
        directionsRenderer.setMap(this.map);
        directionsRenderer.setDirections(result);

        return directionsRenderer;
    }

    /**
     * Geocode address to coordinates
     */
    async geocodeAddress(address: string): Promise<{ lat: number; lng: number } | null> {
        if (!window.google) return null;

        const geocoder = new google.maps.Geocoder();

        try {
            const result = await geocoder.geocode({ address });
            if (result.results.length > 0) {
                const location = result.results[0].geometry.location;
                return {
                    lat: location.lat(),
                    lng: location.lng()
                };
            }
            return null;
        } catch (error) {
            console.error('Error geocoding address:', error);
            return null;
        }
    }

    /**
     * Reverse geocode coordinates to address
     */
    async reverseGeocode(lat: number, lng: number): Promise<string | null> {
        if (!window.google) return null;

        const geocoder = new google.maps.Geocoder();

        try {
            const result = await geocoder.geocode({ location: { lat, lng } });
            if (result.results.length > 0) {
                return result.results[0].formatted_address;
            }
            return null;
        } catch (error) {
            console.error('Error reverse geocoding:', error);
            return null;
        }
    }

    /**
     * Get user's current location
     */
    async getCurrentLocation(): Promise<{ lat: number; lng: number } | null> {
        return new Promise((resolve) => {
            if (!navigator.geolocation) {
                resolve(null);
                return;
            }

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    resolve({
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    });
                },
                () => {
                    resolve(null);
                }
            );
        });
    }
}

// Global type declaration
declare global {
    interface Window {
        google: typeof google;
    }
}

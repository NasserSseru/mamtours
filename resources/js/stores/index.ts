import { createPinia } from 'pinia';
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate';

const pinia = createPinia();
pinia.use(piniaPluginPersistedstate);

export default pinia;

// Export stores
export { useAuthStore } from './auth';
export { useCarsStore } from './cars';
export { useBookingsStore } from './bookings';

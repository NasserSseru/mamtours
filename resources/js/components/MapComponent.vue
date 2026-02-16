<template>
  <div class="map-component">
    <div :id="mapId" class="map-container" :style="{ height: height }"></div>
    
    <div v-if="showControls" class="map-controls">
      <button @click="centerOnUser" class="btn btn-sm btn-secondary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
        My Location
      </button>
      
      <button v-if="markers.length > 1" @click="fitAllMarkers" class="btn btn-sm btn-secondary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
        </svg>
        Fit All
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';
import { MapService } from '../services/MapService';
import type { MapMarker } from '../types';

interface Props {
  center?: { lat: number; lng: number };
  zoom?: number;
  markers?: MapMarker[];
  height?: string;
  showControls?: boolean;
  apiKey: string;
}

const props = withDefaults(defineProps<Props>(), {
  center: () => ({ lat: 0.3476, lng: 32.5825 }), // Kampala
  zoom: 12,
  markers: () => [],
  height: '400px',
  showControls: true
});

const emit = defineEmits<{
  (e: 'markerClick', marker: MapMarker): void;
  (e: 'mapReady'): void;
}>();

const mapId = ref(`map-${Math.random().toString(36).substr(2, 9)}`);
const mapService = ref<MapService | null>(null);

onMounted(async () => {
  try {
    mapService.value = new MapService(props.apiKey);
    await mapService.value.initialize(mapId.value, {
      center: props.center,
      zoom: props.zoom
    });

    if (props.markers.length > 0) {
      mapService.value.addMarkers(props.markers, (marker) => {
        emit('markerClick', marker);
      });
      
      if (props.markers.length > 1) {
        mapService.value.fitBounds();
      }
    }

    emit('mapReady');
  } catch (error) {
    console.error('Failed to initialize map:', error);
  }
});

watch(() => props.markers, (newMarkers) => {
  if (!mapService.value) return;
  
  mapService.value.clearMarkers();
  mapService.value.addMarkers(newMarkers, (marker) => {
    emit('markerClick', marker);
  });
  
  if (newMarkers.length > 1) {
    mapService.value.fitBounds();
  }
}, { deep: true });

const centerOnUser = async () => {
  if (!mapService.value) return;
  
  const location = await mapService.value.getCurrentLocation();
  if (location) {
    mapService.value.setCenter(location.lat, location.lng);
    mapService.value.setZoom(15);
  }
};

const fitAllMarkers = () => {
  if (!mapService.value) return;
  mapService.value.fitBounds();
};
</script>

<style scoped>
.map-component {
  position: relative;
}

.map-container {
  width: 100%;
  border-radius: 8px;
  overflow: hidden;
}

.map-controls {
  position: absolute;
  top: 10px;
  right: 10px;
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.btn {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 8px 12px;
  background: white;
  border: 1px solid #ddd;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  transition: all 0.2s;
}

.btn:hover {
  background: #f5f5f5;
  box-shadow: 0 4px 6px rgba(0,0,0,0.15);
}

.btn-sm {
  padding: 6px 10px;
  font-size: 13px;
}
</style>

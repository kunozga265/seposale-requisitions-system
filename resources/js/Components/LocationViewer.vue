<template>
    <div class="location-viewer">
        <div ref="mapContainer" class="map-container"></div>
    </div>
</template>

<script>
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

// Fix for default Leaflet marker icons in Webpack/Vue CLI
import markerIcon2x from 'leaflet/dist/images/marker-icon-2x.png';
import markerIcon from 'leaflet/dist/images/marker-icon.png';
import markerShadow from 'leaflet/dist/images/marker-shadow.png';

delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl: markerIcon2x,
    iconUrl: markerIcon,
    shadowUrl: markerShadow
});

export default {
    name: 'LocationViewer',
    props: {
        locationData: {
            type: Object,
            required: true,
            // Expected format: { lat: -13.9626, lng: 33.7741, name: 'Area 47' }
        }
    },
    created() {
        // Non-reactive variables to hold the Leaflet instances
        this.map = null;
        this.marker = null;
    },
    mounted() {
        this.initMap();
    },
    beforeDestroy() {
        // Cleanup to prevent memory leaks when navigating away
        if (this.map) {
            this.map.remove();
        }
    },
    watch: {
        // Watch for prop changes: If the parent updates the location, move the map!
        locationData: {
            deep: true,
            handler(newLocation) {
                this.updateMap(newLocation);
            }
        }
    },
    methods: {
        initMap() {
            // Safety check
            if (!this.locationData || !this.locationData.lat || !this.locationData.lng) {
                console.error("Invalid or missing locationData provided to LocationViewer");
                return;
            }

            const { lat, lng, name } = this.locationData;

            // 1. Initialize Map using this.$refs
            this.map = L.map(this.$refs.mapContainer).setView([lat, lng], 15);

            // 2. Add OpenStreetMap Tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(this.map);

            // 3. Add Marker
            this.marker = L.marker([lat, lng]).addTo(this.map);
            
            // 4. (Optional) Add popup if a name was provided
            if (name) {
                this.marker.bindPopup(`<b>${name}</b>`).openPopup();
            }
        },
        
        updateMap(newLocation) {
            if (newLocation && newLocation.lat && newLocation.lng && this.map && this.marker) {
                const newLatLng = [newLocation.lat, newLocation.lng];
                
                // Move the map and the marker
                this.map.setView(newLatLng, 15);
                this.marker.setLatLng(newLatLng);
                
                // Update popup text
                if (newLocation.name) {
                    this.marker.bindPopup(`<b>${newLocation.name}</b>`).openPopup();
                } else {
                    this.marker.closePopup();
                    this.marker.unbindPopup();
                }
            }
        }
    }
};
</script>

<style scoped>
.map-container {
    height: 350px;
    width: 100%;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    z-index: 10;
}
</style>
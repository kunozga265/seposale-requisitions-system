<template>
    <div class="zone-picker">
        <div class="mb-3" style="display: flex; gap: 8px;">
            <button 
                type="button" 
                @click="undoLastPoint" 
                :disabled="points.length === 0"
                class="btn-yellow"
            >
                Undo Last Point
            </button>
            <button 
                type="button" 
                @click="clearZone" 
                :disabled="points.length === 0"
                class="btn-red"
            >
                Clear Zone
            </button>
            <button 
                type="button" 
                @click="saveZone" 
                :disabled="points.length < 3"
                class="btn-green"
            >
                Save Zone
            </button>
        </div>

        <div id="zone-map" class="map-container"></div>
        
        <p class="mt-2 text-sm text-gray-500">
            <span v-if="points.length === 0">Click on the map to start drawing a zone.</span>
            <span v-else-if="points.length < 3">Click at least {{ 3 - points.length }} more point(s) to form a polygon.</span>
            <span v-else>Zone defined! Click "Save Zone" to confirm, or keep clicking to add more points.</span>
        </p>
    </div>
</template>

<script>
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

// Fix for default Leaflet marker icons in Vue CLI / Webpack
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
    name: 'ZonePicker',
    data() {
        return {
            // Only data that affects the UI/template goes here
            points: [], 
        };
    },
    created() {
        // Non-reactive variables (Do NOT put Leaflet objects in data())
        this.map = null;
        this.markers = [];
        this.polygonLayer = null;
    },
    mounted() {
        this.initMap();
    },
    beforeDestroy() {
        // Cleanup to prevent memory leaks
        if (this.map) {
            this.map.remove();
        }
    },
    methods: {
        initMap() {
            // Initialize Map (Centered on Lilongwe as an example)
            this.map = L.map('zone-map').setView([-13.9626, 33.7741], 13);

            // Add OpenStreetMap Tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(this.map);

            // Listen for clicks
            this.map.on('click', this.handleMapClick);
        },
        
        handleMapClick(e) {
            const { lat, lng } = e.latlng;
            
            // Add point to our reactive array
            this.points.push({ lat, lng });

            // Draw a marker for the vertex
            const marker = L.marker([lat, lng]).addTo(this.map);
            this.markers.push(marker);

            this.updatePolygon();
        },

        updatePolygon() {
            // Convert objects to array of arrays for Leaflet
            const latLngs = this.points.map(p => [p.lat, p.lng]);

            if (!this.polygonLayer) {
                // Create the polygon for the first time
                this.polygonLayer = L.polygon(latLngs, {
                    color: '#3b82f6',     
                    fillColor: '#60a5fa', 
                    fillOpacity: 0.4
                }).addTo(this.map);
            } else {
                // Update existing polygon with new points
                this.polygonLayer.setLatLngs(latLngs);
            }
        },

        undoLastPoint() {
            if (this.points.length === 0) return;

            // Remove last point from state
            this.points.pop();

            // Remove last marker from map
            const lastMarker = this.markers.pop();
            if (lastMarker) {
                this.map.removeLayer(lastMarker);
            }

            this.updatePolygon();
        },

        clearZone() {
            this.points = [];
            
            // Remove all markers
            this.markers.forEach(m => this.map.removeLayer(m));
            this.markers = [];

            // Remove polygon
            if (this.polygonLayer) {
                this.map.removeLayer(this.polygonLayer);
                this.polygonLayer = null;
            }
        },

        saveZone() {
            if (this.points.length >= 3) {
                // Emit a deep clone of the points array so the parent gets a clean object
                this.$emit('zone-saved', JSON.parse(JSON.stringify(this.points)));
            }
        }
    }
};
</script>

<style scoped>
.map-container {
    height: 400px;
    width: 100%;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    z-index: 10;
}

/* Basic button styles if you aren't using Tailwind */
.btn-yellow { padding: 6px 12px; background: #eab308; color: white; border: none; border-radius: 4px; cursor: pointer; }
.btn-red { padding: 6px 12px; background: #ef4444; color: white; border: none; border-radius: 4px; cursor: pointer; }
.btn-green { padding: 6px 12px; background: #16a34a; color: white; border: none; border-radius: 4px; cursor: pointer; }
button:disabled { opacity: 0.5; cursor: not-allowed; }
</style>
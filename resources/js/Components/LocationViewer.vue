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
            required: true
        },

        zone: {
            type: Object,
            default: null
            // Expected:
            // {
            //   coordinates: [
            //     { lat: -13.9, lng: 33.7 },
            //     { lat: -13.8, lng: 33.8 }
            //   ]
            // }
        }
    },

    created() {
        this.map = null;
        this.marker = null;
        this.polygon = null;
    },

    mounted() {
        this.initMap();
    },

    beforeDestroy() {
        if (this.map) {
            this.map.remove();
        }
    },

    watch: {
        locationData: {
            deep: true,
            handler() {
                this.updateMap();
            }
        },

        zone: {
            deep: true,
            handler() {
                this.updateZone();
            }
        }
    },

    methods: {
        initMap() {
            if (
                !this.locationData ||
                !this.locationData.lat ||
                !this.locationData.lng
            ) {
                console.error(
                    'Invalid or missing locationData provided to LocationViewer'
                );
                return;
            }

            const { lat, lng, name } = this.locationData;

            this.map = L.map(this.$refs.mapContainer).setView(
                [lat, lng],
                14
            );

            L.tileLayer(
                'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                {
                    attribution: '&copy; OpenStreetMap contributors'
                }
            ).addTo(this.map);

            this.marker = L.marker([lat, lng]).addTo(this.map);

            if (name) {
                this.marker.bindPopup(`<b>${name}</b>`).openPopup();
            }

            // Initial zone render
            this.updateZone();
        },

        updateMap() {
            if (
                !this.locationData ||
                !this.map ||
                !this.marker
            ) {
                return;
            }

            const { lat, lng, name } = this.locationData;

            if (!lat || !lng) {
                return;
            }

            const newLatLng = [lat, lng];

            this.map.setView(newLatLng, 15);
            this.marker.setLatLng(newLatLng);

            if (name) {
                this.marker.bindPopup(`<b>${name}</b>`).openPopup();
            } else {
                this.marker.closePopup();
                this.marker.unbindPopup();
            }
        },

        updateZone() {
            if (!this.map) {
                return;
            }

            // Remove previous polygon
            if (this.polygon) {
                this.map.removeLayer(this.polygon);
                this.polygon = null;
            }

            // Draw new polygon
            if (
                this.zone &&
                this.zone.coordinates &&
                this.zone.coordinates.length
            ) {
                const latLngs = this.zone.coordinates.map(coord => [
                    coord.lat,
                    coord.lng
                ]);

                this.polygon = L.polygon(latLngs, {
                    color: '#3b82f6',
                    fillColor: '#3b82f6',
                    fillOpacity: 0.3,
                    weight: 2
                }).addTo(this.map);
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
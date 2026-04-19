<template>
    <div class="max-w-4xl mx-auto p-4">
        <h2 class="text-xl font-bold mb-4">📍 Tentukan Lokasi Rumah</h2>

        <!-- GPS Button -->
        <div class="flex gap-2 mb-4">
            <button
                @click="getLocation"
                :disabled="loading"
                class="mb-3 px-4 py-2 rounded text-white"
                :class="loading ? 'bg-gray-400' : 'bg-blue-600'"
            >
                📍 Gunakan Lokasi Saya
            </button>
        </div>

        <!-- Map -->
        <div id="map" class="w-full h-96 rounded-2xl shadow mb-4"></div>

        <!-- Coordinates -->
        <div class="grid grid-cols-2 gap-2 mb-4">
            <input
                type="text"
                :value="latitude"
                readonly
                class="border p-2 rounded"
            />
            <input
                type="text"
                :value="longitude"
                readonly
                class="border p-2 rounded"
            />
        </div>

        <!-- Save Button -->
        <button
            @click="saveLocation"
            :disabled="!latitude || loading"
            class="w-full py-2 rounded text-white flex justify-center items-center gap-2"
            :class="
                !latitude || loading
                    ? 'bg-gray-400 cursor-not-allowed'
                    : 'bg-green-600'
            "
        >
            <!-- Spinner -->
            <svg
                v-if="loading"
                class="animate-spin h-5 w-5"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
            >
                <circle
                    class="opacity-25"
                    cx="12"
                    cy="12"
                    r="10"
                    stroke="currentColor"
                    stroke-width="4"
                ></circle>
                <path
                    class="opacity-75"
                    fill="currentColor"
                    d="M4 12a8 8 0 018-8v8z"
                ></path>
            </svg>

            <span>
                {{ loading ? "Menyimpan..." : "Simpan Lokasi" }}
            </span>
        </button>

        <!-- Overlay Loading -->
        <div
            v-if="loading"
            class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50"
        >
            <div class="bg-white p-4 rounded shadow">Menyimpan lokasi...</div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["studentId"],

    data() {
        return {
            map: null,
            marker: null,
            latitude: null,
            longitude: null,
            loading: false,
        };
    },

    mounted() {
        this.initMap();
    },

    methods: {
        initMap() {
            const defaultLoc = { lat: -5.1477, lng: 119.4327 };

            this.map = new google.maps.Map(document.getElementById("map"), {
                center: defaultLoc,
                zoom: 15,
            });

            this.map.addListener("click", (e) => {
                this.setMarker(e.latLng.lat(), e.latLng.lng());
            });
        },

        setMarker(lat, lng) {
            this.latitude = lat;
            this.longitude = lng;

            const position = { lat, lng };

            if (this.marker) {
                this.marker.setPosition(position);
            } else {
                this.marker = new google.maps.Marker({
                    position,
                    map: this.map,
                });
            }

            this.map.setCenter(position);
        },

        getLocation() {
            if (!navigator.geolocation) {
                window.$toast("Browser tidak mendukung GPS", "error");
                return;
            }

            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    this.setMarker(pos.coords.latitude, pos.coords.longitude);
                    window.$toast("Lokasi berhasil diambil", "info");
                },
                () => {
                    window.$toast("Gagal mengambil lokasi", "error");
                },
            );
        },

        async saveLocation() {
            if (!this.latitude) return;

            this.loading = true;

            try {
                const res = await fetch("/maps", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]',
                        ).content,
                    },
                    body: JSON.stringify({
                        latitude: this.latitude,
                        longitude: this.longitude,
                    }),
                });

                if (!res.ok) throw new Error();

                window.$toast("Lokasi berhasil disimpan!", "success");

                setTimeout(() => {
                    window.location.href = "/";
                }, 1200);
            } catch (e) {
                window.$toast("Gagal menyimpan lokasi", "error");
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>

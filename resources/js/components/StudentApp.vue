<template>
    <div class="max-w-6xl mx-auto p-4">
        <!-- TITLE -->
        <h1 class="text-2xl font-bold mb-6">📍 Verifikasi Alamat Siswa</h1>

        <!-- STATISTIK -->
        <div class="grid md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-5 rounded-2xl shadow">
                <p class="text-gray-500">Total Siswa</p>
                <h2 class="text-3xl font-bold">{{ students.length }}</h2>
            </div>

            <div class="bg-green-100 p-5 rounded-2xl shadow">
                <p class="text-green-700">Sudah Verifikasi</p>
                <h2 class="text-3xl font-bold text-green-700">
                    {{ verifiedCount }}
                </h2>
            </div>

            <div class="bg-red-100 p-5 rounded-2xl shadow">
                <p class="text-red-700">Belum Verifikasi</p>
                <h2 class="text-3xl font-bold text-red-700">
                    {{ unverifiedCount }}
                </h2>
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-3 mb-6">
            <!-- SEARCH -->
            <input
                v-model="search"
                placeholder="🔍 Cari Nama Siswa..."
                class="w-full border p-3 rounded-xl shadow-sm"
            />

            <!-- FILTER KELAS -->
            <select
                v-model="selectedClass"
                class="border p-3 rounded-xl shadow-sm"
            >
                <option value="">Semua Kelas</option>

                <option v-for="c in classes" :key="c" :value="c">
                    {{ c }}
                </option>
            </select>
        </div>

        <!-- LIST -->
        <div>
            <!-- ❌ TIDAK ADA HASIL -->
            <div
                v-if="filteredStudents.length === 0"
                class="text-center py-10 text-gray-500"
            >
                <p class="text-sm mt-2">
                    😕 Tidak ada hasil untuk "<span class="font-semibold">{{
                        search
                    }}</span
                    >"
                </p>
                <p class="text-sm mt-2">Coba periksa kembali Nama Siswa</p>
            </div>

            <!-- ✅ ADA DATA -->
            <div v-else class="grid md:grid-cols-2 gap-4">
                <div
                    v-for="s in filteredStudents"
                    :key="s.id"
                    @click="goToVerify(s)"
                    class="p-4 rounded-2xl shadow cursor-pointer transition hover:scale-[1.02]"
                    :class="
                        s.verification
                            ? 'bg-green-100 opacity-70 cursor-not-allowed'
                            : 'bg-red-100'
                    "
                >
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="font-semibold text-lg">{{ s.name }}</p>
                            <!-- <p class="text-sm text-gray-500">
                                NISN: {{ s.nisn }}
                            </p> -->
                        </div>

                        <div class="text-right">
                            <span
                                class="text-xs px-3 py-1 rounded-full bg-blue-100 text-blue-600"
                            >
                                {{ s.class }}
                            </span>

                            <div
                                v-if="s.verification"
                                class="text-green-600 text-xs mt-2"
                            >
                                ✔ Terverifikasi
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["initialStudents"],

    data() {
        return {
            students: this.initialStudents,
            search: "",
            selectedClass: "",
        };
    },

    mounted() {
        const seen = localStorage.getItem("guide_seen");

        if (!seen) {
            this.showGuide = true;
        }
    },

    computed: {
        classes() {
            const unique = new Set(this.students.map((s) => s.class));
            return Array.from(unique);
        },

        filteredStudents() {
            return this.students.filter((s) => {
                const matchSearch = s.name
                    .toLowerCase()
                    .includes(this.search.toLowerCase()); //||
                //s.nisn.includes(this.search);

                const matchClass =
                    this.selectedClass === "" || s.class === this.selectedClass;

                return matchSearch && matchClass;
            });
        },

        verifiedCount() {
            return this.students.filter((s) => s.verification).length;
        },
        unverifiedCount() {
            return this.students.length - this.verifiedCount;
        },
    },

    methods: {
        closeGuide() {
            this.showGuide = false;
            localStorage.setItem("guide_seen", "true");
        },

        goToVerify(s) {
            if (s.verification) return;
            window.location.href = `/verify/${s.id}`;
        },
    },
};
</script>

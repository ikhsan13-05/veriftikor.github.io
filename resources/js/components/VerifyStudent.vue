<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-50">
        <div class="bg-white p-6 rounded-2xl shadow w-full max-w-md">
            <h2 class="text-xl font-bold mb-2">🔐 Verifikasi Siswa</h2>
            <p class="text-gray-500 mb-4">Pastikan NISN sesuai</p>

            <!-- Error -->
            <div v-if="error" class="bg-red-100 text-red-600 p-2 mb-4 rounded">
                {{ error }}
            </div>

            <div class="bg-blue-50 p-3 rounded mb-4">
                <p class="font-semibold">{{ student.name }}</p>
            </div>

            <!-- Input -->
            <input
                type="text"
                v-model="nisn"
                @keyup.enter="submit"
                placeholder="Masukkan NISN"
                class="w-full border p-3 rounded-lg mb-4"
            />

            <button
                @click="submit"
                :disabled="loading || !nisn"
                class="w-full py-2 rounded text-white flex justify-center items-center gap-2"
                :class="
                    loading ? 'bg-gray-400 cursor-not-allowed' : 'bg-blue-600'
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
                    {{ loading ? "Memverifikasi..." : "Verifikasi" }}
                </span>
            </button>
        </div>
    </div>
</template>

<script>
export default {
    props: ["student"],

    data() {
        return {
            nisn: "",
            error: "",
            loading: false,
        };
    },

    methods: {
        async submit() {
            this.error = "";
            this.loading = true;

            try {
                const res = await fetch("/verify", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]',
                        ).content,
                    },
                    body: JSON.stringify({
                        student_id: this.student.id,
                        nisn: this.nisn,
                    }),
                });

                if (!res.ok) {
                    throw new Error("NISN salah");
                }

                window.location.href = "/maps";
            } catch (e) {
                this.error = "NISN tidak sesuai!";
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>

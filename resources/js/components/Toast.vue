<template>
    <div class="fixed top-5 right-5 space-y-2 z-50">
        <div
            v-for="(t, index) in toasts"
            :key="index"
            class="px-4 py-3 rounded shadow text-white flex items-center gap-2"
            :class="getColor(t.type)"
        >
            <span>{{ t.message }}</span>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            toasts: [],
        };
    },

    methods: {
        show(message, type = "success") {
            const toast = { message, type };
            this.toasts.push(toast);

            setTimeout(() => {
                this.toasts.shift();
            }, 3000);
        },

        getColor(type) {
            switch (type) {
                case "error":
                    return "bg-red-500";
                case "info":
                    return "bg-green-500";
                default:
                    return "bg-green-500";
            }
        },
    },
};
</script>

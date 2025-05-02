<template>
    <transition name="slide-fade">
        <div v-if="visible"
             :class="['fixed bottom-4 right-4 p-4 rounded-lg shadow-lg max-w-sm text-white text-sm z-50 flex items-center', alertClass]"
             role="alert">
            <font-awesome-icon :icon="iconClass" class="mr-3 flex-shrink-0 w-5 h-5" />
            <span>{{ message }}</span>
            <button @click="close" class="ml-4 -mr-1 p-1 hover:bg-white/20 rounded-full focus:outline-none">
                 <font-awesome-icon icon="fa-solid fa-times" class="w-3 h-3"/>
            </button>
        </div>
    </transition>
</template>

<script>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import emitter from '../eventBus';

export default {
    name: 'GlobalAlert',
    setup() {
        const visible = ref(false);
        const message = ref('');
        const type = ref('info'); // 'info', 'success', 'error'
        let timeoutId = null;

        const alertClass = computed(() => {
            switch (type.value) {
                case 'success': return 'bg-green-500';
                case 'error': return 'bg-red-600';
                case 'info':
                default: return 'bg-blue-500';
            }
        });

        const iconClass = computed(() => {
             switch (type.value) {
                case 'success': return ['fas', 'check-circle'];
                case 'error': return ['fas', 'exclamation-triangle'];
                case 'info':
                default: return ['fas', 'info-circle'];
            }
        });

        const showAlert = (data) => {
            message.value = data.message;
            type.value = data.type || 'info';
            visible.value = true;

            // Clear previous timeout if any
            if (timeoutId) {
                clearTimeout(timeoutId);
            }

            // Auto-hide after 5 seconds for info/success
            if (type.value !== 'error') {
                 timeoutId = setTimeout(() => {
                    close();
                }, 5000);
            }
        };

        const close = () => {
            visible.value = false;
             if (timeoutId) {
                clearTimeout(timeoutId);
                timeoutId = null;
            }
            // Optional: Reset message/type after fade out?
            // setTimeout(() => { message.value = ''; type.value = 'info'; }, 300);
        };

        onMounted(() => {
            emitter.on('show-alert', showAlert);
            // Add icons needed for alerts to the library (in case not added globally)
             // Assuming they are added globally in app.js
        });

        onUnmounted(() => {
            emitter.off('show-alert', showAlert);
            if (timeoutId) clearTimeout(timeoutId);
        });

        return {
            visible,
            message,
            type,
            alertClass,
            iconClass,
            close,
        };
    },
};
</script>

<style scoped>
/* Fade and slide transition */
.slide-fade-enter-active {
    transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
    transition: all 0.3s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
    transform: translateX(20px);
    opacity: 0;
}
</style>

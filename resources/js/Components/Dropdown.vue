<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue';

const props = defineProps({
    align: {
        default: 'right',
    },
    width: {
        default: '48',
    },
    contentClasses: {
        default: () => ['py-1', 'bg-white'],
    },
});

const open = ref(false);

const closeOnEscape = (e) => {
    if (open.value && e.key === 'Escape') {
        open.value = false;
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));
onUnmounted(() => document.removeEventListener('keydown', closeOnEscape));

const widthClass = computed(() => ({
    48: 'w-48',
    56: 'w-56',
    64: 'w-64',
    72: 'w-72',
}[props.width.toString()] || 'w-48'));

const alignmentClasses = computed(() => {
    if (props.align === 'left') {
        return 'origin-top-left left-0';
    }
    if (props.align === 'right') {
        return 'origin-top-right right-0';
    }
    return 'origin-top';
});

const toggle = () => {
    open.value = !open.value;
};

const close = () => {
    open.value = false;
};
</script>

<template>
    <div class="relative" :class="open ? 'z-[80]' : 'z-10'">
        <div @click.stop="toggle">
            <slot name="trigger" />
        </div>

        <div
            v-if="open"
            class="fixed inset-0 z-[70]"
            @click="close"
        />

        <transition
            enter-active-class="transition ease-out duration-150"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-100"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
        >
            <div
                v-if="open"
                class="absolute z-[90] mt-2 rounded-md shadow-lg"
                :class="[widthClass, alignmentClasses]"
                @click.stop
            >
                <div
                    class="rounded-md ring-1 ring-black ring-opacity-5 dark:ring-white/10"
                    :class="contentClasses"
                    @click="close"
                >
                    <slot name="content" />
                </div>
            </div>
        </transition>
    </div>
</template>

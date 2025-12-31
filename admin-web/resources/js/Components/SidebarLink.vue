<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
    icon: {
        type: [Object, Function],
    },
});

const classes = computed(() =>
    props.active
        ? 'flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-md bg-indigo-50 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300 transition-colors duration-200'
        : 'flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800/50 dark:hover:text-gray-200 transition-colors duration-200'
);
</script>

<template>
    <Link :href="href" :class="classes">
        <component :is="icon" v-if="icon" class="w-5 h-5" />
        <slot />
    </Link>
</template>

<script setup>
import { Link } from '@inertiajs/inertia-vue3';
</script>

<template>
    <Link class="block w-full px-4 py-2 text-right text-sm leading-5 text-slate-700 hover:bg-slate-50 focus:outline-none focus:bg-slate-50 transition duration-150 ease-in-out dark:text-slate-200 dark:hover:bg-slate-700">
        <slot />
    </Link>
</template>

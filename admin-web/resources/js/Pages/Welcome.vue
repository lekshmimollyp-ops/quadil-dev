<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});
</script>

<template>
    <Head title="Welcome to QUDIL" />
    <div class="min-h-screen bg-gray-50 dark:bg-gray-950 flex flex-col items-center justify-center p-6 transition-colors duration-300">
        <div class="w-full max-w-md bg-white dark:bg-gray-900 rounded-2xl shadow-xl p-8 border border-gray-100 dark:border-gray-800 text-center">
            <div class="flex justify-center mb-8">
                <ApplicationLogo class="h-12 w-auto" />
            </div>

            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Admin Control Center</h1>
            <p class="text-gray-500 dark:text-gray-400 mb-8">Unified platform for managing logistics, merchants, and agents.</p>

            <nav v-if="canLogin" class="flex flex-col gap-3">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200"
                >
                    Enter Dashboard
                </Link>

                <template v-else>
                    <Link
                        :href="route('login')"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200"
                    >
                        Log in
                    </Link>

                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 font-semibold py-3 px-4 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all duration-200"
                    >
                        Create Admin Account
                    </Link>
                </template>
            </nav>

            <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800">
                <p class="text-xs text-gray-400">QUDIL Professional Suite v1.0.0</p>
            </div>
        </div>
    </div>
</template>

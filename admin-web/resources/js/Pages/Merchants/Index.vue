<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Plus, Edit, Trash2, ExternalLink, Users, RotateCcw, Loader2 } from 'lucide-vue-next';
import { ref } from 'vue';

defineProps({
    merchants: {
        type: Array,
        default: () => [],
    },
});

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
};

const loadingId = ref(null);

const toggleStatus = (merchant) => {
    const action = merchant.is_active ? 'deactivate' : 'activate';
    if (window.confirm(`Are you sure you want to ${action} this merchant?`)) {
        loadingId.value = merchant.id;
        const form = useForm({});
        form.delete(route('merchants.destroy', merchant.id), {
            onFinish: () => loadingId.value = null
        });
    }
};
</script>

<template>
    <Head title="Merchants" />

    <AuthenticatedLayout>
        <template #header>
            Merchants
        </template>

        <div class="space-y-6">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Merchant Directory</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Manage all registered delivery partners and merchants.</p>
                </div>
                <Link
                    :href="route('merchants.create')"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    <Plus class="w-4 h-4 mr-2" />
                    Add Merchant
                </Link>
            </div>

            <!-- Empty State -->
            <div v-if="merchants.length === 0" class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-12 text-center">
                <div class="mx-auto w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                    <Users class="w-8 h-8 text-gray-400" />
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">No merchants found</h3>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Start by adding your first merchant partner.</p>
                <div class="mt-6">
                    <Link
                        :href="route('merchants.create')"
                        class="inline-flex items-center text-indigo-600 hover:text-indigo-500 font-medium"
                    >
                        Register new merchant
                    </Link>
                </div>
            </div>

            <!-- Merchant Table -->
            <div v-else class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-800">
                            <tr>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Domain/Slug</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Joined</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                            <tr v-for="merchant in merchants" :key="merchant.id" class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center text-indigo-600 font-bold">
                                            {{ merchant.name.charAt(0) }}
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900 dark:text-gray-100">{{ merchant.name }}</div>
                                            <div class="text-xs text-gray-500">{{ merchant.id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300">
                                        {{ merchant.type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center text-sm text-indigo-600 hover:text-indigo-500 font-medium cursor-pointer">
                                        {{ merchant.domain }}
                                        <ExternalLink class="w-3.5 h-3.5 ml-1.5" />
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span v-if="merchant.is_active" class="inline-flex items-center gap-1.5 text-green-600 text-sm font-medium">
                                        <span class="w-2 h-2 rounded-full bg-green-600"></span>
                                        Active
                                    </span>
                                    <span v-else class="inline-flex items-center gap-1.5 text-red-600 text-sm font-medium">
                                        <span class="w-2 h-2 rounded-full bg-red-600"></span>
                                        Inactive
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ formatDate(merchant.created_at) }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <Link
                                            :href="route('merchants.edit', merchant.id)"
                                            class="p-2 text-gray-400 hover:text-indigo-600 transition-colors"
                                            title="Edit Merchant"
                                        >
                                            <Edit class="w-4 h-4" />
                                        </Link>
                                        <button
                                            @click="toggleStatus(merchant)"
                                            :disabled="loadingId === merchant.id"
                                            :title="merchant.is_active ? 'Deactivate' : 'Activate'"
                                            :class="['p-2 rounded-lg transition-colors', merchant.is_active ? 'text-gray-400 hover:text-red-600 hover:bg-red-50' : 'text-indigo-600 hover:bg-indigo-50', loadingId === merchant.id && 'opacity-50 cursor-not-allowed']"
                                        >
                                            <Loader2 v-if="loadingId === merchant.id" class="w-4 h-4 animate-spin" />
                                            <template v-else>
                                                <RotateCcw v-if="!merchant.is_active" class="w-4 h-4" />
                                                <Trash2 v-else class="w-4 h-4" />
                                            </template>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

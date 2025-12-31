<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ShoppingCart, Eye, Clock, MapPin, User, Package, ArrowRight } from 'lucide-vue-next';

defineProps({
    orders: {
        type: Array,
        default: () => [],
    },
});

const getStatusColor = (status) => {
    switch (status.toLowerCase()) {
        case 'pending': return 'text-amber-600 bg-amber-50 dark:bg-amber-900/20';
        case 'assigned': return 'text-blue-600 bg-blue-50 dark:bg-blue-900/20';
        case 'in-transit': return 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20';
        case 'delivered': return 'text-green-600 bg-green-50 dark:bg-green-900/20';
        case 'cancelled': return 'text-red-600 bg-red-50 dark:bg-red-900/20';
        default: return 'text-gray-600 bg-gray-50';
    }
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleString('en-US', {
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};
</script>

<template>
    <Head title="Live Order Stream" />

    <AuthenticatedLayout>
        <template #header>
            Live Order Stream
        </template>

        <div class="space-y-6">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Active Bookings</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Monitor real-time delivery orders across the platform.</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="flex items-center gap-1.5 text-xs font-bold text-red-500 animate-pulse uppercase tracking-wider">
                        <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                        Live
                    </span>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="orders.length === 0" class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-12 text-center">
                <div class="mx-auto w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                    <ShoppingCart class="w-8 h-8 text-gray-400" />
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">No active orders</h3>
                <p class="text-gray-500 dark:text-gray-400 mt-1">New orders will appear here in real-time.</p>
            </div>

            <!-- Orders Table -->
            <div v-else class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-800">
                            <tr>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Order ID</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Route</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                            <tr v-for="order in orders" :key="order.id" class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <Package class="w-4 h-4 text-indigo-500" />
                                        <div>
                                            <div class="font-bold text-gray-900 dark:text-gray-100">#{{ order.id.substring(0, 8) }}</div>
                                            <div class="text-[10px] text-gray-500 flex items-center gap-1">
                                                <Clock class="w-3 h-3" />
                                                {{ formatDate(order.created_at) }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                        <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                            <User class="w-4 h-4 text-gray-500" />
                                        </div>
                                        User #{{ order.user_id }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400 max-w-[200px] truncate">
                                        <MapPin class="w-3.5 h-3.5 text-red-400" />
                                        {{ order.pickup_details.address || 'Pickup' }}
                                        <ArrowRight class="w-3 h-3 mx-1" />
                                        {{ order.delivery_details.address || 'Delivery' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span :class="['px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider', getStatusColor(order.status)]">
                                        {{ order.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-900 dark:text-gray-100">
                                    ${{ parseFloat(order.total_amount).toFixed(2) }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <Link
                                        :href="route('orders.show', order.id)"
                                        class="inline-flex items-center px-3 py-1.5 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-lg text-xs font-bold hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition-colors"
                                    >
                                        <Eye class="w-3.5 h-3.5 mr-1.5" />
                                        DISPATCH
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { 
    ShoppingCart, 
    Users, 
    Truck, 
    TrendingUp,
    ArrowUpRight,
    ArrowDownRight,
    Store, // Added Store icon
    DollarSign // Added DollarSign icon
} from 'lucide-vue-next';

const props = defineProps({
    metrics: {
        type: Object,
        default: () => ({
            total_orders: { value: '0', change: '0%', trend: 'up' },
            active_merchants: { value: '0', change: '0%', trend: 'up' },
            agents_online: { value: '0', change: '0%', trend: 'down' },
            today_revenue: { value: '$0', change: '0%', trend: 'up' },
        })
    },
    recentOrders: {
        type: Array,
        default: () => []
    }
});

const stats = [
    { 
        name: 'Total Orders', 
        value: props.metrics.total_orders.value, 
        icon: ShoppingCart, 
        change: props.metrics.total_orders.change, 
        changeType: props.metrics.total_orders.trend === 'up' ? 'increase' : 'decrease', 
        color: 'text-blue-600', 
        bg: 'bg-blue-50 dark:bg-blue-900/20' 
    },
    { 
        name: 'Active Merchants', 
        value: props.metrics.active_merchants.value, 
        icon: Store, // Changed icon to Store
        change: props.metrics.active_merchants.change, 
        changeType: props.metrics.active_merchants.trend === 'up' ? 'increase' : 'decrease', 
        color: 'text-purple-600', 
        bg: 'bg-purple-50 dark:bg-purple-900/20' 
    },
    { 
        name: 'Agents Online', 
        value: props.metrics.agents_online.value, 
        icon: Users, // Changed icon to Users
        change: props.metrics.agents_online.change, 
        changeType: props.metrics.agents_online.trend === 'up' ? 'increase' : 'decrease', 
        color: 'text-green-600', 
        bg: 'bg-green-50 dark:bg-green-900/20' 
    },
    { 
        name: "Today's Revenue", 
        value: props.metrics.today_revenue.value, 
        icon: DollarSign, // Changed icon to DollarSign
        change: props.metrics.today_revenue.change, 
        changeType: props.metrics.today_revenue.trend === 'up' ? 'increase' : 'decrease', 
        color: 'text-orange-600', 
        bg: 'bg-orange-50 dark:bg-orange-900/20' 
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            Dashboard Overview
        </template>

        <div class="space-y-8">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div 
                    v-for="stat in stats" 
                    :key="stat.name"
                    class="bg-white dark:bg-gray-900 p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-md transition-shadow"
                >
                    <div class="flex items-center justify-between mb-4">
                        <div :class="['p-2 rounded-xl', stat.bg]">
                            <component :is="stat.icon" :class="['w-6 h-6', stat.color]" />
                        </div>
                        <div 
                            :class="[
                                'flex items-center text-xs font-semibold px-2 py-1 rounded-full',
                                stat.changeType === 'increase' ? 'text-green-600 bg-green-50 dark:bg-green-900/20' : 'text-red-600 bg-red-50 dark:bg-red-900/20'
                            ]"
                        >
                            {{ stat.change }}
                            <component :is="stat.changeType === 'increase' ? ArrowUpRight : ArrowDownRight" class="w-3 h-3 ml-1" />
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white mb-1">
                        {{ stat.value }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 font-medium">
                        {{ stat.name }}
                    </div>
                </div>
            </div>

            <!-- Recent Activity Placeholder -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Recent Live Orders</h3>
                    <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                        <ShoppingCart class="w-12 h-12 mb-4 opacity-20" />
                        <p class="text-sm italic">Connecting to Order Service...</p>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Agent Performance</h3>
                    <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                        <BarChart3 class="w-12 h-12 mb-4 opacity-20" />
                        <p class="text-sm italic">Real-time stats coming soon</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

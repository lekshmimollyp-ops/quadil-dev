<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { 
    TrendingUp, 
    TrendingDown, 
    ShoppingBag, 
    DollarSign, 
    Users, 
    ClipboardCheck, 
    AlertCircle,
    ArrowUpRight,
    ArrowDownRight,
    Globe,
    Activity
} from 'lucide-vue-next';

const props = defineProps({
    platformStats: {
        type: Object,
        default: () => ({
            total_tenants: 0,
            platform_total_orders: 0,
            platform_total_revenue: 0,
            platform_completed_orders: 0,
        }),
    },
    todayRevenue: {
        type: Object,
        default: () => ({
            amount: 0,
            change: '+0.0%',
            trend: 'up',
        }),
    },
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(value);
};

const formatNumber = (value) => {
    return new Intl.NumberFormat('en-US').format(value);
};
</script>

<template>
    <Head title="MIS Analytics" />

    <AuthenticatedLayout>
        <template #header>
            Platform Intelligence
        </template>

        <div class="space-y-8">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">Executive Dashboard</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">High-level KPIs and platform growth metrics.</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="px-4 py-2 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl border border-indigo-100 dark:border-indigo-800 flex items-center gap-2">
                        <Activity class="w-4 h-4 text-indigo-600" />
                        <span class="text-xs font-bold text-indigo-700 dark:text-indigo-400 uppercase tracking-wider">System Healthy</span>
                    </div>
                </div>
            </div>

            <!-- KPI Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Revenue Card -->
                <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-green-50 dark:bg-green-900/10 rounded-full blur-2xl group-hover:bg-green-100 transition-colors"></div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-xl">
                            <DollarSign class="w-6 h-6 text-green-600" />
                        </div>
                        <span :class="['flex items-center gap-1 text-xs font-bold px-2 py-1 rounded-lg', todayRevenue.trend === 'up' ? 'text-green-600 bg-green-50' : 'text-red-600 bg-red-50']">
                            <TrendingUp v-if="todayRevenue.trend === 'up'" class="w-3 h-3" />
                            <TrendingDown v-else class="w-3 h-3" />
                            {{ todayRevenue.change }}
                        </span>
                    </div>
                    <div class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-1">Total Revenue</div>
                    <div class="text-3xl font-black text-gray-900 dark:text-white">{{ formatCurrency(platformStats.platform_total_revenue) }}</div>
                </div>

                <!-- Orders Card -->
                <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-50 dark:bg-indigo-900/10 rounded-full blur-2xl group-hover:bg-indigo-100 transition-colors"></div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl">
                            <ShoppingBag class="w-6 h-6 text-indigo-600" />
                        </div>
                    </div>
                    <div class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-1">Total Orders</div>
                    <div class="text-3xl font-black text-gray-900 dark:text-white">{{ formatNumber(platformStats.platform_total_orders) }}</div>
                </div>

                <!-- Tenants Card -->
                <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-50 dark:bg-amber-900/10 rounded-full blur-2xl group-hover:bg-amber-100 transition-colors"></div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-amber-50 dark:bg-amber-900/20 rounded-xl">
                            <Users class="w-6 h-6 text-amber-600" />
                        </div>
                    </div>
                    <div class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-1">Active Merchants</div>
                    <div class="text-3xl font-black text-gray-900 dark:text-white">{{ formatNumber(platformStats.total_tenants) }}</div>
                </div>

                <!-- Completion Rate Card -->
                <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-purple-50 dark:bg-purple-900/10 rounded-full blur-2xl group-hover:bg-purple-100 transition-colors"></div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-purple-50 dark:bg-purple-900/20 rounded-xl">
                            <ClipboardCheck class="w-6 h-6 text-purple-600" />
                        </div>
                    </div>
                    <div class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-1">Completed Orders</div>
                    <div class="text-3xl font-black text-gray-900 dark:text-white">{{ formatNumber(platformStats.platform_completed_orders) }}</div>
                </div>
            </div>

            <!-- Visual Charts Placeholder / Secondary Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Data Visual Simulation -->
                <div class="lg:col-span-2 bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 p-8 shadow-sm">
                    <div class="flex justify-between items-center mb-10">
                        <h4 class="font-black text-gray-900 dark:text-white flex items-center gap-2">
                            <TrendingUp class="w-5 h-5 text-indigo-500" />
                            Order Distribution
                        </h4>
                        <select class="text-xs font-bold bg-gray-50 dark:bg-gray-800 border-none rounded-lg focus:ring-0">
                            <option>Last 7 Days</option>
                            <option>Last 30 Days</option>
                        </select>
                    </div>

                    <!-- CSS-based Chart Simulation for "Wow" factor without external deps -->
                    <div class="flex items-end justify-between h-48 gap-2 mb-4">
                        <div v-for="h in [40, 60, 45, 90, 75, 55, 85]" :key="h" class="flex-1 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl relative group">
                            <div :style="{ height: h + '%' }" class="absolute bottom-0 left-0 right-0 bg-indigo-500 rounded-xl transition-all group-hover:bg-indigo-400 group-hover:scale-x-105"></div>
                            <div class="absolute -top-8 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity bg-indigo-900 text-white text-[10px] py-1 px-2 rounded">{{ h }}%</div>
                        </div>
                    </div>
                    <div class="flex justify-between text-[10px] font-black text-gray-400 uppercase tracking-widest px-2">
                        <span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span><span>Sun</span>
                    </div>
                </div>

                <!-- Platform Health Section -->
                <div class="bg-gray-900 rounded-3xl p-8 text-white relative overflow-hidden shadow-2xl">
                    <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-indigo-600 rounded-full blur-[100px] opacity-20"></div>
                    <h4 class="font-black text-white flex items-center gap-2 mb-8 lowercase tracking-tighter text-xl">
                        <Activity class="w-5 h-5 text-indigo-400" />
                        platform_health
                    </h4>
                    
                    <div class="space-y-6">
                        <div class="flex justify-between items-end pb-4 border-b border-gray-800">
                            <div>
                                <div class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-1">API Latency</div>
                                <div class="text-lg font-bold">42ms</div>
                            </div>
                            <div class="text-green-500 text-xs font-bold">Optimal</div>
                        </div>
                        <div class="flex justify-between items-end pb-4 border-b border-gray-800">
                            <div>
                                <div class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-1">Error Rate</div>
                                <div class="text-lg font-bold">0.02%</div>
                            </div>
                            <div class="text-green-500 text-xs font-bold">Safe</div>
                        </div>
                        <div class="flex justify-between items-end pb-4 border-b border-gray-800">
                            <div>
                                <div class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-1">Fleet Online</div>
                                <div class="text-lg font-bold">88%</div>
                            </div>
                            <div class="text-amber-500 text-xs font-bold">Warning</div>
                        </div>
                        
                        <div class="pt-4">
                            <button class="w-full py-3 bg-white/10 hover:bg-white/20 rounded-2xl text-xs font-black uppercase tracking-widest transition-all">
                                View Full Logs
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

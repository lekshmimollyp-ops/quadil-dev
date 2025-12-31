<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { 
    DollarSign, 
    CreditCard, 
    TrendingUp, 
    TrendingDown, 
    ArrowUpRight, 
    ArrowDownRight,
    PieChart,
    ArrowRightLeft
} from 'lucide-vue-next';

const props = defineProps({
    summary: {
        type: Object,
        default: () => ({
            platform_revenue: 0,
            platform_expense: 0,
            total_transactions: 0
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
    <Head title="Financial Ledgers" />

    <AuthenticatedLayout>
        <template #header>
            Accounting & MIS
        </template>

        <div class="space-y-8">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight uppercase">Usage Ledgers</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Platform-level financial aggregation and transaction audits.</p>
                </div>
                <div class="flex items-center gap-3">
                    <button class="px-4 py-2 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-gray-50 transition-colors">
                        Export Report
                    </button>
                    <button class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100 dark:shadow-none">
                        Reconcile Entries
                    </button>
                </div>
            </div>

            <!-- Financial KPIs -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Platform Revenue -->
                <div class="bg-gray-900 rounded-3xl p-8 text-white relative overflow-hidden shadow-2xl group">
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-600 rounded-full blur-[80px] opacity-20 group-hover:opacity-30 transition-opacity"></div>
                    <div class="flex justify-between items-start mb-8 relative z-10">
                        <div class="p-3 bg-white/10 rounded-2xl backdrop-blur-md">
                            <DollarSign class="w-6 h-6 text-indigo-400" />
                        </div>
                        <div class="flex items-center gap-1 text-green-400 text-xs font-black bg-green-400/10 px-2 py-1 rounded-lg">
                            <TrendingUp class="w-3 h-3" />
                            +12.5%
                        </div>
                    </div>
                    <div class="text-[10px] font-black text-indigo-300 uppercase tracking-[0.2em] mb-2 relative z-10">Platform Gross Revenue</div>
                    <div class="text-4xl font-black relative z-10 tracking-tighter">{{ formatCurrency(summary.platform_revenue) }}</div>
                    <div class="mt-8 pt-6 border-t border-white/10 flex justify-between items-center relative z-10">
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Total Transaction Value</span>
                        <ArrowUpRight class="w-4 h-4 text-gray-500" />
                    </div>
                </div>

                <!-- Platform Expense -->
                <div class="bg-white dark:bg-gray-900 rounded-3xl p-8 border border-gray-200 dark:border-gray-800 shadow-sm relative overflow-hidden group">
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-red-50 dark:bg-red-900/10 rounded-full blur-[80px] group-hover:bg-red-100 transition-colors"></div>
                    <div class="flex justify-between items-start mb-8 relative z-10">
                        <div class="p-3 bg-red-50 dark:bg-red-900/20 rounded-2xl">
                            <CreditCard class="w-6 h-6 text-red-600" />
                        </div>
                        <div class="flex items-center gap-1 text-red-600 text-xs font-black bg-red-50 px-2 py-1 rounded-lg">
                            <TrendingDown class="w-3 h-3" />
                            -2.4%
                        </div>
                    </div>
                    <div class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 relative z-10">Operating Outflow</div>
                    <div class="text-4xl font-black text-gray-900 dark:text-white relative z-10 tracking-tighter">{{ formatCurrency(summary.platform_expense) }}</div>
                    <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800 flex justify-between items-center relative z-10">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Disbursements / Refunds</span>
                        <ArrowDownRight class="w-4 h-4 text-gray-400" />
                    </div>
                </div>

                <!-- Net Position -->
                <div class="bg-white dark:bg-gray-900 rounded-3xl p-8 border border-gray-200 dark:border-gray-800 shadow-sm relative overflow-hidden group">
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-amber-50 dark:bg-amber-900/10 rounded-full blur-[80px] group-hover:bg-amber-100 transition-colors"></div>
                    <div class="flex justify-between items-start mb-8 relative z-10">
                        <div class="p-3 bg-amber-50 dark:bg-amber-900/20 rounded-2xl">
                            <PieChart class="w-6 h-6 text-amber-600" />
                        </div>
                    </div>
                    <div class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 relative z-10">Platform Net Profit</div>
                    <div class="text-4xl font-black text-gray-900 dark:text-white relative z-10 tracking-tighter">{{ formatCurrency(summary.platform_revenue - summary.platform_expense) }}</div>
                    <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800 flex justify-between items-center relative z-10">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ formatNumber(summary.total_transactions) }} Operations</span>
                        <ArrowRightLeft class="w-4 h-4 text-gray-400" />
                    </div>
                </div>
            </div>

            <!-- Ledger Activity Placeholder -->
            <div class="bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 p-8 shadow-sm">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10">
                    <h4 class="font-black text-gray-900 dark:text-white flex items-center gap-3 uppercase tracking-tighter text-xl">
                        <ArrowRightLeft class="w-6 h-6 text-indigo-500" />
                        Audit Stream
                    </h4>
                    <div class="flex gap-2">
                         <button class="px-4 py-2 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 rounded-xl text-[10px] font-black uppercase tracking-widest border border-indigo-100 dark:border-indigo-800">
                             Recent Activity
                         </button>
                         <button class="px-4 py-2 bg-gray-50 dark:bg-gray-800 text-gray-500 rounded-xl text-[10px] font-black uppercase tracking-widest border border-transparent">
                             Disputed Entries
                         </button>
                    </div>
                </div>

                <div class="space-y-4">
                    <div v-if="summary.total_transactions === 0" class="text-center py-20 text-gray-400">
                        <DollarSign class="w-12 h-12 mx-auto mb-4 opacity-10" />
                        <p class="font-bold uppercase tracking-widest text-[10px]">No transaction history available in the ledger.</p>
                    </div>
                    <div v-else class="text-center py-10">
                        <p class="text-sm text-gray-500">Global ledger stream requires merchant-specific lookup in this version.</p>
                        <button class="mt-4 px-6 py-2 bg-gray-900 dark:bg-white dark:text-gray-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest">
                            Search by Merchant
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

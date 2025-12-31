<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { 
    ArrowLeft, 
    ArrowUpRight, 
    ArrowDownLeft, 
    Calendar,
    Search,
    Download,
    Filter
} from 'lucide-vue-next';
import { ref, computed } from 'vue';

const props = defineProps({
    tenant_id: {
        type: String,
        required: true,
    },
    merchant_name: {
        type: String,
        default: 'Merchant Ledger',
    },
    ledgerData: {
        type: Object,
        default: () => ({ summary: {}, entries: [] }),
    },
});

const search = ref('');
const typeFilter = ref('all');

const formatCurrency = (value) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(value);
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const filteredEntries = computed(() => {
    let entries = props.ledgerData.entries || [];
    
    if (typeFilter.value !== 'all') {
        entries = entries.filter(entry => entry.type === typeFilter.value);
    }
    
    if (search.value) {
        const query = search.value.toLowerCase();
        entries = entries.filter(entry => 
            entry.description?.toLowerCase().includes(query) || 
            entry.reference_id?.toLowerCase().includes(query)
        );
    }
    
    return entries;
});
</script>

<template>
    <Head title="Merchant Ledger" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Link :href="route('wallets.index')" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-full transition-colors">
                    <ArrowLeft class="w-5 h-5 text-gray-500" />
                </Link>
                <div>
                    <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">{{ merchant_name }}</h2>
                    <p class="text-xs text-gray-500 font-mono mt-0.5">ID: {{ tenant_id }}</p>
                </div>
            </div>
        </template>

        <div class="space-y-6">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total Credited -->
                <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 border border-gray-100 dark:border-gray-800 shadow-sm">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 bg-green-50 dark:bg-green-900/20 rounded-lg">
                            <ArrowDownLeft class="w-5 h-5 text-green-600" />
                        </div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Credited</span>
                    </div>
                    <div class="text-2xl font-black text-gray-900 dark:text-white">
                        {{ formatCurrency(ledgerData.summary?.total_credited || 0) }}
                    </div>
                </div>

                <!-- Total Debited -->
                <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 border border-gray-100 dark:border-gray-800 shadow-sm">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 bg-red-50 dark:bg-red-900/20 rounded-lg">
                            <ArrowUpRight class="w-5 h-5 text-red-600" />
                        </div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Debited</span>
                    </div>
                    <div class="text-2xl font-black text-gray-900 dark:text-white">
                        {{ formatCurrency(ledgerData.summary?.total_debited || 0) }}
                    </div>
                </div>

                <!-- Net Balance -->
                <div class="bg-indigo-600 rounded-2xl p-6 shadow-lg shadow-indigo-200 dark:shadow-none text-white">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                            <Calendar class="w-5 h-5 text-white" />
                        </div>
                        <span class="text-xs font-bold text-indigo-100 uppercase tracking-wider">Net Balance</span>
                    </div>
                    <div class="text-2xl font-black">
                        {{ formatCurrency((ledgerData.summary?.total_credited || 0) - (ledgerData.summary?.total_debited || 0)) }}
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="flex flex-col md:flex-row gap-4">
                <div class="relative flex-1">
                    <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                    <input 
                        v-model="search"
                        type="text" 
                        placeholder="Search transactions..." 
                        class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-2 focus:ring-indigo-500 text-sm"
                    />
                </div>
                <div class="flex gap-2">
                    <select 
                        v-model="typeFilter"
                        class="px-4 py-2.5 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-2 focus:ring-indigo-500 text-sm font-medium"
                    >
                        <option value="all">All Types</option>
                        <option value="credit">Credits Only</option>
                        <option value="debit">Debits Only</option>
                    </select>
                    <button class="px-4 py-2.5 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl hover:bg-gray-50 flex items-center gap-2 text-sm font-bold text-gray-700 dark:text-gray-300">
                        <Download class="w-4 h-4" />
                        Export
                    </button>
                </div>
            </div>

            <!-- Transactions List -->
            <div class="bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div v-if="filteredEntries.length > 0" class="divide-y divide-gray-100 dark:divide-gray-800">
                    <div v-for="entry in filteredEntries" :key="entry.id" class="p-6 hover:bg-gray-50/50 dark:hover:bg-gray-800/50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-start gap-4">
                                <div 
                                    class="w-10 h-10 rounded-full flex items-center justify-center shrink-0"
                                    :class="entry.type === 'credit' ? 'bg-green-100 text-green-600 dark:bg-green-900/20' : 'bg-red-100 text-red-600 dark:bg-red-900/20'"
                                >
                                    <ArrowDownLeft v-if="entry.type === 'credit'" class="w-5 h-5" />
                                    <ArrowUpRight v-else class="w-5 h-5" />
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 dark:text-white text-sm">{{ entry.description }}</h4>
                                    <p class="text-xs text-gray-500 mt-1 font-mono">Ref: {{ entry.reference_id }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div 
                                    class="text-lg font-black tabular-nums"
                                    :class="entry.type === 'credit' ? 'text-green-600' : 'text-gray-900 dark:text-white'"
                                >
                                    {{ entry.type === 'credit' ? '+' : '-' }}{{ formatCurrency(entry.amount) }}
                                </div>
                                <div class="text-xs text-gray-400 mt-1">{{ formatDate(entry.created_at) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else class="p-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                        <Filter class="w-6 h-6 text-gray-400" />
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">No transactions found</h3>
                    <p class="text-gray-500 text-sm mt-1">Try adjusting your filters or search query.</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

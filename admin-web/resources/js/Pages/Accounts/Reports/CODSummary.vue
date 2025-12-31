<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { 
    Banknote, 
    ArrowRightLeft, 
    User, 
    Clock, 
    CheckCircle2,
    Search,
    Filter,
    FileText
} from 'lucide-vue-next';

const props = defineProps({
    codData: {
        type: Array,
        default: () => [],
    },
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(value);
};
</script>

<template>
    <Head title="COD Summary Report" />

    <AuthenticatedLayout>
        <template #header>
            Financial Reporting
        </template>

        <div class="space-y-6">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">COD Collection Summary</h3>
                    <p class="text-sm text-gray-500 font-medium">Monitoring agent cash collections and remittance status.</p>
                </div>
                <div class="flex gap-3">
                     <button class="px-4 py-2 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-50 transition-colors flex items-center gap-2">
                        <FileText class="w-4 h-4" />
                        Export PDF
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total CODs collected -->
                <div class="bg-indigo-600 rounded-3xl p-6 text-white shadow-xl shadow-indigo-100 dark:shadow-none">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-2 bg-white/20 rounded-xl">
                            <Banknote class="w-5 h-5 text-white" />
                        </div>
                    </div>
                    <div class="text-[10px] font-black text-indigo-100 uppercase tracking-widest mb-1">Total Collections</div>
                    <div class="text-3xl font-black tracking-tighter">$4,240.50</div>
                </div>

                <!-- Pending Remittance -->
                <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 border border-gray-200 dark:border-gray-800 shadow-sm text-amber-600">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-2 bg-amber-50 dark:bg-amber-900/20 rounded-xl">
                            <Clock class="w-5 h-5" />
                        </div>
                    </div>
                    <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Pending Remittance</div>
                    <div class="text-3xl font-black tracking-tighter">$570.00</div>
                </div>

                <!-- Settled -->
                <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 border border-gray-200 dark:border-gray-800 shadow-sm text-emerald-600">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-2 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl">
                            <CheckCircle2 class="w-5 h-5" />
                        </div>
                    </div>
                    <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Settled This Month</div>
                    <div class="text-3xl font-black tracking-tighter">$3,670.50</div>
                </div>
            </div>

            <!-- Collections Table -->
            <div class="bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 overflow-hidden shadow-sm">
                <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
                    <div class="relative w-72">
                        <Search class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                        <input type="text" placeholder="Search Agents..." class="w-full pl-10 pr-4 py-3 bg-gray-50 dark:bg-gray-950 border border-gray-100 dark:border-gray-800 rounded-2xl text-[10px] font-black uppercase tracking-widest" />
                    </div>
                    <button class="p-3 bg-gray-50 dark:bg-gray-800 rounded-2xl text-gray-500 hover:text-indigo-600 transition-colors">
                        <Filter class="w-5 h-5" />
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 dark:bg-gray-800/50">
                            <tr>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Agent</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Total Collected</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">On-Hand (Pending)</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Last Settlement</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                            <tr v-for="item in codData" :key="item.agent_id" class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                            <User class="w-5 h-5 text-gray-400" />
                                        </div>
                                        <div>
                                            <div class="font-black text-gray-900 dark:text-white uppercase tracking-tighter">{{ item.agent_name }}</div>
                                            <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest underline underline-offset-2 decoration-gray-200">ID:{{ item.agent_id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-black text-gray-900 dark:text-white">{{ formatCurrency(item.collections) }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div :class="['text-sm font-black', item.pending_remittance > 0 ? 'text-amber-600' : 'text-emerald-600']">
                                        {{ formatCurrency(item.pending_remittance) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">{{ item.last_remittance }}</div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button class="px-4 py-2 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-100 transition-all">
                                        Reconcile
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

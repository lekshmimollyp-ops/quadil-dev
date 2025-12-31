<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { 
    Wallet, 
    Plus, 
    History, 
    ShieldCheck, 
    CreditCard, 
    Building2,
    Info,
    ArrowUpRight,
    Edit3,
    X,
    DollarSign,
    Zap,
    AlertTriangle
} from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps({
    wallets: {
        type: Array,
        default: () => [],
    },
});

const isTopupModalOpen = ref(false);
const isCreditModalOpen = ref(false);
const selectedWallet = ref(null);

const topupForm = useForm({
    tenant_id: '',
    amount: '',
    description: 'Manual Admin Top-up',
});

const creditForm = useForm({
    tenant_id: '',
    credit_limit: '',
});

const openTopupModal = (wallet) => {
    selectedWallet.value = wallet;
    topupForm.tenant_id = wallet.tenant_id;
    isTopupModalOpen.value = true;
};

const openCreditModal = (wallet) => {
    selectedWallet.value = wallet;
    creditForm.tenant_id = wallet.tenant_id;
    creditForm.credit_limit = wallet.credit_limit;
    isCreditModalOpen.value = true;
};

const submitTopup = () => {
    topupForm.post(route('wallets.topup'), {
        onSuccess: () => {
            isTopupModalOpen.value = false;
            topupForm.reset();
        },
    });
};

const submitCreditUpdate = () => {
    creditForm.patch(route('wallets.credit-limit'), {
        onSuccess: () => {
            isCreditModalOpen.value = false;
        },
    });
};

const formatCurrency = (value) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(value);
};

const getTotalBuyingPower = (wallet) => {
    return parseFloat(wallet.advance_balance) + parseFloat(wallet.credit_limit);
};

const getWalletStatus = (wallet) => {
    if (wallet.is_active === false || wallet.is_active === 0) {
        return { label: 'Suspended', classes: 'bg-red-50 text-red-600 dark:bg-red-900/20', icon: X };
    }
    
    const buyingPower = getTotalBuyingPower(wallet);
    
    if (buyingPower <= 500) { // Critical threshold
        return { label: 'Critical', classes: 'bg-red-50 text-red-600 dark:bg-red-900/20', icon: X };
    }
    
    if (buyingPower < 3000) { // Low balance warning
        return { label: 'Low Balance', classes: 'bg-amber-50 text-amber-600 dark:bg-amber-900/20', icon: AlertTriangle };
    }
    
    return { label: 'Operational', classes: 'bg-green-50 text-green-600 dark:bg-green-900/20', icon: ShieldCheck };
};
</script>

<template>
    <Head title="Merchant Wallets" />

    <AuthenticatedLayout>
        <template #header>
            Credit Management
        </template>

        <div class="space-y-6">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">Finance Governance</h3>
                    <p class="text-sm text-gray-500 font-medium">Monitoring advance balances and authorized credit thresholds.</p>
                </div>
            </div>

            <!-- Wallets Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="wallet in wallets" :key="wallet.tenant_id" class="bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden group hover:border-indigo-500 transition-all flex flex-col">
                    <div class="p-8 flex-1">
                        <div class="flex justify-between items-start mb-6">
                            <div class="p-3 bg-indigo-50 dark:bg-indigo-900/20 rounded-2xl">
                                <Building2 class="w-6 h-6 text-indigo-600" />
                            </div>
                            <div 
                                class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest"
                                :class="getWalletStatus(wallet).classes"
                            >
                                <component :is="getWalletStatus(wallet).icon" class="w-3 h-3" />
                                {{ getWalletStatus(wallet).label }}
                            </div>
                        </div>
                        
                        <h4 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tighter mb-1">{{ wallet.merchant_name }}</h4>
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-8 flex items-center gap-1">
                            ID: {{ wallet.tenant_id.substring(0, 8) }}...
                        </div>

                        <div class="space-y-6">
                            <div class="flex justify-between items-end border-b border-gray-50 dark:border-gray-800 pb-4">
                                <div>
                                    <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Advance Cash</div>
                                    <div class="text-2xl font-black text-gray-900 dark:text-white">{{ formatCurrency(wallet.advance_balance) }}</div>
                                </div>
                                <button @click="openTopupModal(wallet)" class="p-2 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl hover:bg-black hover:text-white transition-all">
                                    <Plus class="w-4 h-4" />
                                </button>
                            </div>

                            <div class="flex justify-between items-end border-b border-gray-50 dark:border-gray-800 pb-4 text-indigo-600">
                                <div>
                                    <div class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-1">Credit Limit</div>
                                    <div class="text-2xl font-black">{{ formatCurrency(wallet.credit_limit) }}</div>
                                </div>
                                <button @click="openCreditModal(wallet)" class="p-2 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl hover:bg-indigo-600 hover:text-white transition-all">
                                    <Edit3 class="w-4 h-4" />
                                </button>
                            </div>

                            <div class="pt-2">
                                <div class="flex justify-between items-center mb-2">
                                    <div class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Total Purchasing Power</div>
                                    <div class="text-xs font-black text-gray-900 dark:text-white">{{ formatCurrency(getTotalBuyingPower(wallet)) }}</div>
                                </div>
                                <div class="w-full h-2 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
                                     <div 
                                        class="h-full bg-indigo-600 rounded-full transition-all duration-500" 
                                        :style="{ width: (getTotalBuyingPower(wallet) > 0 ? (wallet.advance_balance / getTotalBuyingPower(wallet)) * 100 : 0) + '%' }"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-800/20 p-4 border-t border-gray-100 dark:border-gray-800">
                        <Link 
                            :href="route('accounts.merchant-ledger', wallet.tenant_id)"
                            class="w-full flex items-center justify-center gap-2 py-3 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 text-gray-900 dark:text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-50 transition-colors shadow-sm"
                        >
                            <History class="w-3.5 h-3.5" />
                            Audit History
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top-up Modal -->
        <div v-if="isTopupModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
            <div class="bg-white dark:bg-gray-900 rounded-3xl w-full max-w-md border border-gray-200 dark:border-gray-800 shadow-2xl overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/50">
                    <h4 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">Authorize Recharge</h4>
                    <p class="text-xs font-bold text-indigo-600 mt-1 uppercase">{{ selectedWallet?.merchant_name }}</p>
                </div>
                
                <form @submit.prevent="submitTopup" class="p-8 space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Advance Payment Amount ($)</label>
                        <div class="relative">
                            <DollarSign class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                            <input 
                                v-model="topupForm.amount"
                                type="number" 
                                step="0.01"
                                min="1"
                                required
                                class="w-full pl-10 pr-4 py-4 bg-gray-50 dark:bg-gray-950 border border-gray-100 dark:border-gray-800 rounded-2xl focus:ring-2 focus:ring-indigo-500 font-black text-lg dark:text-white"
                                placeholder="0.00"
                            />
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Audit Reference</label>
                        <textarea 
                            v-model="topupForm.description"
                            required
                            class="w-full px-4 py-4 bg-gray-50 dark:bg-gray-950 border border-gray-100 dark:border-gray-800 rounded-2xl focus:ring-2 focus:ring-indigo-500 text-sm dark:text-white min-h-[100px]"
                        ></textarea>
                    </div>

                    <div class="flex flex-col gap-3">
                        <button 
                            type="submit"
                            :disabled="topupForm.processing"
                            class="w-full py-4 bg-gray-900 dark:bg-white dark:text-gray-900 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-black transition-all shadow-xl"
                        >
                            {{ topupForm.processing ? 'PROCESSING...' : 'CONFIRM DEPOSIT' }}
                        </button>
                        <button type="button" @click="isTopupModalOpen = false" class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Credit Limit Modal -->
        <div v-if="isCreditModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
            <div class="bg-white dark:bg-gray-900 rounded-3xl w-full max-w-md border border-gray-200 dark:border-gray-800 shadow-2xl overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-800 bg-indigo-50 dark:bg-indigo-900/30">
                    <h4 class="text-xl font-black text-indigo-900 dark:text-indigo-200 uppercase tracking-tighter">Risk Threshold Control</h4>
                    <p class="text-xs font-bold text-indigo-600 mt-1 uppercase">{{ selectedWallet?.merchant_name }}</p>
                </div>
                
                <form @submit.prevent="submitCreditUpdate" class="p-8 space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Authorized Credit Line ($)</label>
                        <div class="relative">
                            <Zap class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-indigo-400" />
                            <input 
                                v-model="creditForm.credit_limit"
                                type="number" 
                                step="1"
                                min="0"
                                required
                                class="w-full pl-10 pr-4 py-4 bg-gray-50 dark:bg-gray-950 border border-gray-100 dark:border-gray-800 rounded-2xl focus:ring-2 focus:ring-indigo-500 font-black text-lg dark:text-white"
                                placeholder="0.00"
                            />
                        </div>
                        <p class="mt-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-relaxed">
                            This sets the maximum allowed debt the merchant can accrue before their service is auto-disabled.
                        </p>
                    </div>

                    <div class="flex flex-col gap-3">
                        <button 
                            type="submit"
                            :disabled="creditForm.processing"
                            class="w-full py-4 bg-indigo-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-200 dark:shadow-none"
                        >
                            {{ creditForm.processing ? 'UPDATING...' : 'UPDATE CREDIT LINE' }}
                        </button>
                        <button type="button" @click="isCreditModalOpen = false" class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { 
    Truck, 
    Eye, 
    Trash2, 
    Smartphone, 
    Signal, 
    ShieldCheck, 
    ShieldAlert,
    UserPlus,
    X,
    Building2,
    CheckCircle2,
    RotateCcw,
    Loader2
} from 'lucide-vue-next';
import { ref, computed } from 'vue';

const props = defineProps({
    agents: {
        type: Array,
        default: () => [],
    },
    merchants: {
        type: Array,
        default: () => [],
    },
});

const activeTab = ref('all'); // 'all', 'pending'
const isAssociateModalOpen = ref(false);
const selectedAgent = ref(null);
const loadingId = ref(null);
const removingAssociation = ref(null); // Track which association is being removed

const filteredAgents = computed(() => {
    if (activeTab.value === 'pending') {
        return props.agents.filter(a => !a.is_active);
    }
    return props.agents;
});

const getStatusColor = (status) => {
    switch (status) {
        case 'online': return 'text-green-600 bg-green-50 dark:bg-green-900/20';
        case 'away': return 'text-amber-600 bg-amber-50 dark:bg-amber-900/20';
        case 'offline': return 'text-gray-600 bg-gray-50 dark:bg-gray-800/50';
        default: return 'text-gray-600 bg-gray-50';
    }
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
};

const approveForm = useForm({});
const associateForm = useForm({
    tenant_id: '',
    freelancer_id: '',
});

const approveAgent = (id) => {
    if (confirm('Approve this agent for platform operations?')) {
        approveForm.patch(route('agents.approve', id));
    }
};

const openAssociateModal = (agent) => {
    selectedAgent.value = agent;
    associateForm.freelancer_id = agent.user_id;
    isAssociateModalOpen.value = true;
};

const submitAssociation = () => {
    associateForm.post(route('agents.associate'), {
        onSuccess: () => {
            isAssociateModalOpen.value = false;
            associateForm.reset();
        },
    });
};

const toggleStatus = (agent) => {
    const action = agent.is_active ? 'deactivate' : 'activate';
    if (window.confirm(`Are you sure you want to ${action} this agent?`)) {
        loadingId.value = agent.id;
        const form = useForm({});
        form.delete(route('agents.destroy', agent.id), {
            onFinish: () => loadingId.value = null
        });
    }
};

const removeAssociation = (agent, association) => {
    if (window.confirm(`Remove association with ${association.tenant?.name}?`)) {
        removingAssociation.value = association.id;
        const form = useForm({
            tenant_id: association.tenant_id,
            freelancer_id: agent.user_id
        });
        form.post(route('agents.disassociate'), {
            onFinish: () => removingAssociation.value = null,
            preserveScroll: true
        });
    }
};
</script>

<template>
    <Head title="Fleet Directory" />

    <AuthenticatedLayout>
        <template #header>
            Agent Governance
        </template>

        <div class="space-y-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">Freelance Fleet</h3>
                    <p class="text-sm text-gray-500 font-medium">Independent onboarding, approvals, and multi-consignee associations.</p>
                </div>
                
                <div class="flex bg-gray-100 dark:bg-gray-800 p-1 rounded-xl">
                    <button 
                        @click="activeTab = 'all'"
                        :class="['px-4 py-2 text-[10px] font-black uppercase tracking-widest rounded-lg transition-all', activeTab === 'all' ? 'bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm' : 'text-gray-500 hover:text-gray-700']"
                    >
                        Active Roster
                    </button>
                    <button 
                        @click="activeTab = 'pending'"
                        :class="['px-4 py-2 text-[10px] font-black uppercase tracking-widest rounded-lg transition-all flex items-center gap-2', activeTab === 'pending' ? 'bg-white dark:bg-gray-700 text-indigo-600 shadow-sm' : 'text-gray-500 hover:text-gray-700']"
                    >
                        Pending Approvals
                        <span v-if="agents.filter(a => !a.is_active).length > 0" class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                    </button>
                </div>
            </div>

            <!-- Agents Table -->
            <div class="bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-800">
                            <tr>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Agent Identity</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Associated Merchants</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Governance Status</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Live State</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            <tr v-for="agent in filteredAgents" :key="agent.id" class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-indigo-600 flex items-center justify-center text-white text-xs font-black shadow-lg shadow-indigo-100 dark:shadow-none">
                                            {{ agent.user_id }}
                                        </div>
                                        <div>
                                            <div class="font-black text-gray-900 dark:text-white uppercase tracking-tighter">Freelancer #{{ agent.id.substring(0,6) }}</div>
                                            <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ formatDate(agent.created_at) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div v-if="agent.associations && agent.associations.length > 0" class="flex flex-col gap-1">
                                        <div v-for="assoc in agent.associations" :key="assoc.id" class="inline-flex items-center gap-1.5 px-2 py-1 bg-indigo-50 text-indigo-700 dark:bg-indigo-900/20 dark:text-indigo-400 rounded-lg text-[10px] font-bold group/assoc">
                                            <Building2 class="w-3 h-3" />
                                            {{ assoc.tenant?.name || 'Unknown' }}
                                            <button 
                                                v-if="removingAssociation !== assoc.id"
                                                @click.stop="removeAssociation(agent, assoc)"
                                                class="ml-1 p-0.5 rounded hover:bg-red-100 dark:hover:bg-red-900/20 text-indigo-400 hover:text-red-600 opacity-0 group-hover/assoc:opacity-100 transition-opacity"
                                                title="Remove association"
                                            >
                                                <X class="w-3 h-3" />
                                            </button>
                                            <Loader2 v-else class="ml-1 w-3 h-3 animate-spin text-indigo-400" />
                                        </div>
                                    </div>
                                    <span v-else class="text-xs text-gray-400">No associations</span>
                                </td>
                                <td class="px-6 py-4 text-[10px] font-black uppercase tracking-widest">
                                    <div v-if="agent.is_active" class="flex items-center gap-2 text-green-600">
                                        <ShieldCheck class="w-4 h-4" />
                                        Verified Professional
                                    </div>
                                    <div v-else class="flex items-center gap-2 text-amber-500 animate-pulse">
                                        <ShieldAlert class="w-4 h-4" />
                                        Awaiting Approval
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span :class="['inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-[10px] font-black uppercase tracking-wider', getStatusColor(agent.current_status)]">
                                        <Signal class="w-3 h-3" />
                                        {{ agent.current_status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button 
                                            v-if="!agent.is_active"
                                            @click="approveAgent(agent.id)"
                                            class="p-2 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition-all shadow-sm"
                                            title="Approve Onboarding"
                                        >
                                            <CheckCircle2 class="w-4 h-4" />
                                        </button>

                                        <button 
                                            @click="openAssociateModal(agent)"
                                            class="p-2 text-gray-400 hover:text-indigo-600 transition-colors"
                                            title="Manage Associations"
                                        >
                                            <UserPlus class="w-4 h-4" />
                                        </button>

                                        <Link
                                            :href="route('agents.show', agent.id)"
                                            class="p-2 text-gray-400 hover:text-gray-900 transition-colors"
                                            title="Full Dossier"
                                        >
                                            <Eye class="w-4 h-4" />
                                        </Link>

                                        <button
                                            @click="toggleStatus(agent)"
                                            :disabled="loadingId === agent.id"
                                            :title="agent.is_active ? 'Deactivate' : 'Activate'"
                                            :class="['p-2 rounded-lg transition-colors', agent.is_active ? 'text-gray-400 hover:text-red-600 hover:bg-red-50' : 'text-indigo-600 hover:bg-indigo-50', loadingId === agent.id && 'opacity-50 cursor-not-allowed']"
                                        >
                                            <Loader2 v-if="loadingId === agent.id" class="w-4 h-4 animate-spin" />
                                            <template v-else>
                                                <RotateCcw v-if="!agent.is_active" class="w-4 h-4" />
                                                <Trash2 v-else class="w-4 h-4" />
                                            </template>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div v-if="filteredAgents.length === 0" class="py-20 text-center text-gray-400 border-t border-gray-100 dark:border-gray-800">
                     <Truck class="w-12 h-12 mx-auto mb-4 opacity-10" />
                     <p class="font-bold uppercase tracking-widest text-[10px]">No agents match this criteria.</p>
                </div>
            </div>
        </div>

        <!-- Association Modal -->
        <div v-if="isAssociateModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
            <div class="bg-white dark:bg-gray-900 rounded-3xl w-full max-w-lg border border-gray-200 dark:border-gray-800 shadow-2xl overflow-hidden">
                <div class="p-8 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/50 flex justify-between items-center">
                    <div>
                        <h4 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">Multi-Store Association</h4>
                        <p class="text-xs font-bold text-gray-500 mt-1 uppercase">Link Agent #{{ selectedAgent?.user_id }} to business entities</p>
                    </div>
                    <button @click="isAssociateModalOpen = false" class="p-2 text-gray-400 hover:text-gray-600">
                        <X class="w-6 h-6" />
                    </button>
                </div>
                
                <form @submit.prevent="submitAssociation" class="p-8 space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Select Target Merchant / Store</label>
                        <div class="relative group">
                            <Building2 class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-300 group-hover:text-indigo-500 transition-colors" />
                            <select 
                                v-model="associateForm.tenant_id"
                                required
                                class="w-full pl-12 pr-4 py-4 bg-gray-50 dark:bg-gray-950 border border-gray-100 dark:border-gray-800 rounded-2xl focus:ring-2 focus:ring-indigo-500 appearance-none font-bold text-sm dark:text-white"
                            >
                                <option value="" disabled>Choose an entity...</option>
                                <option v-for="merchant in merchants" :key="merchant.id" :value="merchant.id">
                                    {{ merchant.name }} ({{ merchant.type }})
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="bg-indigo-50 dark:bg-indigo-900/10 p-6 rounded-2xl flex gap-4">
                        <ShieldCheck class="w-8 h-8 text-indigo-600 shrink-0" />
                        <div>
                            <p class="text-sm font-black text-indigo-900 dark:text-indigo-300 uppercase leading-none mb-2">Association Governance</p>
                            <p class="text-[10px] text-indigo-700/70 dark:text-indigo-400/70 font-bold uppercase leading-relaxed">
                                Linking this agent allows them to receive priority dispatches from the selected store and access their warehouse premises.
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 pt-4">
                        <button 
                            type="submit"
                            :disabled="associateForm.processing"
                            :class="['w-full py-4 rounded-2xl text-xs font-black uppercase tracking-widest transition-all', associateForm.processing ? 'bg-gray-400 cursor-not-allowed' : 'bg-gray-900 dark:bg-white dark:text-gray-900 text-white hover:bg-black']"
                        >
                            {{ associateForm.processing ? 'ESTABLISHING LINK...' : 'AUTHORIZE ASSOCIATION' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Globe, Plus, Trash2, Pencil, RotateCcw, Loader2, Map, ShieldCheck, ShieldAlert } from 'lucide-vue-next';
import { ref, computed } from 'vue';

const props = defineProps({
    countries: Array,
    states: Array,
});

const activeTab = ref('countries');
const isModalOpen = ref(false);
const editingItem = ref(null);
const loadingId = ref(null);

const countryForm = useForm({
    name: '',
    code: '',
});

const stateForm = useForm({
    country_id: '',
    name: '',
});

const openCreateModal = () => {
    editingItem.value = null;
    countryForm.reset();
    stateForm.reset();
    isModalOpen.value = true;
};

const editItem = (item) => {
    editingItem.value = item;
    if (activeTab.value === 'countries') {
        countryForm.name = item.name;
        countryForm.code = item.code;
    } else {
        stateForm.country_id = item.country_id;
        stateForm.name = item.name;
    }
    isModalOpen.value = true;
};

const submit = () => {
    if (activeTab.value === 'countries') {
        if (editingItem.value) {
            countryForm.put(route('geo.countries.update', editingItem.value.id), {
                onSuccess: () => closeModal(),
            });
        } else {
            countryForm.post(route('geo.countries.store'), {
                onSuccess: () => closeModal(),
            });
        }
    } else {
        if (editingItem.value) {
            stateForm.put(route('geo.states.update', editingItem.value.id), {
                onSuccess: () => closeModal(),
            });
        } else {
            stateForm.post(route('geo.states.store'), {
                onSuccess: () => closeModal(),
            });
        }
    }
};

const toggleStatus = (item) => {
    loadingId.value = item.id;
    const url = activeTab.value === 'countries' 
        ? route('geo.countries.destroy', item.id)
        : route('geo.states.destroy', item.id);
        
    countryForm.patch(url, {
        onFinish: () => loadingId.value = null
    });
};

const closeModal = () => {
    isModalOpen.value = false;
    editingItem.value = null;
    countryForm.reset();
    stateForm.reset();
};
</script>

<template>
    <Head title="System Masters" />

    <AuthenticatedLayout>
        <template #header>
            System Settings
        </template>

        <div class="space-y-6">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 uppercase tracking-tight">System Masters</h3>
                    <p class="text-sm text-gray-500">Manage global administrative boundaries and regional hierarchies.</p>
                </div>
                <button 
                    @click="openCreateModal"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700 transition-all shadow-sm"
                >
                    <Plus class="w-4 h-4 mr-2" />
                    ADD {{ activeTab === 'countries' ? 'COUNTRY' : 'STATE' }}
                </button>
            </div>

            <!-- Tabs -->
            <div class="flex gap-2 p-1 bg-gray-100 dark:bg-gray-800 rounded-xl w-fit">
                <button 
                    @click="activeTab = 'countries'"
                    :class="['px-6 py-2 rounded-lg text-xs font-black uppercase tracking-widest transition-all', activeTab === 'countries' ? 'bg-white dark:bg-gray-700 text-indigo-600 shadow-sm' : 'text-gray-500 hover:text-gray-700']"
                >
                    Countries
                </button>
                <button 
                    @click="activeTab = 'states'"
                    :class="['px-6 py-2 rounded-lg text-xs font-black uppercase tracking-widest transition-all', activeTab === 'states' ? 'bg-white dark:bg-gray-700 text-indigo-600 shadow-sm' : 'text-gray-500 hover:text-gray-700']"
                >
                    States / Provinces
                </button>
            </div>

            <!-- Table -->
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-800">
                        <tr v-if="activeTab === 'countries'">
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Country Name</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">ISO Code</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                        <tr v-else>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">State Name</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Parent Country</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        <template v-if="activeTab === 'countries'">
                            <tr v-for="country in countries" :key="country.id" class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                                <td class="px-6 py-4 font-bold text-gray-900 dark:text-white uppercase tracking-tight">{{ country.name }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 bg-gray-100 dark:bg-gray-800 rounded text-[10px] font-black text-gray-500 uppercase tracking-widest border border-gray-200 dark:border-gray-700">{{ country.code }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div v-if="country.is_active" class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 dark:bg-emerald-900/20">
                                        <ShieldCheck class="w-3 h-3 mr-1" /> Active
                                    </div>
                                    <div v-else class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-gray-100 text-gray-400">
                                        <ShieldAlert class="w-3 h-3 mr-1" /> Disabled
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right flex items-center justify-end gap-2">
                                    <button @click="editItem(country)" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                                        <Pencil class="w-4 h-4" />
                                    </button>
                                    <button @click="toggleStatus(country)" :disabled="loadingId === country.id" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <Loader2 v-if="loadingId === country.id" class="w-4 h-4 animate-spin" />
                                        <template v-else>
                                            <RotateCcw v-if="!country.is_active" class="w-4 h-4" />
                                            <Trash2 v-else class="w-4 h-4" />
                                        </template>
                                    </button>
                                </td>
                            </tr>
                        </template>
                        <template v-else>
                            <tr v-for="state in states" :key="state.id" class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                                <td class="px-6 py-4 font-bold text-gray-900 dark:text-white uppercase tracking-tight">{{ state.name }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <Globe class="w-3.5 h-3.5 text-gray-400" />
                                        <span class="text-xs text-gray-600 dark:text-gray-400">{{ state.country?.name || 'Unknown' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div v-if="state.is_active" class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 dark:bg-emerald-900/20">
                                        <ShieldCheck class="w-3 h-3 mr-1" /> Active
                                    </div>
                                    <div v-else class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-gray-100 text-gray-400">
                                        <ShieldAlert class="w-3 h-3 mr-1" /> Disabled
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right flex items-center justify-end gap-2">
                                    <button @click="editItem(state)" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                                        <Pencil class="w-4 h-4" />
                                    </button>
                                    <button @click="toggleStatus(state)" :disabled="loadingId === state.id" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <Loader2 v-if="loadingId === state.id" class="w-4 h-4 animate-spin" />
                                        <template v-else>
                                            <RotateCcw v-if="!state.is_active" class="w-4 h-4" />
                                            <Trash2 v-else class="w-4 h-4" />
                                        </template>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal -->
        <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
            <div class="bg-white dark:bg-gray-900 rounded-2xl w-full max-w-md border border-gray-200 dark:border-gray-800 shadow-2xl overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-800">
                    <h4 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">
                        {{ editingItem ? 'Modify' : 'Add New' }} {{ activeTab === 'countries' ? 'Country' : 'State' }}
                    </h4>
                </div>

                <form @submit.prevent="submit" class="p-6 space-y-4">
                    <template v-if="activeTab === 'countries'">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Country Name</label>
                            <input v-model="countryForm.name" type="text" required class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:text-white" />
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">ISO Code (2-3 chars)</label>
                            <input v-model="countryForm.code" type="text" required maxlength="3" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:text-white" />
                        </div>
                    </template>
                    <template v-else>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Parent Country</label>
                            <select v-model="stateForm.country_id" required class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:text-white">
                                <option value="" disabled>Select Country</option>
                                <option v-for="c in countries" :key="c.id" :value="c.id">{{ c.name }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">State Name</label>
                            <input v-model="stateForm.name" type="text" required class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:text-white" />
                        </div>
                    </template>

                    <div class="flex gap-3 pt-4">
                        <button type="button" @click="closeModal" class="flex-1 px-4 py-2 text-sm font-bold text-gray-500 hover:text-gray-700 transition-colors uppercase tracking-widest">Cancel</button>
                        <button type="submit" :disabled="countryForm.processing || stateForm.processing" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200/50 uppercase tracking-widest">
                            {{ (countryForm.processing || stateForm.processing) ? 'Saving...' : 'Save Changes' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

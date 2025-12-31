<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Globe, Plus, Trash2, MapPin, Search, ShieldCheck, ShieldAlert, RotateCcw, Loader2, Pencil } from 'lucide-vue-next';
import { ref, computed } from 'vue';

const props = defineProps({
    cities: {
        type: Array,
        default: () => [],
    },
    countries: {
        type: Array,
        default: () => [],
    }
});

const searchQuery = ref('');
const isAddModalOpen = ref(false);
const editingCity = ref(null);
const loadingId = ref(null);

const form = useForm({
    name: '',
    state_id: '',
    state: '', // Keep for backward compat or if no state selected
    country_code: '',
});

const selectedCountryId = ref('');
const availableStates = computed(() => {
    if (!selectedCountryId.value) return [];
    const country = props.countries.find(c => c.id === selectedCountryId.value);
    return country ? country.states : [];
});

const filteredCities = computed(() => {
    return props.cities.filter(city => 
        city.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        city.state?.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const editCity = (city) => {
    editingCity.value = city;
    form.name = city.name;
    form.state_id = city.state_id;
    form.state = city.state;
    form.country_code = city.country_code;

    // Pre-select country based on state or city data if possible
    if (city.state?.country_id) {
        selectedCountryId.value = city.state.country_id;
    }

    isAddModalOpen.value = true;
};

const submit = () => {
    if (editingCity.value) {
        form.put(route('cities.update', editingCity.value.id), {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route('cities.store'), {
            onSuccess: () => closeModal(),
        });
    }
};

const closeModal = () => {
    isAddModalOpen.value = false;
    editingCity.value = null;
    form.reset();
};

const toggleStatus = (city) => {
    const action = city.is_active ? 'deactivate' : 'activate';
    if (confirm(`Are you sure you want to ${action} this city?`)) {
        loadingId.value = city.id;
        form.patch(route('cities.destroy', city.id), {
            onFinish: () => {
                loadingId.value = null;
            }
        });
    }
};
</script>

<template>
    <Head title="City Masters" />

    <AuthenticatedLayout>
        <template #header>
            Location Masters
        </template>

        <div class="space-y-6">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">City Directory</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Manage the global list of cities supported by the platform.</p>
                </div>
                <button 
                    @click="isAddModalOpen = true; editingCity = null; form.reset();"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700 transition-all shadow-sm shadow-indigo-200 dark:shadow-none"
                >
                    <Plus class="w-4 h-4 mr-2" />
                    ADD CITY
                </button>
            </div>

            <!-- Search & Filters -->
            <div class="bg-white dark:bg-gray-900 p-4 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm flex items-center gap-4">
                <div class="relative flex-1">
                    <Search class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" />
                    <input 
                        v-model="searchQuery"
                        type="text" 
                        placeholder="Search by city name or state..."
                        class="w-full pl-9 pr-4 py-2 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm dark:text-white"
                    />
                </div>
            </div>

            <!-- Cities Table -->
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 overflow-hidden shadow-sm">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-800">
                        <tr>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">City Name</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">State / Province</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Country</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        <tr v-for="city in filteredCities" :key="city.id" :class="['hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors', !city.is_active && 'opacity-60 grayscale-[0.5]']">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div :class="['w-8 h-8 rounded-lg flex items-center justify-center', city.is_active ? 'bg-indigo-50 dark:bg-indigo-900/20' : 'bg-gray-100 dark:bg-gray-800']">
                                        <MapPin :class="['w-4 h-4', city.is_active ? 'text-indigo-600' : 'text-gray-400']" />
                                    </div>
                                    <span class="font-bold text-gray-900 dark:text-white uppercase tracking-tight">{{ city.name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ city.state?.name || city.state || 'N/A' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-gray-100 dark:bg-gray-800 text-[10px] font-black rounded uppercase tracking-widest text-gray-500">
                                    {{ city.state?.country?.code || city.country_code }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div v-if="city.is_active" class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 dark:bg-emerald-900/20">
                                    <ShieldCheck class="w-3 h-3 mr-1" />
                                    Active
                                </div>
                                <div v-else class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-gray-100 text-gray-400 dark:bg-gray-800">
                                    <ShieldAlert class="w-3 h-3 mr-1" />
                                    Disabled
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right flex items-center justify-end gap-2">
                                <button 
                                    @click="editCity(city)"
                                    title="Edit City"
                                    class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                >
                                    <Pencil class="w-4 h-4" />
                                </button>
                                <button 
                                    @click="toggleStatus(city)"
                                    :title="city.is_active ? 'Deactivate' : 'Activate'"
                                    :disabled="loadingId === city.id"
                                    :class="['p-2 rounded-lg transition-colors', city.is_active ? 'text-gray-400 hover:text-red-600 hover:bg-red-50' : 'text-indigo-600 hover:bg-indigo-50', loadingId === city.id && 'opacity-50 cursor-not-allowed']"
                                >
                                    <Loader2 v-if="loadingId === city.id" class="w-4 h-4 animate-spin text-indigo-600" />
                                    <template v-else>
                                        <RotateCcw v-if="!city.is_active" class="w-4 h-4" />
                                        <Trash2 v-else class="w-4 h-4" />
                                    </template>
                                </button>
                            </td>
                        </tr>
                        <tr v-if="filteredCities.length === 0">
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                No cities found matching your search.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add City Modal -->
        <div v-if="isAddModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
            <div class="bg-white dark:bg-gray-900 rounded-2xl w-full max-w-md border border-gray-200 dark:border-gray-800 shadow-2xl overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-800">
                    <h4 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">
                        {{ editingCity ? 'Edit City' : 'Add New City' }}
                    </h4>
                </div>
                
                <form @submit.prevent="submit" class="p-6 space-y-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">City Name</label>
                        <input 
                            v-model="form.name"
                            type="text" 
                            required
                            class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:text-white"
                        />
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Country</label>
                            <select 
                                v-model="selectedCountryId"
                                required
                                class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:text-white"
                            >
                                <option value="" disabled>Select Country</option>
                                <option v-for="c in countries" :key="c.id" :value="c.id">{{ c.name }}</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">State / Province</label>
                            <select 
                                v-model="form.state_id"
                                required
                                :disabled="!selectedCountryId"
                                class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:text-white disabled:opacity-50"
                            >
                                <option value="" disabled>Select State</option>
                                <option v-for="s in availableStates" :key="s.id" :value="s.id">{{ s.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button 
                            type="button" 
                            @click="closeModal"
                            class="flex-1 px-4 py-2 text-sm font-bold text-gray-500 hover:text-gray-700 transition-colors"
                        >
                            CANCEL
                        </button>
                        <button 
                            type="submit"
                            :disabled="form.processing"
                            class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200/50"
                        >
                            {{ form.processing ? 'SAVING...' : 'SAVE CITY' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

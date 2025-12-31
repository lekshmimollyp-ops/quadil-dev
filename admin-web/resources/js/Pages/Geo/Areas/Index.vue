<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Focus, Plus, Trash2, MapPin, Navigation, Info, Shield, ShieldCheck, ShieldAlert, RotateCcw, Loader2, Search, Crosshair, Pencil } from 'lucide-vue-next';
import axios from 'axios';
import { ref, watch } from 'vue';

const props = defineProps({
    cities: {
        type: Array,
        default: () => [],
    },
    areas: {
        type: Array,
        default: () => [],
    },
});

const isAddModalOpen = ref(false);
const editingArea = ref(null);
const loadingId = ref(null);
const isSearching = ref(false);
const searchResults = ref([]);
const searchTimeout = ref(null);

const form = useForm({
    city_id: '',
    name: '',
    center_lat: '',
    center_lng: '',
    radius_km: 5,
});

const searchLocations = async (query) => {
    if (query.length < 3) {
        searchResults.value = [];
        return;
    }

    isSearching.value = true;
    try {
        const response = await axios.get(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&addressdetails=1&limit=5`, {
            headers: { 'User-Agent': 'QUDIL-Admin-Panel' }
        });
        searchResults.value = response.data;
    } catch (error) {
        console.error('Geocoding failed:', error);
    } finally {
        isSearching.value = false;
    }
};

const selectLocation = (result) => {
    form.name = result.display_name.split(',')[0];
    form.center_lat = parseFloat(result.lat);
    form.center_lng = parseFloat(result.lon);
    
    // Find matching city from our database
    const addr = result.address;
    const cityCandidates = [addr.city, addr.town, addr.village, addr.suburb, addr.county, addr.state_district];
    
    const matchedCity = props.cities.find(c => 
        cityCandidates.some(candidate => 
            candidate?.toLowerCase().trim() === c.name.toLowerCase().trim()
        )
    );

    if (matchedCity) {
        form.city_id = matchedCity.id;
    } else {
        // Fallback or warning - for now we'll just set it to the first city if none match 
        // to avoid validation errors, or leave it empty if we want to force a choice.
        // Given "No need of dropdown", we'll try to find the closest match or at least alert.
        console.warn('Could not automatically match city for:', result.display_name);
    }
    
    searchResults.value = [];
};

watch(() => form.name, (newVal) => {
    if (searchTimeout.value) clearTimeout(searchTimeout.value);
    searchTimeout.value = setTimeout(() => {
        if (!form.center_lat) searchLocations(newVal);
    }, 500);
});

const editArea = (area) => {
    editingArea.value = area;
    form.city_id = area.city_id;
    form.name = area.name;
    form.center_lat = area.center_lat;
    form.center_lng = area.center_lng;
    form.radius_km = area.radius_km;
    isAddModalOpen.value = true;
};

const submit = () => {
    if (editingArea.value) {
        form.put(route('areas.update', editingArea.value.id), {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route('areas.store'), {
            onSuccess: () => closeModal(),
        });
    }
};

const closeModal = () => {
    isAddModalOpen.value = false;
    editingArea.value = null;
    form.reset();
};

const toggleStatus = (area) => {
    const action = area.is_active ? 'deactivate' : 'activate';
    if (confirm(`Are you sure you want to ${action} this service area?`)) {
        loadingId.value = area.id;
        form.patch(route('areas.destroy', area.id), {
            onFinish: () => {
                loadingId.value = null;
            }
        });
    }
};

const getCityName = (cityId) => {
    return props.cities.find(c => c.id === cityId)?.name || 'Unknown';
};
</script>

<template>
    <Head title="Service Areas" />

    <AuthenticatedLayout>
        <template #header>
            Operational Boundaries
        </template>

        <div class="space-y-6 text-gray-900 dark:text-gray-100">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-medium">Service Coverage</h3>
                    <p class="text-sm text-gray-500">Define operational zones based on a central point and KM radius.</p>
                </div>
                <button 
                    @click="isAddModalOpen = true; editingArea = null; form.reset();"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700 transition-all"
                >
                    <Plus class="w-4 h-4 mr-2" />
                    NEW AREA
                </button>
            </div>

            <!-- Dashboard Style Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm flex items-center gap-4">
                    <div class="p-3 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl">
                        <Focus class="w-6 h-6 text-indigo-600" />
                    </div>
                    <div>
                        <div class="text-[10px] font-black uppercase text-gray-400 tracking-widest leading-none mb-1">Total Zones</div>
                        <div class="text-2xl font-black">{{ areas.length }}</div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm flex items-center gap-4">
                    <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-xl">
                        <Shield class="w-6 h-6 text-green-600" />
                    </div>
                    <div>
                        <div class="text-[10px] font-black uppercase text-gray-400 tracking-widest leading-none mb-1">Active Status</div>
                        <div class="text-2xl font-black text-green-600">{{ areas.filter(a => a.is_active).length }} Online</div>
                    </div>
                </div>
                <div class="bg-indigo-600 p-6 rounded-2xl text-white shadow-lg shadow-indigo-100 dark:shadow-none relative overflow-hidden">
                    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
                    <div class="relative z-10 flex items-center gap-4">
                         <div class="p-3 bg-white/10 rounded-xl">
                            <Navigation class="w-6 h-6 text-white" />
                        </div>
                        <div>
                            <div class="text-[10px] font-black uppercase text-indigo-200 tracking-widest leading-none mb-1">Coverage Model</div>
                            <div class="text-2xl font-black">Radius Based</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service Areas List -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="area in areas" :key="area.id" class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden group hover:border-indigo-500 transition-all">
                    <div class="p-6 border-b border-gray-50 dark:border-gray-800">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center gap-2">
                                <div class="px-2.5 py-1 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 rounded-lg text-[10px] font-black uppercase tracking-widest">
                                    {{ getCityName(area.city_id) }}
                                </div>
                                <div v-if="!area.is_active" class="px-2.5 py-1 bg-gray-100 dark:bg-gray-800 text-gray-400 rounded-lg text-[10px] font-black uppercase tracking-widest">
                                    Disabled
                                </div>
                            </div>
                            <div class="flex items-center gap-1">
                                <button 
                                    @click="editArea(area)"
                                    title="Edit Area"
                                    class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-white rounded-lg transition-colors shadow-none hover:shadow-sm"
                                >
                                    <Pencil class="w-4 h-4" />
                                </button>
                                <button 
                                    @click="toggleStatus(area)" 
                                    :disabled="loadingId === area.id"
                                    :class="['p-1.5 rounded-lg transition-colors shadow-none hover:shadow-sm', area.is_active ? 'text-gray-400 hover:text-red-500 hover:bg-white' : 'text-indigo-600 hover:text-indigo-700 hover:bg-white', loadingId === area.id && 'opacity-50 cursor-not-allowed']"
                                >
                                    <Loader2 v-if="loadingId === area.id" class="w-4 h-4 animate-spin" />
                                    <template v-else>
                                        <RotateCcw v-if="!area.is_active" class="w-4 h-4" />
                                        <Trash2 v-else class="w-4 h-4" />
                                    </template>
                                </button>
                            </div>
                        </div>
                        <h4 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tighter">{{ area.name }}</h4>
                        <div class="mt-2 flex items-center gap-2 text-xs text-gray-500">
                            <MapPin class="w-3.5 h-3.5" />
                            {{ area.center_lat }}, {{ area.center_lng }}
                        </div>
                    </div>
                    <div :class="['bg-gray-50/50 dark:bg-gray-800/20 p-6 flex justify-between items-center', !area.is_active && 'opacity-50']">
                        <div class="flex items-center gap-3">
                            <div :class="['w-1.5 h-1.5 rounded-full', area.is_active ? 'bg-green-500' : 'bg-gray-400']"></div>
                            <span class="text-xs font-bold uppercase tracking-widest text-gray-400">{{ area.is_active ? 'Sphere of Influence' : 'Zone Offline' }}</span>
                        </div>
                        <div :class="['text-xl font-black', area.is_active ? 'text-indigo-600' : 'text-gray-400']">{{ area.radius_km }}<span class="text-[10px] text-gray-400 ml-1 uppercase">km</span></div>
                    </div>
                </div>

                <button 
                    @click="isAddModalOpen = true; editingArea = null; form.reset();"
                    class="border-2 border-dashed border-gray-200 dark:border-gray-800 rounded-2xl p-12 flex flex-col items-center justify-center text-gray-400 hover:border-indigo-500 hover:text-indigo-500 transition-all bg-gray-50/10"
                >
                    <Plus class="w-10 h-10 mb-2" />
                    <span class="text-sm font-bold uppercase tracking-widest">Add New Zone</span>
                </button>
            </div>
        </div>

        <!-- Add Area Modal -->
        <div v-if="isAddModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
            <div class="bg-white dark:bg-gray-900 rounded-2xl w-full max-w-lg border border-gray-200 dark:border-gray-800 shadow-2xl overflow-hidden">
                 <div class="p-6 border-b border-gray-100 dark:border-gray-800">
                    <h4 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">
                        {{ editingArea ? 'Modify Service Zone' : 'Define Service Zone' }}
                    </h4>
                </div>
                
                <form @submit.prevent="submit" class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-1">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Parent City</label>
                            <select v-model="form.city_id" required class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:text-white">
                                <option value="" disabled>Select City</option>
                                <option v-for="city in cities" :key="city.id" :value="city.id">{{ city.name }}</option>
                            </select>
                        </div>
                        <div class="col-span-1 relative">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Zone Label (Searchable)</label>
                            <div class="relative">
                                <input 
                                    v-model="form.name" 
                                    type="text" 
                                    required 
                                    placeholder="e.g. Dubai Marina" 
                                    class="w-full pl-10 pr-4 py-2 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:text-white" 
                                />
                                <Search class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" />
                                <div v-if="isSearching" class="absolute right-3 top-2.5">
                                    <Loader2 class="w-4 h-4 animate-spin text-indigo-500" />
                                </div>
                            </div>

                            <!-- Search Results Dropdown -->
                            <div v-if="searchResults.length > 0" class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl shadow-2xl max-h-48 overflow-y-auto">
                                <button 
                                    v-for="res in searchResults" 
                                    :key="res.place_id"
                                    type="button"
                                    @click="selectLocation(res)"
                                    class="w-full text-left px-4 py-2 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 flex flex-col transition-colors border-b border-gray-50 dark:border-gray-800 last:border-0"
                                >
                                    <span class="text-xs font-bold">{{ res.display_name.split(',')[0] }}</span>
                                    <span class="text-[10px] text-gray-500 truncate">{{ res.display_name }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Center Latitude</label>
                            <input v-model="form.center_lat" type="number" step="0.000001" required placeholder="Lat" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:text-white" />
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Center Longitude</label>
                            <input v-model="form.center_lng" type="number" step="0.000001" required placeholder="Lng" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:text-white" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Coverage Radius (KM): {{ form.radius_km }}km</label>
                        <input v-model="form.radius_km" type="range" min="1" max="100" class="w-full accent-indigo-600" />
                        <div class="flex justify-between text-[10px] text-gray-500 mt-1">
                            <span>1KM</span>
                            <span>50KM</span>
                            <span>100KM</span>
                        </div>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="button" @click="closeModal" class="flex-1 px-4 py-2 text-sm font-bold text-gray-500 hover:text-gray-700 transition-colors">CANCEL</button>
                        <button type="submit" :disabled="form.processing" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200/50">
                            {{ form.processing ? 'SAVING...' : 'PUBLISH ZONE' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, User, Truck, ShieldCheck, MapPin, Smartphone, Signal, Calendar } from 'lucide-vue-next';

const props = defineProps({
    agent: {
        type: Object,
        required: true,
    },
});

const statusForm = useForm({
    status: props.agent.current_status,
});

const updateStatus = (newStatus) => {
    statusForm.status = newStatus;
    statusForm.patch(route('agents.update-status', props.agent.id), {
        preserveScroll: true,
    });
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};
</script>

<template>
    <Head :title="'Agent Profile - #' + agent.user_id" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-2">
                <Link :href="route('agents.index')" class="p-1 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                    <ArrowLeft class="w-5 h-5 text-gray-500" />
                </Link>
                <span>Agent Profile</span>
            </div>
        </template>

        <div class="max-w-5xl mx-auto space-y-6">
            <!-- Profile Header -->
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-8 shadow-sm">
                <div class="flex flex-col md:flex-row gap-8 items-start md:items-center">
                    <div class="w-24 h-24 rounded-2xl bg-indigo-600 flex items-center justify-center text-white text-3xl font-bold shadow-lg shadow-indigo-200 dark:shadow-none">
                        {{ agent.user_id }}
                    </div>
                    
                    <div class="flex-1 space-y-2">
                        <div class="flex items-center gap-3">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Delivery Agent #{{ agent.user_id }}</h2>
                            <span :class="['px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider', 
                                agent.current_status === 'online' ? 'text-green-600 bg-green-50' : 
                                agent.current_status === 'away' ? 'text-amber-600 bg-amber-50' : 'text-gray-600 bg-gray-50']">
                                {{ agent.current_status }}
                            </span>
                        </div>
                        <div class="flex flex-wrap gap-4 text-sm text-gray-500 dark:text-gray-400">
                            <div class="flex items-center gap-1.5 font-mono">
                                <ShieldCheck class="w-4 h-4" />
                                {{ agent.id }}
                            </div>
                            <div class="flex items-center gap-1.5">
                                <Calendar class="w-4 h-4" />
                                Joined on {{ formatDate(agent.created_at) }}
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-2 p-1 bg-gray-100 dark:bg-gray-800 rounded-xl">
                        <button 
                            @click="updateStatus('online')"
                            :disabled="statusForm.processing"
                            :class="['px-4 py-2 text-xs font-bold rounded-lg transition-all', agent.current_status === 'online' ? 'bg-white dark:bg-gray-700 text-green-600 shadow-sm' : 'text-gray-500 hover:text-gray-700']"
                        >
                            ONLINE
                        </button>
                        <button 
                            @click="updateStatus('away')"
                            :disabled="statusForm.processing"
                            :class="['px-4 py-2 text-xs font-bold rounded-lg transition-all', agent.current_status === 'away' ? 'bg-white dark:bg-gray-700 text-amber-600 shadow-sm' : 'text-gray-500 hover:text-gray-700']"
                        >
                            AWAY
                        </button>
                        <button 
                            @click="updateStatus('offline')"
                            :disabled="statusForm.processing"
                            :class="['px-4 py-2 text-xs font-bold rounded-lg transition-all', agent.current_status === 'offline' ? 'bg-white dark:bg-gray-700 text-gray-600 shadow-sm' : 'text-gray-500 hover:text-gray-700']"
                        >
                            OFFLINE
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Vehicle Info -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
                            <h3 class="font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <Truck class="w-5 h-5 text-indigo-500" />
                                Registered Vehicles
                            </h3>
                            <span class="px-2 py-1 text-xs font-bold bg-indigo-50 text-indigo-600 rounded-md">
                                {{ agent.vehicles.length }} TOTAL
                            </span>
                        </div>
                        
                        <div class="divide-y divide-gray-100 dark:divide-gray-800">
                            <div v-for="vehicle in agent.vehicles" :key="vehicle.id" class="p-6 flex items-center justify-between hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                        <Truck class="w-6 h-6 text-gray-600 dark:text-gray-400" />
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900 dark:text-white">{{ vehicle.vehicle_type }}</div>
                                        <div class="text-sm font-mono text-gray-500">{{ vehicle.plate_number }}</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span v-if="vehicle.is_verified" class="px-3 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-700 uppercase tracking-wider">
                                        Verified
                                    </span>
                                    <span v-else class="px-3 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700 uppercase tracking-wider">
                                        Pending
                                    </span>
                                </div>
                            </div>
                            
                            <div v-if="agent.vehicles.length === 0" class="p-12 text-center text-gray-500">
                                No vehicles registered.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics / Quick Info -->
                <div class="space-y-6">
                    <div class="bg-indigo-600 rounded-2xl p-6 text-white shadow-lg shadow-indigo-200 dark:shadow-none">
                        <h4 class="font-bold mb-4 opacity-80 text-sm uppercase tracking-wider">Performance Stats</h4>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-indigo-100">Deliveries</span>
                                <span class="font-bold">0</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-indigo-100">Rating</span>
                                <span class="font-bold text-amber-300">N/A</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-indigo-100">Reliability</span>
                                <span class="font-bold">0%</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm">
                        <h4 class="font-bold text-gray-900 dark:text-white mb-4 text-sm uppercase tracking-wider">System Info</h4>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between text-gray-500">
                                <span>Service Area</span>
                                <span class="text-gray-900 dark:text-gray-300">Unassigned</span>
                            </div>
                            <div class="flex justify-between text-gray-500">
                                <span>Last Ping</span>
                                <span class="text-gray-900 dark:text-gray-300">N/A</span>
                            </div>
                            <div class="flex justify-between text-gray-500">
                                <span>Version</span>
                                <span class="text-gray-900 dark:text-gray-300">v1.2.0-ext</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

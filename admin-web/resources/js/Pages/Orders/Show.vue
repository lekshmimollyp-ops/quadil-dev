<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { 
    ArrowLeft, 
    Package, 
    MapPin, 
    User, 
    Truck, 
    Calendar, 
    DollarSign, 
    ChevronRight,
    Search,
    CheckCircle2,
    AlertCircle
} from 'lucide-vue-next';

const props = defineProps({
    order: {
        type: Object,
        required: true,
    },
    availableDrivers: {
        type: Array,
        default: () => [],
    },
});

const form = useForm({
    driver_id: '',
});

const assignDriver = (driverId) => {
    if (confirm('Are you sure you want to assign this driver?')) {
        form.driver_id = driverId;
        form.post(route('orders.assign', props.order.id));
    }
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};
</script>

<template>
    <Head :title="'Order Details #' + order.id.substring(0, 8)" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-2">
                <Link :href="route('orders.index')" class="p-1 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                    <ArrowLeft class="w-5 h-5 text-gray-500" />
                </Link>
                <span>Order #{{ order.id.substring(0, 8) }}</span>
            </div>
        </template>

        <div class="max-w-6xl mx-auto space-y-6">
            <!-- Order Header Card -->
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-8 shadow-sm">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-2xl bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center">
                            <Package class="w-8 h-8 text-indigo-600" />
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white uppercase tracking-tight">Booking #{{ order.id.substring(0, 8) }}</h2>
                            <div class="flex items-center gap-3 text-sm text-gray-500 mt-1">
                                <span class="flex items-center gap-1.5 font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full uppercase text-[10px] tracking-widest">
                                    {{ order.status }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <Calendar class="w-4 h-4" />
                                    {{ formatDate(order.created_at) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-8 text-right">
                        <div>
                            <div class="text-xs text-gray-400 uppercase font-bold tracking-wider mb-1">Customer ID</div>
                            <div class="font-bold text-gray-900 dark:text-white">User #{{ order.user_id }}</div>
                        </div>
                        <div class="px-6 py-3 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-gray-100 dark:border-gray-800">
                            <div class="text-xs text-gray-400 uppercase font-bold tracking-wider mb-1">Total Bill</div>
                            <div class="text-2xl font-black text-indigo-600">${{ parseFloat(order.total_amount).toFixed(2) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Trip & Parcel Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Route Information -->
                    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-gray-100 dark:border-gray-800">
                            <h3 class="font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <MapPin class="w-5 h-5 text-red-500" />
                                Route Information
                            </h3>
                        </div>
                        <div class="p-8 relative">
                            <!-- Dash Line -->
                            <div class="absolute left-[47px] top-[100px] bottom-[100px] w-0.5 border-l-2 border-dashed border-gray-200 dark:border-gray-800"></div>
                            
                            <div class="space-y-12">
                                <!-- Pickup -->
                                <div class="flex items-start gap-6 relative z-10">
                                    <div class="w-10 h-10 rounded-full bg-red-50 dark:bg-red-900/20 border-2 border-red-500 flex items-center justify-center shrink-0">
                                        <div class="w-2.5 h-2.5 bg-red-500 rounded-full"></div>
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-xs font-black text-red-500 uppercase tracking-widest mb-1">Pickup Location</div>
                                        <div class="text-lg font-bold text-gray-900 dark:text-white">{{ order.pickup_details.address }}</div>
                                        <div class="mt-2 text-sm text-gray-500 bg-gray-50 dark:bg-gray-800/50 p-3 rounded-xl inline-block">
                                            Coordinates: {{ order.pickup_details.lat }}, {{ order.pickup_details.lng }}
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Delivery -->
                                <div class="flex items-start gap-6 relative z-10">
                                    <div class="w-10 h-10 rounded-full bg-green-50 dark:bg-green-900/20 border-2 border-green-500 flex items-center justify-center shrink-0">
                                        <CheckCircle2 class="w-5 h-5 text-green-600" />
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-xs font-black text-green-500 uppercase tracking-widest mb-1">Delivery Destination</div>
                                        <div class="text-lg font-bold text-gray-900 dark:text-white">{{ order.delivery_details.address }}</div>
                                        <div class="mt-2 text-sm text-gray-500 bg-gray-50 dark:bg-gray-800/50 p-3 rounded-xl inline-block">
                                            Coordinates: {{ order.delivery_details.lat }}, {{ order.delivery_details.lng }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Parcel Metadata -->
                    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-gray-100 dark:border-gray-800">
                            <h3 class="font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <Package class="w-5 h-5 text-indigo-500" />
                                Shipment Metadata
                            </h3>
                        </div>
                        <div class="p-6 grid grid-cols-2 md:grid-cols-4 gap-6">
                            <div v-for="(value, key) in order.parcel_details" :key="key" class="p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-100 dark:border-gray-800">
                                <div class="text-[10px] font-black uppercase text-gray-400 tracking-wider mb-1">{{ key }}</div>
                                <div class="font-bold text-gray-900 dark:text-white truncate">{{ value }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Manual Dispatch Override -->
                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden flex flex-col h-full sticky top-24">
                        <div class="p-6 border-b border-gray-100 dark:border-gray-800 bg-indigo-600">
                            <h3 class="font-bold text-white flex items-center gap-2">
                                <Truck class="w-5 h-5" />
                                Manual Dispatch
                            </h3>
                            <p class="text-indigo-100 text-xs mt-1">Assign an online driver to this order manually.</p>
                        </div>
                        
                        <div class="p-4 bg-gray-50 dark:bg-gray-800/30 border-b border-gray-100 dark:border-gray-800">
                            <div class="relative">
                                <Search class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" />
                                <input 
                                    type="text" 
                                    placeholder="Search drivers..."
                                    class="w-full pl-9 pr-4 py-2 text-sm bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all dark:text-white"
                                />
                            </div>
                        </div>

                        <div class="flex-1 overflow-y-auto max-h-[400px] divide-y divide-gray-100 dark:divide-gray-800">
                            <div v-for="driver in availableDrivers" :key="driver.id" class="p-4 hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors cursor-pointer group" @click="assignDriver(driver.id)">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center text-indigo-600 font-bold">
                                        {{ driver.user_id }}
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-sm font-bold text-gray-900 dark:text-white">Driver #{{ driver.user_id }}</div>
                                        <div class="text-[10px] text-gray-500 flex items-center gap-1 mt-0.5">
                                            <div class="w-1.5 h-1.5 bg-green-500 rounded-full"></div>
                                            Online & Available
                                        </div>
                                    </div>
                                    <button class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 group-hover:bg-indigo-600 group-hover:text-white flex items-center justify-center transition-all">
                                        <ChevronRight class="w-4 h-4" />
                                    </button>
                                </div>
                            </div>
                            
                            <div v-if="availableDrivers.length === 0" class="p-12 text-center">
                                <div class="w-12 h-12 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <AlertCircle class="w-6 h-6 text-gray-400" />
                                </div>
                                <div class="text-sm font-medium text-gray-500">No online drivers available for dispatch.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

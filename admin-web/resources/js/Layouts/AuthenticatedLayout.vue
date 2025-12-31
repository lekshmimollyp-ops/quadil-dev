<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import SidebarLink from '@/Components/SidebarLink.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { Link } from '@inertiajs/vue3';
import { 
    LayoutDashboard, 
    Users, 
    Truck, 
    ShoppingCart, 
    BarChart3, 
    Settings,
    LogOut,
    Menu,
    X,
    Bell,
    Building2,
    Wallet,
    CreditCard,
    Banknote,
    Globe,
    Navigation
} from 'lucide-vue-next';

const isSidebarOpen = ref(true);
const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};
</script>

<template>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-950 flex transition-colors duration-300">
        <!-- Sidebar -->
        <aside 
            class="fixed inset-y-0 left-0 z-50 transition-all duration-300 ease-in-out bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800"
            :class="isSidebarOpen ? 'w-64' : 'w-20'"
        >
            <div class="flex flex-col h-full">
                <!-- Branding -->
                <div class="p-6 flex items-center justify-between">
                    <Link :href="route('dashboard')" v-if="isSidebarOpen">
                        <ApplicationLogo class="h-8 w-auto fill-current text-indigo-600" />
                    </Link>
                    <div v-else class="mx-auto bg-indigo-600 p-1.5 rounded-lg shadow-sm">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                </div>

                <!-- Navigation Links -->
                <nav class="flex-1 px-4 space-y-2 mt-4">
                    <SidebarLink :href="route('dashboard')" :active="route().current('dashboard')" :icon="LayoutDashboard">
                        <span v-if="isSidebarOpen">Dashboard</span>
                    </SidebarLink>
                    
                    <!-- 1. System Settings -->
                    <div class="px-3 mt-4 mb-2">
                        <div v-if="isSidebarOpen" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">System Settings</div>
                        <div v-else class="h-px bg-gray-200 dark:bg-gray-800 mx-2"></div>
                    </div>

                    <SidebarLink :href="route('cities.index')" :active="route().current('cities.*')" :icon="Globe">
                        <span v-if="isSidebarOpen">City Masters</span>
                    </SidebarLink>

                    <SidebarLink :href="route('areas.index')" :active="route().current('areas.*')" :icon="Navigation">
                        <span v-if="isSidebarOpen">Service Areas</span>
                    </SidebarLink>

                    <SidebarLink :href="route('geo.masters.index')" :active="route().current('geo.masters.*')" :icon="Settings">
                        <span v-if="isSidebarOpen">System Masters</span>
                    </SidebarLink>

                    <!-- 2. Consignee Governance -->
                    <div class="px-3 mt-6 mb-2">
                        <div v-if="isSidebarOpen" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Consignee Governance</div>
                        <div v-else class="h-px bg-gray-200 dark:bg-gray-800 mx-2"></div>
                    </div>
                    <SidebarLink :href="route('merchants.index')" :icon="Users" :active="route().current('merchants.*')">
                        <span v-if="isSidebarOpen">Merchant Registry</span>
                    </SidebarLink>
                    <SidebarLink :href="route('merchants.hierarchy')" :icon="Building2" :active="route().current('merchants.hierarchy')">
                        <span v-if="isSidebarOpen">Organizational Tree</span>
                    </SidebarLink>

                    <!-- 3. Dispatch Governance -->
                    <div class="px-3 mt-6 mb-2">
                        <div v-if="isSidebarOpen" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Dispatch Governance</div>
                        <div v-else class="h-px bg-gray-200 dark:bg-gray-800 mx-2"></div>
                    </div>
                    <SidebarLink :href="route('agents.index')" :icon="Truck" :active="route().current('agents.*')">
                        <span v-if="isSidebarOpen">Agent Roster</span>
                    </SidebarLink>
                    <SidebarLink :href="route('orders.index')" :active="route().current('orders.*')" :icon="ShoppingCart">
                        <span v-if="isSidebarOpen">Live Orders</span>
                    </SidebarLink>
                    <SidebarLink :href="route('analytics.index')" :active="route().current('analytics.*')" :icon="BarChart3">
                        <span v-if="isSidebarOpen">MIS Analytics</span>
                    </SidebarLink>

                    <!-- 4. Reports & MIS (Accounts & MIS) -->
                    <div class="px-3 mt-6 mb-2">
                        <div v-if="isSidebarOpen" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Accounts & MIS</div>
                        <div v-else class="h-px bg-gray-200 dark:bg-gray-800 mx-2"></div>
                    </div>
                    <SidebarLink :href="route('wallets.index')" :icon="Wallet" :active="route().current('wallets.*')">
                        <span v-if="isSidebarOpen">Credit Control</span>
                    </SidebarLink>
                    <SidebarLink :href="route('accounts.ledgers')" :icon="CreditCard" :active="route().current('accounts.ledgers')">
                        <span v-if="isSidebarOpen">Usage Ledgers</span>
                    </SidebarLink>
                    <SidebarLink :href="route('accounts.cod-summary')" :icon="Banknote" :active="route().current('accounts.cod-summary')">
                        <span v-if="isSidebarOpen">COD Summary</span>
                    </SidebarLink>
                </nav>

                <!-- Footer / Logout -->
                <div class="p-4 border-t border-gray-100 dark:border-gray-800">
                    <SidebarLink :href="route('logout')" method="post" as="button" :icon="LogOut">
                        <span v-if="isSidebarOpen">Logout</span>
                    </SidebarLink>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div 
            class="flex-1 transition-all duration-300 ease-in-out"
            :class="isSidebarOpen ? 'ml-64' : 'ml-20'"
        >
            <!-- Top Header -->
            <header class="h-16 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between px-8 sticky top-0 z-40">
                <div class="flex items-center gap-4">
                    <button @click="toggleSidebar" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <Menu v-if="!isSidebarOpen" class="w-5 h-5 text-gray-500" />
                        <X v-else class="w-5 h-5 text-gray-500" />
                    </button>
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white" v-if="$slots.header">
                        <slot name="header" />
                    </h2>
                </div>

                <div class="flex items-center gap-4">
                    <button class="p-2 text-gray-500 hover:text-indigo-600 relative">
                        <Bell class="w-5 h-5" />
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white dark:border-gray-900"></span>
                    </button>
                    
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <button class="flex items-center gap-3 p-1.5 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 hidden sm:inline-block">
                                    {{ $page.props.auth.user.name }}
                                </span>
                                <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white text-xs font-bold ring-2 ring-indigo-50 dark:ring-indigo-900">
                                    {{ $page.props.auth.user.name.charAt(0) }}
                                </div>
                            </button>
                        </template>
                        <template #content>
                            <DropdownLink :href="route('profile.edit')">Profile</DropdownLink>
                            <DropdownLink :href="route('logout')" method="post" as="button">Log Out</DropdownLink>
                        </template>
                    </Dropdown>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-8">
                <slot />
            </main>
        </div>
    </div>
</template>

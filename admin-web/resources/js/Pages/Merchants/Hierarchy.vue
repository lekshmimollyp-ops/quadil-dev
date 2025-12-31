<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TreeNode from '@/Components/TreeNode.vue';
import { Head, Link } from '@inertiajs/vue3';
import { 
    Building2, 
    ChevronRight, 
    ChevronDown, 
    Briefcase, 
    Store,
    ExternalLink,
    Search
} from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps({
    hierarchy: {
        type: Array,
        default: () => [],
    },
});

const openNodes = ref(new Set());

const toggleNode = (id) => {
    if (openNodes.value.has(id)) {
        openNodes.value.delete(id);
    } else {
        openNodes.value.add(id);
    }
};

const getIcon = (type) => {
    switch (type) {
        case 'corporate': return Briefcase;
        case 'chain': return Building2;
        default: return Store;
    }
};

const getTypeColor = (type) => {
    switch (type) {
        case 'corporate': return 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/20 dark:text-indigo-400';
        case 'chain': return 'bg-amber-50 text-amber-700 dark:bg-amber-900/20 dark:text-amber-400';
        default: return 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-400';
    }
};
</script>

<template>
    <Head title="Merchant Hierarchy" />

    <AuthenticatedLayout>
        <template #header>
            Consignee Governance
        </template>

        <div class="space-y-6">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">Organizational Tree</h3>
                    <p class="text-sm text-gray-500 font-medium">Visualizing specialized tenant relationships (Corporate > Chain > Store).</p>
                </div>
                <Link :href="route('merchants.index')" class="px-4 py-2 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-50 transition-colors">
                    Flat List View
                </Link>
            </div>

            <div class="bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden p-8">
                <div class="max-w-4xl mx-auto py-10">
                    <div v-if="hierarchy.length === 0" class="text-center py-20 text-gray-400">
                        <Building2 class="w-16 h-16 mx-auto mb-4 opacity-10" />
                        <p class="font-bold uppercase tracking-widest text-xs">No organizational structures defined.</p>
                    </div>

                    <div class="space-y-4">
                        <TreeNode v-for="node in hierarchy" :key="node.id" :node="node" :isOpen="openNodes.has(node.id)" @toggle="toggleNode(node.id)" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

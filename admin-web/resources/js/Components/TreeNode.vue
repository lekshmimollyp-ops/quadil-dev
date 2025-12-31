<script setup>
import { Building2, Briefcase, Store, ChevronRight, ChevronDown, ExternalLink } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps({
    node: {
        type: Object,
        required: true
    },
    isOpen: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['toggle']);

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

const hasChildren = computed(() => props.node.children && props.node.children.length > 0);
</script>

<template>
    <div class="select-none">
        <div 
            class="flex items-center gap-4 p-4 rounded-2xl border border-transparent hover:border-gray-100 dark:hover:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-all cursor-pointer group"
            @click="emit('toggle')"
        >
            <div class="flex items-center gap-2">
                <component 
                    :is="hasChildren ? (isOpen ? ChevronDown : ChevronRight) : 'div'" 
                    class="w-4 h-4 text-gray-400"
                />
                <div :class="['p-2.5 rounded-xl shadow-sm', getTypeColor(node.type)]">
                    <component :is="getIcon(node.type)" class="w-5 h-5" />
                </div>
            </div>

            <div class="flex-1">
                <div class="flex items-center gap-3">
                    <h5 class="font-black text-gray-900 dark:text-white uppercase tracking-tighter">{{ node.name }}</h5>
                    <span :class="['px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-widest', getTypeColor(node.type)]">
                        {{ node.type }}
                    </span>
                </div>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ node.domain }}</p>
            </div>

            <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                <a :href="'/merchants/' + node.id + '/edit'" class="p-2 text-gray-400 hover:text-indigo-600">
                    <ExternalLink class="w-4 h-4" />
                </a>
            </div>
        </div>

        <div v-if="isOpen && hasChildren" class="ml-10 mt-2 border-l-2 border-gray-100 dark:border-gray-800 pl-4 space-y-2">
            <TreeNode 
                v-for="child in node.children" 
                :key="child.id" 
                :node="child" 
                :isOpen="false" 
                @toggle="emit('toggle')" 
            />
        </div>
    </div>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { Save, X, ArrowLeft, Info } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps({
    merchant: {
        type: Object,
        default: () => null,
    },
    merchants: {
        type: Array,
        default: () => [],
    },
    isEdit: {
        type: Boolean,
        default: false,
    },
});

// Filter out current merchant from parent options to prevent circular reference
const availableParents = computed(() => {
    if (!props.isEdit) return props.merchants;
    return props.merchants.filter(m => m.id !== props.merchant?.id);
});

const form = useForm({
    name: props.merchant?.name ?? '',
    type: props.merchant?.type ?? 'single',
    domain: props.merchant?.domain ?? '',
    settings: props.merchant?.settings ?? {},
    parent_tenant_id: props.merchant?.parent_tenant_id ?? '',
    is_active: props.merchant?.is_active ?? true,
});

const submit = () => {
    if (props.isEdit) {
        form.put(route('merchants.update', props.merchant.id));
    } else {
        form.post(route('merchants.store'));
    }
};
</script>

<template>
    <Head :title="isEdit ? 'Edit Merchant' : 'Add Merchant'" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-2">
                <Link :href="route('merchants.index')" class="p-1 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                    <ArrowLeft class="w-5 h-5 text-gray-500" />
                </Link>
                <span>{{ isEdit ? 'Edit Merchant' : 'Add Merchant' }}</span>
            </div>
        </template>

        <div class="max-w-4xl mx-auto">
            <form @submit.prevent="submit" class="space-y-8">
                <!-- Basic Information Card -->
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                        <h4 class="text-base font-semibold text-gray-900 dark:text-gray-100">Basic Information</h4>
                        <p class="text-sm text-gray-500 mt-1">Core details about the merchant partner.</p>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div class="space-y-1.5">
                                <label for="name" class="text-sm font-medium text-gray-700 dark:text-gray-300">Merchant Name</label>
                                <input
                                    id="name"
                                    type="text"
                                    v-model="form.name"
                                    placeholder="e.g. Pizza Hut"
                                    class="w-full px-4 py-2.5 bg-white dark:bg-gray-950 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all dark:text-white"
                                    required
                                />
                                <div v-if="form.errors.name" class="text-xs text-red-500 mt-1">{{ form.errors.name }}</div>
                            </div>

                            <!-- Domain -->
                            <div class="space-y-1.5">
                                <label for="domain" class="text-sm font-medium text-gray-700 dark:text-gray-300">Unique Identifier (Domain/Slug)</label>
                                <input
                                    id="domain"
                                    type="text"
                                    v-model="form.domain"
                                    placeholder="e.g. pizza-hut-mall"
                                    class="w-full px-4 py-2.5 bg-white dark:bg-gray-950 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all dark:text-white"
                                    required
                                />
                                <p class="text-[10px] text-gray-400">Used for URL identification and tenant isolation.</p>
                                <div v-if="form.errors.domain" class="text-xs text-red-500 mt-1">{{ form.errors.domain }}</div>
                            </div>

                            <!-- Type -->
                            <div class="space-y-1.5">
                                <label for="type" class="text-sm font-medium text-gray-700 dark:text-gray-300">Merchant Type</label>
                                <select
                                    id="type"
                                    v-model="form.type"
                                    class="w-full px-4 py-2.5 bg-white dark:bg-gray-950 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all dark:text-white"
                                >
                                    <option value="single">Single Store</option>
                                    <option value="chain">Service Chain</option>
                                    <option value="corporate">Corporate Entity</option>
                                </select>
                                <div v-if="form.errors.type" class="text-xs text-red-500 mt-1">{{ form.errors.type }}</div>
                            </div>

                            <!-- Status -->
                            <div class="space-y-1.5 pt-7">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <div class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" v-model="form.is_active" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Active Status</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Advanced Configuration Card -->
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                        <h4 class="text-base font-semibold text-gray-900 dark:text-gray-100">Advanced Settings</h4>
                        <p class="text-sm text-gray-500 mt-1">Configure parent relationships and custom settings.</p>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <div class="space-y-1.5">
                            <label for="parent_id" class="text-sm font-medium text-gray-700 dark:text-gray-300">Parent Merchant (Optional)</label>
                            <select
                                id="parent_id"
                                v-model="form.parent_tenant_id"
                                class="w-full px-4 py-2.5 bg-white dark:bg-gray-950 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all dark:text-white"
                            >
                                <option value="">None (Top Level Entity)</option>
                                <option v-for="m in availableParents" :key="m.id" :value="m.id">
                                    {{ m.name }} ({{ m.type }})
                                </option>
                            </select>
                            <p class="text-[10px] text-gray-400">Select a parent to create a hierarchical relationship (e.g., Chain under Corporate).</p>
                            <div v-if="form.errors.parent_tenant_id" class="text-xs text-red-500 mt-1">{{ form.errors.parent_tenant_id }}</div>
                        </div>

                        <div class="bg-indigo-50/50 dark:bg-indigo-900/10 border border-indigo-100 dark:border-indigo-900/30 rounded-lg p-4 flex gap-3 text-indigo-700 dark:text-indigo-300">
                            <Info class="w-5 h-5 flex-shrink-0 mt-0.5" />
                            <div class="text-sm">
                                <p class="font-medium">Settings are managed via JSON</p>
                                <p class="mt-1 opacity-80">Currently, settings are handled as a raw JSON object. In the next version, a visual settings builder will be available.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Bar -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-800">
                    <Link
                        :href="route('merchants.index')"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-950 border border-gray-300 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors"
                    >
                        Cancel
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center px-6 py-2.5 bg-indigo-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50"
                    >
                        <Save v-if="!form.processing" class="w-4 h-4 mr-2" />
                        <span v-else class="mr-2 animate-spin">⏳</span>
                        {{ isEdit ? 'Update Merchant' : 'Save Merchant' }}
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

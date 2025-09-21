<script setup lang="ts">
import type { HomeOwnerFilters } from '@/types/filters';
import { router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

interface Props {
    filters: HomeOwnerFilters;
}

const props = defineProps<Props>();

const search = ref(props.filters.search || '');

const performSearch = () => {
    router.get(
        '/homeowners',
        {
            search: search.value,
        },
        {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        },
    );
};

watch(search, performSearch);

const clearFilters = () => {
    search.value = '';
};
</script>

<template>
    <div class="mb-6 rounded-lg bg-white p-6 shadow">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                <input
                    id="search"
                    v-model="search"
                    type="text"
                    placeholder="Search by name, title, initial..."
                    class="mt-1 block w-full rounded-md border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                />
            </div>
            <div class="flex items-end">
                <button
                    @click="clearFilters"
                    type="button"
                    class="inline-flex items-center rounded-md bg-gray-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600"
                >
                    Clear Search
                </button>
            </div>
        </div>
    </div>
</template>

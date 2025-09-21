<script setup lang="ts">
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import type { HomeOwnerFilters } from '@/types/filters';

interface Props {
    filters: HomeOwnerFilters;
}

const props = defineProps<Props>();

const search = ref(props.filters.search || '');
const title = ref(props.filters.title || '');

const performSearch = () => {
    router.get('/homeowners', {
        search: search.value,
        title: title.value,
    }, {
        preserveScroll: true,
        preserveState: true,
        replace: true,
    });
};

watch([search, title], performSearch);

const clearFilters = () => {
    search.value = '';
    title.value = '';
};

const uniqueTitles = ['Mr', 'Mrs', 'Miss', 'Ms', 'Dr', 'Prof', 'Sir', 'Lord', 'Lady', 'Mister'];
</script>

<template>
    <div class="mb-6 bg-white shadow rounded-lg p-6">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                <input
                    id="search"
                    v-model="search"
                    type="text"
                    placeholder="Search by name, title..."
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm text-gray-900 bg-white px-3 py-2"
                />
            </div>
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <select
                    id="title"
                    v-model="title"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm text-gray-900 bg-white px-3 py-2"
                >
                    <option value="">All Titles</option>
                    <option v-for="titleOption in uniqueTitles" :key="titleOption" :value="titleOption">
                        {{ titleOption }}
                    </option>
                </select>
            </div>
            <div class="flex items-end">
                <button
                    @click="clearFilters"
                    type="button"
                    class="inline-flex items-center rounded-md bg-gray-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600"
                >
                    Clear Filters
                </button>
            </div>
        </div>
    </div>
</template>
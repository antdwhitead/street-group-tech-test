<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import IndexHeader from '@/components/homeowners/IndexHeader.vue';
import SearchFilters from '@/components/homeowners/SearchFilters.vue';
import HomeOwnersTable from '@/components/homeowners/HomeOwnersTable.vue';
import type { HomeOwner } from '@/types/homeOwner';
import type { PaginationData } from '@/types/pagination';
import type { HomeOwnerFilters } from '@/types/filters';

interface Props {
    homeOwners: PaginationData<HomeOwner>;
    filters: HomeOwnerFilters;
}

defineProps<Props>();
</script>

<template>
    <Head title="Home Owners" />

    <div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <IndexHeader />

            <SearchFilters :filters="filters" />

            <HomeOwnersTable :home-owners="homeOwners" :filters="filters" />
        </div>
    </div>
</template>
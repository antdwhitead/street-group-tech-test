<script setup lang="ts">
import HomeOwnersTable from '@/components/homeowners/HomeOwnersTable.vue';
import IndexHeader from '@/components/homeowners/IndexHeader.vue';
import SearchFilters from '@/components/homeowners/SearchFilters.vue';
import type { HomeOwnerFilters } from '@/types/filters';
import type { HomeOwner } from '@/types/homeOwner';
import type { PaginationData } from '@/types/pagination';
import { Head } from '@inertiajs/vue3';

interface Props {
    homeOwners: PaginationData<HomeOwner>;
    filters: HomeOwnerFilters;
}

defineProps<Props>();
</script>

<template>
    <Head title="Home Owners" />

    <div class="min-h-screen bg-gray-50 px-4 py-12 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <IndexHeader />

            <SearchFilters :filters="filters" />

            <HomeOwnersTable :home-owners="homeOwners" :filters="filters" />
        </div>
    </div>
</template>

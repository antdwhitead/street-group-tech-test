<script setup lang="ts">
import ResultsHeader from '@/components/results/ResultsHeader.vue';
import ResultsSummary from '@/components/results/ResultsSummary.vue';
import ResultsTable from '@/components/results/ResultsTable.vue';
import type { HomeOwner } from '@/types/homeOwner';
import { Head } from '@inertiajs/vue3';

interface Statistics {
    total_parsed: number;
    newly_created: number;
    duplicates_found: number;
}

interface Props {
    homeOwners: HomeOwner[];
    totalCount: number;
    statistics?: Statistics;
}

defineProps<Props>();
</script>

<template>
    <Head title="CSV Parsing Results" />

    <div class="min-h-screen bg-gray-50 px-4 py-12 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-6xl">
            <ResultsHeader :total-count="totalCount" :statistics="statistics" />

            <ResultsTable :home-owners="homeOwners" :total-count="totalCount" />

            <ResultsSummary :home-owners="homeOwners" :total-count="totalCount" :statistics="statistics" />
        </div>
    </div>
</template>

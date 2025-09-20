<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ResultsHeader from '@/components/results/ResultsHeader.vue';
import ResultsTable from '@/components/results/ResultsTable.vue';
import ResultsSummary from '@/components/results/ResultsSummary.vue';
import type { Person } from '@/types/person';

interface Statistics {
    total_parsed: number;
    newly_created: number;
    duplicates_found: number;
}

interface Props {
    people: Person[];
    totalCount: number;
    statistics?: Statistics;
}

defineProps<Props>();
</script>

<template>
    <Head title="CSV Parsing Results" />

    <div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            <ResultsHeader :total-count="totalCount" :statistics="statistics" />

            <ResultsTable :people="people" :total-count="totalCount" />

            <ResultsSummary :people="people" :total-count="totalCount" :statistics="statistics" />
        </div>
    </div>
</template>

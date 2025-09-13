<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ResultsHeader from '@/components/ResultsHeader.vue';
import ResultsTable from '@/components/ResultsTable.vue';
import ResultsSummary from '@/components/ResultsSummary.vue';
import type { Person } from '@/types/person';

interface Props {
    people: Person[];
    totalCount: number;
}

defineProps<Props>();
</script>

<template>
    <Head title="CSV Parsing Results" />

    <div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            <ResultsHeader :total-count="totalCount" />

            <ResultsTable :people="people" :total-count="totalCount" />

            <ResultsSummary :people="people" :total-count="totalCount" />
        </div>
    </div>
</template>
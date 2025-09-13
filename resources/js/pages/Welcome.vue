<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PageHeader from '@/components/PageHeader.vue';
import CsvUploadForm from '@/components/csv/CsvUploadForm.vue';
import CsvFormatGuide from '@/components/csv/CsvFormatGuide.vue';
</script>

<template>
    <Head title="Homeowner Names CSV Parser" />

    <div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto">
            <PageHeader
                title="Homeowner Names CSV Parser"
                description="Upload a CSV file containing homeowner names to parse and extract individual person records"
            />

            <CsvUploadForm />

            <div class="mt-6">
                <CsvFormatGuide />
            </div>
        </div>
    </div>
</template>

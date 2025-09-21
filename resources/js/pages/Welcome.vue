<script setup lang="ts">
import PageHeader from '@/components/PageHeader.vue';
import CsvFormatGuide from '@/components/csv/CsvFormatGuide.vue';
import CsvUploadForm from '@/components/csv/CsvUploadForm.vue';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Homeowner Names CSV Parser" />

    <div class="min-h-screen bg-gray-50 px-4 py-12 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl">
            <div class="mb-8 flex items-start justify-between">
                <PageHeader
                    title="Homeowner Names CSV Parser"
                    description="Upload a CSV file containing homeowner names to parse and extract individual person records"
                />
                <Link
                    href="/homeowners"
                    class="inline-flex items-center rounded-md bg-gray-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600"
                >
                    View All Home Owners
                </Link>
            </div>

            <CsvUploadForm />

            <div class="mt-6">
                <CsvFormatGuide />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

interface Props {
    totalCount: number;
}

defineProps<Props>();
</script>

<template>
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="mb-2 text-3xl font-bold text-gray-900">CSV Parsing Results</h1>
                <p class="text-lg text-gray-600">Successfully parsed {{ totalCount }} people from your CSV file</p>
            </div>
            <Link
                href="/public"
                class="inline-flex items-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none"
            >
                Upload Another File
            </Link>
        </div>
    </div>
</template>

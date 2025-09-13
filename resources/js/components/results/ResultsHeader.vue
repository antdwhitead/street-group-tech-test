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
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    CSV Parsing Results
                </h1>
                <p class="text-lg text-gray-600">
                    Successfully parsed {{ totalCount }} people from your CSV file
                </p>
            </div>
            <Link
                href="/public"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
                Upload Another File
            </Link>
        </div>
    </div>
</template>

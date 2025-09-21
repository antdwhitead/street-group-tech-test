<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="mb-8">
        <div class="md:flex md:items-center md:justify-between">
            <div class="min-w-0 flex-1">
                <h1 class="text-3xl font-bold text-gray-900">Home Owners</h1>
                <p class="mt-2 text-sm text-gray-700">Manage and browse all home owner records</p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <Link
                    href="/"
                    class="inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
                >
                    Upload CSV
                </Link>
            </div>
        </div>
    </div>
</template>

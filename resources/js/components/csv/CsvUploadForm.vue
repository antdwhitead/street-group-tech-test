<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

const csvFile = ref<File | null>(null);
const isUploading = ref(false);
const error = ref<string | null>(null);

const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0];

    if (file) {
        csvFile.value = file;
        error.value = null;
    }
};

const uploadCsv = () => {
    if (!csvFile.value) {
        error.value = 'Please select a CSV file';
        return;
    }

    isUploading.value = true;
    error.value = null;

    const formData = new FormData();
    formData.append('csv', csvFile.value);

    router.post('/homeowners/upload', formData, {
        onError: (errors) => {
            error.value = errors.csv || 'An error occurred while uploading the file';
            isUploading.value = false;
        },
        onFinish: () => {
            isUploading.value = false;
        },
    });
};
</script>

<template>
    <div class="rounded-lg bg-white p-8 shadow-lg">
        <div class="mb-6">
            <label for="csv-file" class="mb-2 block text-sm font-medium text-gray-700"> Select CSV File </label>
            <input
                id="csv-file"
                type="file"
                accept=".csv"
                @change="handleFileChange"
                class="block w-full text-sm text-gray-500 file:mr-4 file:rounded-full file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-100"
            />
        </div>

        <div v-if="error" class="mb-4 rounded-md border border-red-200 bg-red-50 p-4">
            <p class="text-sm text-red-600">{{ error }}</p>
        </div>

        <div class="mb-6">
            <button
                @click="uploadCsv"
                :disabled="!csvFile || isUploading"
                class="flex w-full justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none disabled:cursor-not-allowed disabled:bg-gray-400"
            >
                <span v-if="isUploading" class="flex items-center">
                    <svg class="mr-3 -ml-1 h-5 w-5 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path
                            class="opacity-75"
                            fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                        ></path>
                    </svg>
                    Processing...
                </span>
                <span v-else> Parse CSV </span>
            </button>
        </div>
    </div>
</template>

<script setup lang="ts">
import type { HomeOwner } from '@/types/homeOwner';

interface Props {
    homeOwners: HomeOwner[];
    totalCount: number;
}

defineProps<Props>();
</script>

<template>
    <div class="overflow-hidden rounded-lg bg-white shadow-lg">
        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
            <h2 class="text-lg font-medium text-gray-900">Parsed Home Owners ({{ totalCount }})</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase">#</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase">Title</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase">First Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase">Initial</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase">Last Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase">Full Name</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    <tr v-for="(homeOwner, index) in homeOwners" :key="index" class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm whitespace-nowrap text-gray-500">
                            {{ index + 1 }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium whitespace-nowrap text-gray-900">
                            {{ homeOwner.title || '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm whitespace-nowrap text-gray-900">
                            {{ homeOwner.first_name || '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm whitespace-nowrap text-gray-900">
                            {{ homeOwner.initial || '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm whitespace-nowrap text-gray-900">
                            {{ homeOwner.last_name || '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm whitespace-nowrap text-gray-900">
                            <span class="font-medium">
                                {{ [homeOwner.title, homeOwner.first_name || homeOwner.initial, homeOwner.last_name].filter(Boolean).join(' ') }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-if="homeOwners.length === 0" class="px-6 py-12 text-center">
            <div class="text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                    />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No home owners found</h3>
                <p class="mt-1 text-sm text-gray-500">The CSV file appears to be empty or contains no valid name data.</p>
            </div>
        </div>
    </div>
</template>

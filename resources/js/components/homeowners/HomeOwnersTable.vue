<script setup lang="ts">
import Pagination from '@/components/Pagination.vue';
import type { HomeOwnerFilters } from '@/types/filters';
import type { HomeOwner } from '@/types/homeOwner';
import type { PaginationData } from '@/types/pagination';
import { Link } from '@inertiajs/vue3';

interface Props {
    homeOwners: PaginationData<HomeOwner>;
    filters: HomeOwnerFilters;
}

defineProps<Props>();
</script>

<template>
    <div class="overflow-hidden rounded-lg bg-white shadow-lg">
        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
            <h2 class="text-lg font-medium text-gray-900">Home Owners ({{ homeOwners.total }})</h2>
        </div>

        <div v-if="homeOwners.data.length > 0" class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase">Title</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase">First Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase">Initial</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase">Last Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase">Full Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase">Created</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    <tr v-for="homeOwner in homeOwners.data" :key="homeOwner.id" class="hover:bg-gray-50">
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
                                {{ homeOwner.full_name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm whitespace-nowrap text-gray-500">
                            {{ homeOwner.created_at ? new Date(homeOwner.created_at).toLocaleDateString() : '-' }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-else class="px-6 py-12 text-center">
            <div class="text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                    />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No home owners found</h3>
                <p class="mt-1 text-sm text-gray-500">
                    {{ filters.search ? 'Try adjusting your search criteria.' : 'Upload a CSV file to get started.' }}
                </p>
                <div v-if="!filters.search" class="mt-6">
                    <Link
                        href="/"
                        class="inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
                    >
                        Upload CSV File
                    </Link>
                </div>
            </div>
        </div>

        <Pagination :pagination="homeOwners" :preserve-scroll="true" />
    </div>
</template>

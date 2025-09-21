<script setup lang="ts">
import type { PaginationData } from '@/types/pagination';
import { Link } from '@inertiajs/vue3';

interface Props {
    pagination: PaginationData;
    preserveScroll?: boolean;
}

defineProps<Props>();
</script>

<template>
    <div v-if="pagination.last_page > 1" class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
        <div class="flex flex-1 justify-between sm:hidden">
            <Link
                v-if="pagination.prev_page_url"
                :href="pagination.prev_page_url"
                :preserve-scroll="preserveScroll"
                class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
            >
                Previous
            </Link>
            <Link
                v-if="pagination.next_page_url"
                :href="pagination.next_page_url"
                :preserve-scroll="preserveScroll"
                class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
            >
                Next
            </Link>
        </div>
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Showing
                    <span class="font-medium">{{ pagination.from }}</span>
                    to
                    <span class="font-medium">{{ pagination.to }}</span>
                    of
                    <span class="font-medium">{{ pagination.total }}</span>
                    results
                </p>
            </div>
            <div>
                <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                    <template v-for="(link, index) in pagination.links" :key="index">
                        <Link
                            v-if="link.url"
                            :href="link.url"
                            :preserve-scroll="preserveScroll"
                            :class="[
                                'relative inline-flex items-center px-4 py-2 text-sm font-medium',
                                link.active
                                    ? 'z-10 bg-blue-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600'
                                    : 'text-gray-900 ring-1 ring-gray-300 ring-inset hover:bg-gray-50 focus:outline-offset-0',
                                index === 0 ? 'rounded-l-md' : '',
                                index === pagination.links.length - 1 ? 'rounded-r-md' : '',
                            ]"
                        >
                            {{ link.label }}
                        </Link>
                        <span
                            v-else
                            :class="[
                                'relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500',
                                index === 0 ? 'rounded-l-md' : '',
                                index === pagination.links.length - 1 ? 'rounded-r-md' : '',
                            ]"
                        >
                            {{ link.label }}
                        </span>
                    </template>
                </nav>
            </div>
        </div>
    </div>
</template>

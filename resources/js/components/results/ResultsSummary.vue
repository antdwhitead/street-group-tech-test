<script setup lang="ts">
import type { HomeOwner } from '@/types/homeOwner';

interface Props {
    homeOwners: HomeOwner[];
    totalCount: number;
    statistics?: {
        total_parsed: number;
        newly_created: number;
        duplicates_found: number;
    };
}

const props = defineProps<Props>();

const withFirstNames = props.homeOwners.filter((h) => h.first_name).length;
const withInitials = props.homeOwners.filter((h) => h.initial).length;
</script>

<template>
    <div v-if="homeOwners.length > 0" class="mt-6 rounded-lg bg-white p-6 shadow">
        <h3 class="mb-4 text-lg font-medium text-gray-900">Summary</h3>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div class="rounded-lg bg-blue-50 p-4">
                <div class="text-2xl font-bold text-blue-600">{{ totalCount }}</div>
                <div class="text-sm text-blue-600">Total Home Owners</div>
            </div>
            <div class="rounded-lg bg-green-50 p-4">
                <div class="text-2xl font-bold text-green-600">
                    {{ withFirstNames }}
                </div>
                <div class="text-sm text-green-600">With First Names</div>
            </div>
            <div class="rounded-lg bg-purple-50 p-4">
                <div class="text-2xl font-bold text-purple-600">
                    {{ withInitials }}
                </div>
                <div class="text-sm text-purple-600">With Initials</div>
            </div>
        </div>
    </div>
</template>

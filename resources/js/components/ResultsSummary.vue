<script setup lang="ts">
import type { Person } from '@/types/person';

interface Props {
    people: Person[];
    totalCount: number;
}

const props = defineProps<Props>();

const withFirstNames = props.people.filter(p => p.first_name).length;
const withInitials = props.people.filter(p => p.initial).length;
</script>

<template>
    <div v-if="people.length > 0" class="mt-6 bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Summary</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-blue-50 rounded-lg p-4">
                <div class="text-2xl font-bold text-blue-600">{{ totalCount }}</div>
                <div class="text-sm text-blue-600">Total People</div>
            </div>
            <div class="bg-green-50 rounded-lg p-4">
                <div class="text-2xl font-bold text-green-600">
                    {{ withFirstNames }}
                </div>
                <div class="text-sm text-green-600">With First Names</div>
            </div>
            <div class="bg-purple-50 rounded-lg p-4">
                <div class="text-2xl font-bold text-purple-600">
                    {{ withInitials }}
                </div>
                <div class="text-sm text-purple-600">With Initials</div>
            </div>
        </div>
    </div>
</template>
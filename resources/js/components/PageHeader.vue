<script setup lang="ts">
interface Props {
    title: string;
    description: string;
}

defineProps<Props>();
</script>

<template>
    <div class="mb-8 text-center">
        <h1 class="mb-2 text-3xl font-bold text-gray-900">
            {{ title }}
        </h1>
        <p class="text-lg text-gray-600">
            {{ description }}
        </p>
    </div>
</template>

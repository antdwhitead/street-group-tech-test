<script setup lang="ts">
interface Props {
    title: string;
    description: string;
}

defineProps<Props>();
</script>

<template>
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">
            {{ title }}
        </h1>
        <p class="text-lg text-gray-600">
            {{ description }}
        </p>
    </div>
</template>
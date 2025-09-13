<script setup lang="ts">
</script>

<template>
    <div class="text-sm text-gray-500">
        <h3 class="font-medium mb-2">Expected CSV Format:</h3>
        <ul class="space-y-1">
            <li>• Header row: "homeowner" (will be skipped)</li>
            <li>• Single names: "Mr John Smith"</li>
            <li>• Multiple people: "Mr & Mrs Smith" or "Mr and Mrs Smith"</li>
            <li>• Initials: "Mr J. Smith" or "Mr J Smith"</li>
            <li>• Various titles: Mr, Mrs, Ms, Dr, Prof, etc.</li>
        </ul>
    </div>
</template>
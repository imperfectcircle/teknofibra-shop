<template>
    <th
        @click="emit('click', field)"
        class="border-b-2 p-2 text-left cursor-pointer bg-gray-100"
        :class="field === sortField ? 'bg-blue-50' : ''"
    >
        <div class="flex justify-between">
            <slot></slot>
            <div v-if="sortField === field" class="ml-2">
                <!-- Sort Ascending -->
                <svg
                    v-if="sortDirection === 'asc'"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="size-6"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3 4.5h14.25M3 9h9.75M3 13.5h5.25m5.25-.75L17.25 9m0 0L21 12.75M17.25 9v12"
                    />
                </svg>

                <!-- Sort Descending -->
                <svg
                    v-else
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="size-6"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3 4.5h14.25M3 9h9.75M3 13.5h9.75m4.5-4.5v12m0 0-3.75-3.75M17.25 21 21 17.25"
                    />
                </svg>
            </div>
        </div>
    </th>
</template>

<script setup>
const { field, sortField, sortDirection } = defineProps({
    field: String,
    sortField: String,
    sortDirection: String,
});

const emit = defineEmits(["click"]);
</script>

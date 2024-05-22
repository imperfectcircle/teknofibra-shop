<template>
    <div
        class="w-[200px] bg-indigo-600 text-gray-100 py-8 pl-4 pr-2 space-y-2 rounded-l-2xl"
    >
        <router-link
            :to="{ name: 'app.dashboard' }"
            class="flex items-center py-1 px-2 rounded transition-colors hover:bg-black/30"
        >
            <span class="mr-2">
                <HomeIcon class="w-6" />
            </span>
            <span> Dashboard </span>
        </router-link>
        <router-link
            :to="{ name: 'app.dashboard' }"
            class="flex items-center py-1 px-2 rounded transition-colors hover:bg-black/30"
        >
            <span class="mr-2">
                <ArchiveBoxIcon class="w-6" />
            </span>
            <span> Prodotti </span>
        </router-link>
        <router-link
            :to="{ name: 'app.dashboard' }"
            class="flex items-center py-1 px-2 rounded transition-colors hover:bg-black/30"
        >
            <span class="mr-2">
                <UserIcon class="w-6" />
            </span>
            <span> Utenti </span>
        </router-link>
        <router-link
            :to="{ name: 'app.dashboard' }"
            class="flex items-center py-1 px-2 rounded transition-colors hover:bg-black/30"
        >
            <span class="mr-2">
                <ChartBarIcon class="w-6" />
            </span>
            <span> Report </span>
        </router-link>
    </div>
</template>

<script setup>
import {
    HomeIcon,
    UserIcon,
    ArchiveBoxIcon,
    ChartBarIcon,
} from "@heroicons/vue/24/outline";
</script>

<style scoped></style>

<template>
    <div
        class="w-[200px] transition-all bg-indigo-600 text-gray-100 py-8 pl-4 pr-2 space-y-2 rounded-l-2xl"
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
            :to="{ name: 'app.products' }"
            class="flex items-center py-1 px-2 rounded transition-colors hover:bg-black/30"
        >
            <span class="mr-2">
                <ArchiveBoxIcon class="w-6" />
            </span>
            <span> Prodotti </span>
        </router-link>
        <router-link
            :to="{ name: 'app.categories' }"
            class="flex items-center py-1 px-2 rounded transition-colors hover:bg-black/30"
        >
            <span class="mr-2">
                <TagIcon class="w-6" />
            </span>
            <span> Categorie </span>
        </router-link>
        <router-link
            :to="{ name: 'app.orders' }"
            class="flex items-center py-1 px-2 rounded transition-colors hover:bg-black/30"
        >
            <span class="mr-2">
                <ClipboardDocumentListIcon class="w-6" />
            </span>
            <span> Ordini </span>
        </router-link>
        <router-link
            :to="{ name: 'app.users' }"
            class="flex items-center py-1 px-2 rounded transition-colors hover:bg-black/30"
        >
            <span class="mr-2">
                <UserIcon class="w-6" />
            </span>
            <span> Utenti </span>
        </router-link>
        <router-link
            :to="{ name: 'app.customers' }"
            class="flex items-center py-1 px-2 rounded transition-colors hover:bg-black/30"
        >
            <span class="mr-2">
                <UserGroupIcon class="w-6" />
            </span>
            <span> Clienti </span>
        </router-link>
        <router-link
            :to="{ name: 'app.shipping-costs' }"
            class="flex items-center py-1 px-2 rounded transition-colors hover:bg-black/30"
        >
            <span class="mr-2">
                <TicketIcon class="w-6" />
            </span>
            <span> Spese di Spedizione </span>
        </router-link>
        <router-link
            :to="{ name: 'reports.orders' }"
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
    UserGroupIcon,
    ArchiveBoxIcon,
    TagIcon,
    ChartBarIcon,
    ClipboardDocumentListIcon,
    TicketIcon,
} from "@heroicons/vue/24/outline";
</script>

<style scoped></style>

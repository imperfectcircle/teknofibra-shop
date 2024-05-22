<template>
    <div v-if="currentUser.id" class="flex min-h-full bg-gray-100">
        <!-- Sidebar -->
        <Sidebar :class="{ '-ml-[200px]': !sidebarOpened }" />
        <!-- End Sidebar -->

        <div class="flex-1">
            <!-- Header -->
            <Navbar @toggle-sidebar="toggleSidebar" />
            <!-- End Header -->

            <!-- Content -->
            <main class="p-6">
                <div class="p-4 rounded bg-white shadow">
                    <router-view></router-view>
                </div>
            </main>
            <!-- End Content -->
        </div>
    </div>
    <div v-else class="min-h-full bg-gray-100 flex items-center justify-center">
        <div class="flex flex-col items-center">
            <svg
                class="animate-spin -ml-1 mr-3 h-16 w-16 text-black"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
            >
                <circle
                    class="opacity-25"
                    cx="12"
                    cy="12"
                    r="10"
                    stroke="currentColor"
                    stroke-width="4"
                ></circle>
                <path
                    class="opacity-75"
                    fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                ></path>
            </svg>
            <span class="mt-4 text-xl"> Attendi... </span>
        </div>
    </div>
</template>

<script setup>
import { computed, onMounted, onUnmounted, ref } from "vue";
import Sidebar from "./Sidebar.vue";
import Navbar from "./Navbar.vue";
import store from "../store";

const { title } = defineProps({
    title: String,
});

const sidebarOpened = ref(true);

const currentUser = computed(() => store.state.user.data);
const toggleSidebar = () => {
    sidebarOpened.value = !sidebarOpened.value;
};

onMounted(() => {
    store.dispatch("getUser");
    handleSidebarOpened();
    window.addEventListener("resize", handleSidebarOpened);
});

onUnmounted(() => {
    window.removeEventListener("resize", handleSidebarOpened);
});

const handleSidebarOpened = () => {
    sidebarOpened.value = window.innerWidth > 768;
};
</script>

<style scoped></style>

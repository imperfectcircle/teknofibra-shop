<template>
    <div class="flex min-h-full bg-gray-100">
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
</template>

<script setup>
import { onMounted, onUnmounted, ref } from "vue";
import Sidebar from "./Sidebar.vue";
import Navbar from "./Navbar.vue";

const { title } = defineProps({
    title: String,
});

const sidebarOpened = ref(true);
const toggleSidebar = () => {
    sidebarOpened.value = !sidebarOpened.value;
};

onMounted(() => {
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

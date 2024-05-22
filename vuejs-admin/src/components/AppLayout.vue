<template>
    <div class="flex min-h-full">
        <!-- Sidebar -->
        <Sidebar />
        <!-- End Sidebar -->

        <div class="flex-1">
            <!-- Header -->
            <header class="h-8 shadow bg-white">Header</header>
            <!-- End Header -->

            <!-- Content -->
            <main>
                <router-view></router-view>
            </main>
            <!-- End Content -->
        </div>
    </div>
</template>

<script setup>
import Sidebar from "./Sidebar.vue";

const { title } = defineProps({
    title: String,
});
</script>

<style scoped></style>

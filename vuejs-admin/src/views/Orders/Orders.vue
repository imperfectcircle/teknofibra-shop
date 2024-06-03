<template>
    <div class="flex items-center justify-between mb-3">
        <h1 class="text-3xl font-semibold">Ordini</h1>
    </div>
    <OrdersTable @clickView="viewOrders" />
</template>

<script setup>
import { computed, ref } from "vue";
import OrdersTable from "./OrdersTable.vue";
import store from "../../store";

const orders = computed(() => store.state.orders);
const showOrder = () => {};
</script>

<style scoped></style>

<template>
    <div class="flex items-center justify-between mb-3">
        <h1 class="text-3xl font-semibold">Clienti</h1>
    </div>
    <CustomersTable />
</template>

<script setup>
import { computed, ref } from "vue";
import store from "../../store";
import CustomersTable from "./CustomersTable.vue";

const DEFAULT_CUSTOMER = {};

const customers = computed(() => store.state.customers);

const customerModel = ref({ ...DEFAULT_CUSTOMER });
</script>

<style scoped></style>

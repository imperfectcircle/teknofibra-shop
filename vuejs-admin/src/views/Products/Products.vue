<template>
    <div class="flex items-center justify-between mb-3">
        <h1 class="text-3xl font-semibold">Prodotti</h1>
        <button
            @click="showProductModal"
            class="flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            type="submit"
        >
            Aggiungi un nuovo prodotto
        </button>
    </div>
    <ProductModal v-model="showModal" :product="productModel" />
    <ProductsTable />
</template>

<script setup>
import { ref } from "vue";
import ProductModal from "./ProductModal.vue";
import ProductsTable from "./ProductsTable.vue";

const showModal = ref(false);
const productModel = ref({
    id: "",
    title: "",
    price: "",
    image: "",
    description: "",
});

const showProductModal = () => {
    showModal.value = true;
};
</script>

<style scoped></style>

<template>
    <div class="flex items-center justify-between mb-3">
        <h1 class="text-3xl font-semibold">Prodotti</h1>
        <router-link
            :to="{ name: 'app.products.create' }"
            class="flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
            Aggiungi un nuovo prodotto
        </router-link>
    </div>
    <ProductModal
        v-model="showModal"
        :product="productModel"
        @close="onModalClose"
    />
    <ProductsTable @clickEdit="editProduct" />
</template>

<script setup>
import { ref } from "vue";
import ProductModal from "./ProductModal.vue";
import ProductsTable from "./ProductsTable.vue";
import store from "../../store";
import { DEFAULT_EMPTY_OBJECT } from "../../constants";

const showModal = ref(false);
const productModel = ref({
    ...DEFAULT_EMPTY_OBJECT,
});

const editProduct = (product) => {
    store.dispatch("getProduct", product.id).then(({ data }) => {
        productModel.value = data;
    });
};

const onModalClose = () => {
    productModel.value = {
        ...DEFAULT_EMPTY_OBJECT,
    };
};
</script>

<style scoped></style>

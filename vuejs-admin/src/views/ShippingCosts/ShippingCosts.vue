<template>
    <div>
        <h1 class="text-3xl font-semibold mb-6">Spese di Spedizione</h1>
        <div v-if="loading" class="text-center">
            <Spinner />
        </div>
        <form v-else @submit.prevent="saveShippingCosts">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div
                    v-for="country in activeCountries"
                    :key="country.code"
                    class="mb-4"
                >
                    <label
                        :for="country.code"
                        class="block text-sm font-medium text-gray-700"
                        >{{ country.name }}</label
                    >
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div
                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                        >
                            <span class="text-gray-500 sm:text-sm">€</span>
                        </div>
                        <input
                            type="number"
                            :id="country.code"
                            v-model="shippingCosts[country.code]"
                            step="0.01"
                            min="0"
                            class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"
                            placeholder="0.00"
                        />
                    </div>
                </div>
            </div>
            <div class="mt-6">
                <button
                    type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Salva Spese di Spedizione
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import axiosClient from "../../axios";
import Spinner from "../../components/core/Spinner.vue";
import { useStore } from "vuex";

const store = useStore();
const countries = ref([]);
const shippingCosts = ref({});
const loading = ref(true);

const activeCountries = computed(() => {
    return countries.value.filter((country) => country.active);
});

onMounted(async () => {
    try {
        const response = await axiosClient.get("/shipping-costs");
        countries.value = response.data;
        countries.value.forEach((country) => {
            shippingCosts.value[country.code] = country.shipping_cost
                ? country.shipping_cost.cost
                : "";
        });
    } catch (error) {
        console.error("Errore nel caricamento dei paesi:", error);
        store.commit("showToast", "Errore nel caricamento dei paesi");
    } finally {
        loading.value = false;
    }
});

const saveShippingCosts = async () => {
    try {
        const costs = Object.entries(shippingCosts.value).map(
            ([country_code, cost]) => ({
                country_code,
                cost: parseFloat(cost) || 0,
            })
        );

        await axiosClient.post("/shipping-costs-update", { costs });
        store.commit(
            "showToast",
            "Spese di spedizione aggiornate con successo"
        );
    } catch (error) {
        console.error(
            "Errore nel salvataggio delle spese di spedizione:",
            error
        );
        store.commit(
            "showToast",
            "Errore nel salvataggio delle spese di spedizione"
        );
    }
};
</script>

<template>
    <div v-if="customer.id" class="animate-fade-in-down">
        <form @submit.prevent="onSubmit">
            <div class="bg-white px-4 pt-5 pb-4">
                <h1 class="text-2xl font-semibold pb-2">{{ title }}</h1>
                <CustomInput
                    class="mb-2"
                    v-model="customer.first_name"
                    label="Nome"
                    :errors="errors.first_name"
                />
                <CustomInput
                    class="mb-2"
                    v-model="customer.last_name"
                    label="Cognome"
                    :errors="errors.last_name"
                />
                <CustomInput
                    class="mb-2"
                    v-model="customer.email"
                    label="Email"
                    :errors="errors.email"
                />
                <CustomInput
                    class="mb-2"
                    v-model="customer.phone"
                    label="Telefono"
                    :errors="errors.phone"
                />
                <CustomInput
                    type="checkbox"
                    class="mb-2"
                    v-model="customer.status"
                    label="Attivo"
                    :errors="errors.status"
                />

                <!-- <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h2
                            class="text-xl font-semibold mt-6 pb-2 border-b border-gray-300"
                        >
                            Indirizzo di Fatturazione
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            <CustomInput
                                v-model="customer.billingAddress.address1"
                                label="Indirizzo"
                                :errors="errors['billingAddress.address1']"
                            />
                            <CustomInput
                                v-model="customer.billingAddress.address2"
                                label="Num. Civico"
                                :errors="errors['billingAddress.address2']"
                            />
                            <CustomInput
                                v-model="customer.billingAddress.city"
                                label="Città"
                                :errors="errors['billingAddress.city']"
                            />
                            <CustomInput
                                v-model="customer.billingAddress.zipcode"
                                label="CAP"
                                :errors="errors['billingAddress.zipcode']"
                            />

                            <CustomInput
                                type="select"
                                :select-options="countries"
                                v-model="customer.billingAddress.country_code"
                                label="Nazione"
                                :errors="errors['billingAddress.country_code']"
                            />
                            <CustomInput
                                v-if="billingCountry && !billingCountry.states"
                                v-model="customer.billingAddress.state"
                                label="Stato/Provincia"
                                :errors="errors['billingAddress.state']"
                            />
                            <CustomInput
                                v-else
                                type="select"
                                :select-options="billingStateOptions"
                                v-model="customer.billingAddress.state"
                                label="State"
                                :errors="errors['billingAddress.state']"
                            />
                        </div>
                    </div>

                    <div>
                        <h2
                            class="text-xl font-semibold mt-6 pb-2 border-b border-gray-300"
                        >
                            Indirizzo di Spedizione
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            <CustomInput
                                v-model="customer.shippingAddress.address1"
                                label="Indirizzo"
                                :errors="errors['shippingAddress.address1']"
                            />
                            <CustomInput
                                v-model="customer.shippingAddress.address2"
                                label="Num. Civico"
                                :errors="errors['shippingAddress.address2']"
                            />
                            <CustomInput
                                v-model="customer.shippingAddress.city"
                                label="Città"
                                :errors="errors['shippingAddress.city']"
                            />
                            <CustomInput
                                v-model="customer.shippingAddress.zipcode"
                                label="CAP"
                                :errors="errors['shippingAddress.zipcode']"
                            />
                            <CustomInput
                                type="select"
                                :select-options="countries"
                                v-model="customer.shippingAddress.country_code"
                                label="Nazione"
                                :errors="errors['shippingAddress.country_code']"
                            />
                            <CustomInput
                                v-if="
                                    shippingCountry && !shippingCountry.states
                                "
                                v-model="customer.shippingAddress.state"
                                label="Stato/Provincia"
                                :errors="errors['shippingAddress.state']"
                            />
                            <CustomInput
                                v-else
                                type="select"
                                :select-options="shippingStateOptions"
                                v-model="customer.shippingAddress.state"
                                label="Stato"
                                :errors="errors['shippingAddress.state']"
                            />
                        </div>
                    </div>
                </div> -->
            </div>
            <footer
                class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
            >
                <button
                    type="submit"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 text-base font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500"
                >
                    Invia
                </button>
                <router-link
                    :to="{ name: 'app.customers' }"
                    type="button"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                    ref="cancelButtonRef"
                >
                    Annulla
                </router-link>
            </footer>
        </form>
    </div>
</template>

<script setup>
import { computed, onMounted, ref } from "vue";
import store from "../../store";
import { useRoute, useRouter } from "vue-router";
import CustomInput from "../../components/core/CustomInput.vue";

const router = useRouter();
const route = useRoute();

const title = ref("");
const errors = ref({
    first_name: [],
    last_name: [],
    email: [],
    phone: [],
    status: [],
    "billingAddress.address1": [],
    "billingAddress.address2": [],
    "billingAddress.city": [],
    "billingAddress.zipcode": [],
    "billingAddress.country_code": [],
    "billingAddress.state": [],
    "shippingAddress.address1": [],
    "shippingAddress.address2": [],
    "shippingAddress.city": [],
    "shippingAddress.zipcode": [],
    "shippingAddress.country_code": [],
    "shippingAddress.state": [],
});
const customer = ref({
    billingAddress: {},
    shippingAddress: {},
});
const loading = ref(false);

// const countries = computed(() =>
//     store.state.countries.map((c) => ({ key: c.code, text: c.name }))
// );
// const billingCountry = computed(() =>
//     store.state.countries.find(
//         (c) => c.code === customer.value.billingAddress.country_code
//     )
// );
// const billingStateOptions = computed(() => {
//     if (!billingCountry.value || !billingCountry.value.states) return [];

//     return Object.entries(billingCountry.value.states).map((c) => ({
//         key: c[0],
//         text: c[1],
//     }));
// });
// const shippingCountry = computed(() =>
//     store.state.countries.find(
//         (c) => c.code === customer.value.shippingAddress.country_code
//     )
// );
// const shippingStateOptions = computed(() => {
//     if (!shippingCountry.value || !shippingCountry.value.states) return [];

//     return Object.entries(shippingCountry.value.states).map((c) => ({
//         key: c[0],
//         text: c[1],
//     }));
// });

function onSubmit() {
    loading.value = true;
    if (customer.value.id) {
        customer.value.status = !!customer.value.status;
        store
            .dispatch("updateCustomer", customer.value)
            .then((response) => {
                loading.value = false;
                if (response.status === 200) {
                    store.commit(
                        "showToast",
                        "Il cliente è stato aggiornato con successo"
                    );
                    store.dispatch("getCustomers");
                    router.push({ name: "app.customers" });
                }
            })
            .catch((err) => {
                errors.value = err.response.data.errors;
            });
    } else {
        store
            .dispatch("createCustomer", customer.value)
            .then((response) => {
                loading.value = false;
                if (response.status === 201) {
                    // TODO show notification
                    store.dispatch("getCustomers");
                    router.push({ name: "app.customers" });
                }
            })
            .catch((err) => {
                loading.value = false;
                debugger;
            });
    }
}

onMounted(() => {
    store.dispatch("getCustomer", route.params.id).then(({ data }) => {
        title.value = `Aggiorna Cliente: "${data.first_name} ${data.last_name}"`;
        customer.value = data;
    });
});
</script>

<style scoped></style>

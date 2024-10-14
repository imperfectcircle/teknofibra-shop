<template>
    <div v-if="order">
        <!--  Order Details-->
        <div>
            <h2
                class="flex justify-between items-center text-xl font-semibold pb-2 border-b border-gray-300"
            >
                Dettagli Ordine
                <OrderStatus :order="order" />
            </h2>
            <table>
                <tbody>
                    <tr>
                        <td class="font-bold py-1 px-2">Ordine #</td>
                        <td>{{ order.id }}</td>
                    </tr>
                    <tr>
                        <td class="font-bold py-1 px-2">Data Ordine</td>
                        <td>{{ order.created_at }}</td>
                    </tr>
                    <tr>
                        <td class="font-bold py-1 px-2">Stato Ordine</td>
                        <td>
                            <select
                                v-model="order.status"
                                @change="onStatusChange"
                            >
                                <option
                                    v-for="status of orderStatuses"
                                    :value="status"
                                >
                                    {{ status }}
                                </option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="font-bold py-1 px-2">SubTotale</td>
                        <td>€{{ order.total_price }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!--/  Order Details-->

        <!--  Customer Details-->
        <div>
            <h2
                class="text-xl font-semibold mt-6 pb-2 border-b border-gray-300"
            >
                Dettagli Cliente
            </h2>
            <table>
                <tbody>
                    <tr>
                        <td class="font-bold py-1 px-2">Nome Completo</td>
                        <td>
                            {{ order.customer.first_name }}
                            {{ order.customer.last_name }}
                        </td>
                    </tr>
                    <tr>
                        <td class="font-bold py-1 px-2">Email</td>
                        <td>{{ order.customer.email }}</td>
                    </tr>
                    <tr>
                        <td class="font-bold py-1 px-2">Telefono</td>
                        <td>{{ order.customer.phone }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!--/  Customer Details-->

        <!--  Addresses Details-->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <h2
                    class="text-xl font-semibold mt-6 pb-2 border-b border-gray-300"
                >
                    Indirizzo di Fatturazione
                </h2>
                <!--  Billing Address Details-->
                <div>
                    {{ order.customer.billingAddress.address1 }},
                    {{ order.customer.billingAddress.address2 }} <br />
                    {{ order.customer.billingAddress.city }},
                    {{ order.customer.billingAddress.zipcode }} <br />
                    {{ order.customer.billingAddress.state }},
                    {{ order.customer.billingAddress.country }} <br />
                </div>
                <!--/  Billing Address Details-->
            </div>
            <div>
                <h2
                    class="text-xl font-semibold mt-6 pb-2 border-b border-gray-300"
                >
                    Indirizzo di Spedizione
                </h2>
                <!--  Shipping Address Details-->
                <div>
                    {{ order.customer.shippingAddress.address1 }},
                    {{ order.customer.shippingAddress.address2 }} <br />
                    {{ order.customer.shippingAddress.city }},
                    {{ order.customer.shippingAddress.zipcode }} <br />
                    {{ order.customer.shippingAddress.state }},
                    {{ order.customer.shippingAddress.country }} <br />
                </div>
                <!--/  Shipping Address Details-->
            </div>
        </div>
        <!--/  Customer Details-->

        <!--    Order Items-->
        <div>
            <h2
                class="text-xl font-semibold mt-6 pb-2 border-b border-gray-300"
            >
                Articoli Ordine
            </h2>
            <div v-for="item of order.items">
                <!-- Order Item -->
                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <a
                        href="#"
                        class="w-36 h-32 flex items-center justify-center overflow-hidden"
                    >
                        <img
                            :src="item.product.image"
                            class="object-cover"
                            alt=""
                        />
                    </a>
                    <div class="flex flex-col justify-between flex-1">
                        <div class="flex justify-between mb-3">
                            <h3>
                                {{ item.product.title }}
                            </h3>
                        </div>
                        <div class="flex justify-between items-center">
                            <div class="flex items-center">
                                Qty: {{ item.quantity }}
                            </div>
                            <span class="text-lg font-semibold">
                                €{{ item.unit_price }}
                            </span>
                        </div>
                    </div>
                </div>
                <!--/ Order Item -->
                <hr class="my-3" />
            </div>
        </div>
        <!--/    Order Items-->
    </div>
</template>

<script setup>
import { computed, onMounted, ref } from "vue";
import store from "../../store";
import { useRoute } from "vue-router";
import axiosClient from "../../axios.js";
import OrderStatus from "./OrderStatus.vue";

const route = useRoute();

const order = ref(null);
const orderStatuses = ref([]);

onMounted(() => {
    store.dispatch("getOrder", route.params.id).then(({ data }) => {
        order.value = data;
    });

    axiosClient
        .get(`/orders/statuses`)
        .then(({ data }) => (orderStatuses.value = data));
});

function onStatusChange() {
    axiosClient
        .post(`/orders/change-status/${order.value.id}/${order.value.status}`)
        .then(({ data }) => {
            store.commit(
                "showToast",
                `Lo stato dell'ordine è stato cambiato in "${order.value.status}"`
            );
        });
}
</script>

<style scoped></style>

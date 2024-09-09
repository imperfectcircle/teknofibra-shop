<template>
    <div>
        <h1 class="text-3xl font-semibold mb-6">Codici Sconto</h1>
        <form @submit.prevent="saveDiscountCode">
            <div class="mb-4">
                <label
                    for="code"
                    class="block text-sm font-medium text-gray-700"
                    >Codice</label
                >
                <input
                    type="text"
                    id="code"
                    v-model="discountCode.code"
                    required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                />
            </div>
            <div class="mb-4">
                <label
                    for="percentage"
                    class="block text-sm font-medium text-gray-700"
                    >Percentuale di sconto</label
                >
                <input
                    type="number"
                    id="percentage"
                    v-model="discountCode.percentage"
                    required
                    min="0"
                    max="100"
                    step="0.01"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                />
            </div>
            <div class="mb-4">
                <label
                    for="is_active"
                    class="block text-sm font-medium text-gray-700"
                    >Attivo</label
                >
                <input
                    type="checkbox"
                    id="is_active"
                    v-model="discountCode.is_active"
                    class="mt-1 rounded"
                />
            </div>
            <button
                type="submit"
                class="bg-blue-500 text-white px-4 py-2 rounded"
            >
                Salva
            </button>
        </form>

        <table class="mt-8 w-full">
            <thead>
                <tr class="border-b-2">
                    <th>Codice</th>
                    <th>Percentuale</th>
                    <th>Attivo</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="code in discountCodes"
                    :key="code.id"
                    class="text-center border-b-2"
                >
                    <td class="py-2">{{ code.code }}</td>
                    <td>{{ code.percentage }}%</td>
                    <td>{{ code.is_active ? "Sì" : "No" }}</td>
                    <td>
                        <button
                            @click="editDiscountCode(code)"
                            class="text-blue-500"
                        >
                            Modifica
                        </button>
                        <button
                            @click="deleteDiscountCode(code.id)"
                            class="text-red-500 ml-2"
                        >
                            Elimina
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import { ref, onMounted } from "vue";
import axiosClient from "../../axios";
import store from "../../store/index.js";

export default {
    setup() {
        const discountCodes = ref([]);
        const discountCode = ref({ code: "", percentage: 0, is_active: true });

        const fetchDiscountCodes = async () => {
            const response = await axiosClient.get("/discount-codes");
            discountCodes.value = response.data;
        };

        const saveDiscountCode = async () => {
            if (discountCode.value.id) {
                await axiosClient.put(
                    `/discount-codes/${discountCode.value.id}`,
                    discountCode.value
                );
                store.commit(
                    "showToast",
                    "Codice Sconto aggiornato con successo"
                );
            } else {
                await axiosClient.post("/discount-codes", discountCode.value);
                store.commit("showToast", "Codice Sconto creato con successo");
            }
            await fetchDiscountCodes();
            discountCode.value = { code: "", percentage: 0, is_active: true };
        };

        const editDiscountCode = (code) => {
            discountCode.value = { ...code };
        };

        const deleteDiscountCode = async (id) => {
            if (
                confirm("Sei sicuro di voler eliminare questo codice sconto?")
            ) {
                await axiosClient.delete(`/discount-codes/${id}`);
                await fetchDiscountCodes();
                store.commit(
                    "showToast",
                    "Codice Sconto eliminato con successo"
                );
            }
        };

        onMounted(fetchDiscountCodes);

        return {
            discountCodes,
            discountCode,
            saveDiscountCode,
            editDiscountCode,
            deleteDiscountCode,
        };
    },
};
</script>

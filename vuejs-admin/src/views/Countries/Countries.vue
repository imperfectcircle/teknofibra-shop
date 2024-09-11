<template>
    <div>
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-semibold">Gestione Paesi</h1>
            <button
                @click="showAddModal"
                class="py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
                Aggiungi Paese
            </button>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">Codice</th>
                        <th class="py-2 px-4 border-b">Nome</th>
                        <th class="py-2 px-4 border-b">Stati</th>
                        <th class="py-2 px-4 border-b">Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="country in countries"
                        :key="country.code"
                        class="text-center"
                    >
                        <td class="py-2 px-4 border-b">{{ country.code }}</td>
                        <td class="py-2 px-4 border-b">{{ country.name }}</td>
                        <td class="py-2 px-4 border-b">
                            {{
                                country.states
                                    ? Object.keys(JSON.parse(country.states))
                                          .length
                                    : "Nessuno stato"
                            }}
                        </td>
                        <td class="py-2 px-4 border-b">
                            <button
                                @click="editCountry(country)"
                                class="text-blue-500 mr-2"
                            >
                                Modifica
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <CountryModal
            v-if="showModal"
            :country="selectedCountry"
            @close="closeModal"
            @save="saveCountry"
        />
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axiosClient from "../../axios";
import CountryModal from "./CountryModal.vue";
import { useStore } from "vuex";

const store = useStore();
const countries = ref([]);
const showModal = ref(false);
const selectedCountry = ref(null);

onMounted(async () => {
    await loadCountries();
});

const loadCountries = async () => {
    try {
        const response = await axiosClient.get("/countries");
        countries.value = response.data;
    } catch (error) {
        console.error("Errore nel caricamento dei paesi:", error);
        store.commit("showToast", "Errore nel caricamento dei paesi");
    }
};

const showAddModal = () => {
    selectedCountry.value = { code: "", name: "", states: null };
    showModal.value = true;
};

const editCountry = (country) => {
    selectedCountry.value = { ...country };
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    selectedCountry.value = null;
};

const saveCountry = async (country) => {
    try {
        let response;
        if (
            country.code &&
            countries.value.some((c) => c.code === country.code)
        ) {
            // Aggiornamento di un paese esistente
            const { code, ...updateData } = country;
            response = await axiosClient.put(`/countries/${code}`, updateData);
            store.commit("showToast", "Paese aggiornato con successo");
        } else {
            // Creazione di un nuovo paese
            response = await axiosClient.post("/countries", country);
            store.commit("showToast", "Paese aggiunto con successo");
        }
        await loadCountries();
        closeModal();
    } catch (error) {
        console.error("Errore nel salvataggio del paese:", error);
        store.commit(
            "showToast",
            error.response?.data?.message || "Errore nel salvataggio del paese"
        );
    }
};
</script>

<template>
    <div class="fixed z-10 inset-0 overflow-y-auto">
        <div
            class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
        >
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span
                class="hidden sm:inline-block sm:align-middle sm:h-screen"
                aria-hidden="true"
                >&#8203;</span
            >
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
            >
                <form @submit.prevent="save">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="mb-4">
                            <label
                                for="code"
                                class="block text-sm font-medium text-gray-700"
                                >Codice</label
                            >
                            <input
                                type="text"
                                id="code"
                                v-model="countryData.code"
                                required
                                :disabled="!!props.country.code"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                            />
                        </div>
                        <div class="mb-4">
                            <label
                                for="name"
                                class="block text-sm font-medium text-gray-700"
                                >Nome</label
                            >
                            <input
                                type="text"
                                id="name"
                                v-model="countryData.name"
                                required
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                            />
                        </div>
                        <div class="mb-4">
                            <label
                                for="states"
                                class="block text-sm font-medium text-gray-700"
                                >Stati (JSON)</label
                            >
                            <textarea
                                id="states"
                                v-model="countryData.states"
                                rows="4"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                            ></textarea>
                        </div>
                    </div>
                    <div
                        class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
                    >
                        <button
                            type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Salva
                        </button>
                        <button
                            @click="$emit('close')"
                            type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Annulla
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from "vue";

const props = defineProps({
    country: Object,
});

const emit = defineEmits(["close", "save"]);

const countryData = ref({ code: "", name: "", states: null });

watch(
    () => props.country,
    (newCountry) => {
        if (newCountry) {
            countryData.value = { ...newCountry };
            if (countryData.value.states) {
                countryData.value.states = JSON.stringify(
                    countryData.value.states,
                    null,
                    2
                );
            }
        }
    },
    { immediate: true }
);

const save = () => {
    const saveData = { ...countryData.value };
    if (saveData.states) {
        try {
            saveData.states = JSON.parse(saveData.states);
        } catch (error) {
            alert("Il formato JSON degli stati non è valido");
            return;
        }
    }
    emit("save", saveData);
};
</script>

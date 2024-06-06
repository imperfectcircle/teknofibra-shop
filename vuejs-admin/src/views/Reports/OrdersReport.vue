<template>
    <div>
        <BarChart v-if="!loading" :data="chartData" :height="240" />
        <Spinner v-else />
    </div>
</template>

<script setup>
import axiosClient from "../../axios.js";
import { ref, watch } from "vue";
import BarChart from "../../components/core/Charts/Bar.vue";
import { useRoute } from "vue-router";
import Spinner from "../../components/core/Spinner.vue";

const route = useRoute();
const chartData = ref([]);

const loading = ref(true);

watch(
    route,
    (rt) => {
        getData();
    },
    { immediate: true }
);

function getData() {
    axiosClient
        .get("report/orders", { params: { d: route.params.date } })
        .then(({ data }) => {
            chartData.value = data;
            loading.value = false;
        });
}
</script>

<style scoped></style>

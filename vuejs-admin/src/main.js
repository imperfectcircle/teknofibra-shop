import { createApp } from "vue";
import CKEditor from "@ckeditor/ckeditor5-vue";
import store from "./store";
import router from "./router";
import "./style.css";
import App from "./App.vue";
import currencyEUR from "./filters/currency.js";

const app = createApp(App);
app.use(store).use(router).use(CKEditor).mount("#app");

app.config.globalProperties.$filters = {
    currencyEUR,
};

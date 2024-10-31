import { createRouter, createWebHistory } from "vue-router";
import Dashboard from "../views/Dashboard.vue";
import Login from "../views/Login.vue";
import RequestPassword from "../views/RequestPassword.vue";
import ResetPassword from "../views/ResetPassword.vue";
import AppLayout from "../components/AppLayout.vue";
import Products from "../views/Products/Products.vue";
import Categories from "../views/Categories/Categories.vue";
import Users from "../views/Users/Users.vue";
import Customers from "../views/Customers/Customers.vue";
import CustomerView from "../views/Customers/CustomerView.vue";
import Orders from "../views/Orders/Orders.vue";
import OrderView from "../views/Orders/OrderView.vue";
import store from "../store";
import NotFound from "../views/NotFound.vue";
import Report from "../views/Reports/Report.vue";
import OrdersReport from "../views/Reports/OrdersReport.vue";
import CustomersReport from "../views/Reports/CustomersReport.vue";
import ProductForm from "../views/Products/ProductForm.vue";
import ShippingCosts from "../views/ShippingCosts/ShippingCosts.vue";
import DiscountCodes from "../views/DiscountCodes/DiscountCodes.vue";
import Countries from "../views/Countries/Countries.vue";

const routes = [
    {
        path: "/",
        redirect: "/app",
    },
    {
        path: "/app",
        name: "app",
        component: AppLayout,
        meta: {
            requiresAuth: true,
        },
        children: [
            {
                path: "dashboard",
                name: "app.dashboard",
                component: Dashboard,
            },
            {
                path: "products",
                name: "app.products",
                component: Products,
            },
            {
                path: "products/create",
                name: "app.products.create",
                component: ProductForm,
            },
            {
                path: "products/:id",
                name: "app.products.edit",
                component: ProductForm,
                props: {
                    id: (value) => /^\d+$/.test(value),
                },
            },
            {
                path: "categories",
                name: "app.categories",
                component: Categories,
            },
            {
                path: "users",
                name: "app.users",
                component: Users,
            },
            {
                path: "orders",
                name: "app.orders",
                component: Orders,
            },
            {
                path: "orders/:id",
                name: "app.orders.view",
                component: OrderView,
            },
            {
                path: "customers",
                name: "app.customers",
                component: Customers,
            },
            {
                path: "customers/:id",
                name: "app.customers.view",
                component: CustomerView,
            },
            {
                path: "shipping-costs",
                name: "app.shipping-costs",
                component: ShippingCosts,
            },
            {
                path: "countries",
                name: "app.countries",
                component: Countries,
            },
            {
                path: "discount-codes",
                name: "app.discount-codes",
                component: DiscountCodes,
            },
            {
                path: "/report",
                name: "reports",
                component: Report,
                meta: {
                    requiresAuth: true,
                },
                children: [
                    {
                        path: "orders/:date?",
                        name: "reports.orders",
                        component: OrdersReport,
                    },
                    {
                        path: "customers/:date?",
                        name: "reports.customers",
                        component: CustomersReport,
                    },
                ],
            },
        ],
    },
    {
        path: "/login",
        name: "login",
        meta: {
            requiresGuest: true,
        },
        component: Login,
    },
    {
        path: "/:pathMatch(.*)",
        name: "notfound",
        component: NotFound,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    if (to.meta.requiresAuth && !store.state.user.token) {
        next({ name: "login" });
    } else if (to.meta.requiresGuest && store.state.user.token) {
        next({ name: "app.dashboard" });
    } else {
        next();
    }
});

export default router;

const state = {
    user: {
        token: sessionStorage.getItem("TOKEN"),
        data: {},
    },
    products: {
        loading: false,
        data: [],
        links: [],
        from: null,
        to: null,
        page: 1,
        limit: null,
        total: null,
    },
    users: {
        loading: false,
        data: [],
        links: [],
        from: null,
        to: null,
        page: 1,
        limit: null,
        total: null,
    },
    orders: {
        loading: false,
        data: [],
        links: [],
        from: null,
        to: null,
        page: 1,
        limit: null,
        total: null,
    },
    customers: {
        loading: false,
        data: [],
        links: [],
        from: null,
        to: null,
        page: 1,
        limit: null,
        total: null,
    },
    countries: [],
    toast: {
        show: false,
        message: "",
        delay: 5000,
    },
    dateOptions: [
        { key: "1d", text: "Oggi" },
        { key: "1w", text: "Ultima Settimana" },
        { key: "2w", text: "Ultime 2 Settimane" },
        { key: "1m", text: "Ultimo Mese" },
        { key: "3m", text: "Ultimi 3 Mesi" },
        { key: "6m", text: "Ultimi 6 Mesi" },
        { key: "all", text: "Sempre" },
    ],
};

export default state;

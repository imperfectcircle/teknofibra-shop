<template>
    <header class="h-14 shadow bg-white flex justify-between items-center p-4">
        <button
            @click="emit('toggle-sidebar')"
            class="flex items-center justify-center rounded transition-colors w-8 h-8 hover:bg-black/10"
        >
            <Bars3CenterLeftIcon
                class="w-8 transition-colors hover:text-black/60"
            />
        </button>

        <Menu as="div" class="relative inline-block text-left">
            <div>
                <MenuButton class="flex items-center">
                    <img
                        src="https://randomuser.me/api/portraits/lego/6.jpg"
                        alt=""
                        class="rounded-full w-10 mr-2"
                    />
                    {{ currentUser.name }}
                    <ChevronDownIcon
                        class="-mr-1 ml-2 h-5 w-5 text-black hover:text-black/50"
                        aria-hidden="true"
                    />
                </MenuButton>
            </div>

            <transition
                enter-active-class="transition duration-100 ease-out"
                enter-from-class="transform scale-95 opacity-0"
                enter-to-class="transform scale-100 opacity-100"
                leave-active-class="transition duration-75 ease-in"
                leave-from-class="transform scale-100 opacity-100"
                leave-to-class="transform scale-95 opacity-0"
            >
                <MenuItems
                    class="absolute right-0 mt-2 w-36 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none"
                >
                    <div class="px-1 py-1">
                        <MenuItem v-slot="{ active }">
                            <button
                                @click="logout"
                                :class="[
                                    active
                                        ? 'bg-indigo-600 text-white'
                                        : 'text-gray-900',
                                    'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                ]"
                            >
                                <ArrowLeftStartOnRectangleIcon
                                    :active="active"
                                    class="mr-2 h-5 w-5 text-indigo-400 group-hover:text-white"
                                    aria-hidden="true"
                                />
                                Logout
                            </button>
                        </MenuItem>
                    </div>
                </MenuItems>
            </transition>
        </Menu>
    </header>
</template>

<script setup>
import {
    Bars3CenterLeftIcon,
    ArrowLeftStartOnRectangleIcon,
    UserCircleIcon,
} from "@heroicons/vue/24/outline";
import { Menu, MenuButton, MenuItems, MenuItem } from "@headlessui/vue";
import { ChevronDownIcon } from "@heroicons/vue/20/solid";
import store from "../store";
import router from "../router";
import { computed } from "vue";

const emit = defineEmits(["toggle-sidebar"]);
const currentUser = computed(() => store.state.user.data);

const logout = () => {
    store.dispatch("logout").then(() => {
        router.push({ name: "login" });
    });
};
</script>

<style scoped></style>

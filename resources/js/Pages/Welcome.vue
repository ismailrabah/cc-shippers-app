<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3';
import BreezeApplicationLogo from '@/Components/ApplicationLogo.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean
})
</script>

<template>
    <Head title="Welcome" />

    <div class="relative flex items-top justify-center min-h-screen sm:items-center sm:pt-0 bg-gray-800">
        <div v-if="canLogin" class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
            <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="text-sm text-white">
                Dashboard
            </Link>

            <template v-else>
                <Link :href="route('login')" class="text-sm text-white">
                    Log in
                </Link>

                <Link v-if="canRegister" :href="route('register')" class="ml-4 text-sm text-white">
                    Register
                </Link>
            </template>
        </div>

        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 ">
            <div v-if="$page.props.auth.user" class="text-sky-400/100">
                <div class="p-6 max-w-sm mx-auto bg-white rounded-xl shadow-lg flex items-center space-x-4">
                    <div class="shrink-0">
                        <BreezeApplicationLogo class="h-12 w-12 fill-current text-gray-500" />
                    </div>
                    <div>
                        <div class="text-xl font-medium text-black">
                            <Link  :href="route('dashboard')" class="text-sky-400/100">
                                Go To Dashboard
                            </Link>
                        </div>
                        <p class="text-slate-500">Welcome back <b>{{$page.props.auth.user.name}}</b>.</p>
                    </div>
                </div>
            </div>
            <template v-else>
                <div class="p-6 max-w-sm mx-auto bg-white rounded-xl shadow-lg flex items-center space-x-4">
                    <div class="shrink-0">
                        <BreezeApplicationLogo class="h-12 w-12 fill-current text-gray-500" />
                    </div>
                    <div>
                        <div class="text-xl font-medium text-black mb-2">
                            <Link :href="route('login')" class="p-2 bg-sky-500/100 rounded-md text-sm text-white">
                                Log in
                            </Link>
                            <Link v-if="canRegister" :href="route('register')" class="p-2 bg-gray-800 rounded-md ml-4 text-sm text-white">
                                Register
                            </Link>
                        </div>
                        <p class="text-slate-500">Welcome To <b>CC Shippers App</b>.</p>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>

<style scoped>
</style>

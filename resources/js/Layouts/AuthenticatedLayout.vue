<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/inertia-vue3';
import DarkModeToggle from '@/Components/DarkToggle.vue';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div >
        <div class="min-h-screen bg-gray-100   dark:bg-gray-800">
            <nav class="bg-white border-gray-100 dark:bg-gray-900  print:hidden">
                <!-- Primary Navigation Menu -->
                <div class=" mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center" v-if="false"> 
                                <Link :href="route('dashboard')">
                                    <ApplicationLogo class="block h-9 w-auto" />
                                </Link>
                            </div>
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex" v-if="$page.props.auth.user.type_id==1||$page.props.auth.user.type_id==2||$page.props.auth.user.type_id==6||$page.props.auth.user.type_id==5||$page.props.auth.user.type_id==8||$page.props.auth.user.type_id==9">
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    الرئيسية
                                </NavLink>
                            </div> 
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex" v-if="$page.props.auth.user.type_id==1||$page.props.auth.user.type_id==5||$page.props.auth.user.type_id==2">
                                <NavLink :href="route('users.index')" :active="route().current('users.index')">
                                    المندوبين
                                </NavLink>
                            </div>
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex"  v-if="$page.props.auth.user.type_id==1||$page.props.auth.user.type_id==2||$page.props.auth.user.type_id==6||$page.props.auth.user.type_id==5">
                                <NavLink :href="route('formRegistration')" :active="route().current('formRegistration')">
                                     العقد الإلكتروني
                                </NavLink>
                            </div>

                            <div  class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex" v-if="$page.props.auth.user.type_id==1 ||$page.props.auth.user.type_id==5" >
                                <NavLink :href="route('FormRegistrationCourt')" :active="route().current('FormRegistrationCourt')">
                                    حسابات المندوبين
                                </NavLink>
                            </div>
                            <div  class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex" v-if="$page.props.auth.user.type_id==1||$page.props.auth.user.type_id==5" >
                                <NavLink :href="route('accounting')" :active="route().current('accounting')">
                                    المحاسبة
                                </NavLink>
                            </div>
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex" v-if="$page.props.auth.user.type_id==1||$page.props.auth.user.type_id==6" >
                                <NavLink :href="route('hospital')" :active="route().current('hospital')">
                                     الحجوزات
                                </NavLink>
                            </div>
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex" v-if="$page.props.auth.user.type_id==1||$page.props.auth.user.type_id==2||$page.props.auth.user.type_id==6||$page.props.auth.user.type_id==5" >
                                <NavLink :href="route('card')" :active="route().current('card')">
                                     عرض البطاقات
                                </NavLink>
                            </div>
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex" v-if="$page.props.auth.user.type_id==1||$page.props.auth.user.type_id==2||$page.props.auth.user.type_id==6||$page.props.auth.user.type_id==5" >
                                <NavLink :href="route('CardsMobile')" :active="route().current('CardsMobile')">
                                        البطاقات 
                                </NavLink>
                            </div>
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex" v-if="$page.props.auth.user.type_id==1||$page.props.auth.user.type_id==2||$page.props.auth.user.type_id==6||$page.props.auth.user.type_id==5" >
                                <NavLink :href="route('CategoryCardMobile')" :active="route().current('CategoryCardMobile')">
                                    تصنيفات
                                    البطاقات  
                                </NavLink>
                            </div>
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex " v-if="$page.props.auth.user.type_id==1||$page.props.auth.user.type_id==2||$page.props.auth.user.type_id==6||$page.props.auth.user.type_id==5" >
                                <NavLink :href="route('ServicesCardMobile')" :active="route().current('ServicesCardMobile')">
                                       خدمات البطاقات
                                </NavLink>
                            </div>

                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex" v-if="$page.props.auth.user.type_id==1||$page.props.auth.user.type_id==2||$page.props.auth.user.type_id==6||$page.props.auth.user.type_id==5" >
                                <NavLink :href="route('PendingRequest')" :active="route().current('PendingRequest')">
                                      طلبات المعلقة
                                </NavLink>
                            </div>
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex" v-if="$page.props.auth.user.type_id==1||$page.props.auth.user.type_id==2||$page.props.auth.user.type_id==6||$page.props.auth.user.type_id==5" >
                                <NavLink :href="route('settings')" :active="route().current('settings')">
                                       اعدادات التطبيق
                                </NavLink>
                            </div>
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex" v-if="$page.props.auth.user.type_id==8" >
                                <NavLink :href="route('sort_phone')" :active="route().current('sort_phone')">
                                     الإسناد
                                </NavLink>
                            </div>

                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex" v-if="$page.props.auth.user.type_id==8||$page.props.auth.user.type_id==9" >
                                <NavLink :href="route('contact_phone')" :active="route().current('contact_phone')">
                                       تم الاسناد
                                </NavLink>
                            </div>
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex" v-if="$page.props.auth.user.type_id==8||$page.props.auth.user.type_id==9" >
                                <NavLink :href="route('accept_offer')" :active="route().current('accept_offer')">
                                    تم قبول العرض
                                </NavLink>
                            </div>
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex" v-if="$page.props.auth.user.type_id==8||$page.props.auth.user.type_id==9" >
                                <NavLink :href="route('reject_offer')" :active="route().current('reject_offer')">
                                    رفض العرض
                                </NavLink>
                            </div>
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex" v-if="$page.props.auth.user.type_id==8||$page.props.auth.user.type_id==9" >
                                <NavLink :href="route('follow_up')" :active="route().current('follow_up')">
                                    معاودة مره اخرى
                                </NavLink>
                            </div>
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex" v-if="$page.props.auth.user.type_id==8||$page.props.auth.user.type_id==9" >
                                <NavLink :href="route('busy')" :active="route().current('busy')">
                                    مشغول
                                </NavLink>
                            </div>
                        </div>
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <!-- Settings Dropdown -->
                            <div class="ml-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                                {{ $page.props.auth.user.name }}

                                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('logout')" method="post" as="button">
                                            تسجيل الخروج
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                            <DarkModeToggle></DarkModeToggle>
                        </div>

                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button @click="showingNavigationDropdown = ! showingNavigationDropdown" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{'hidden': showingNavigationDropdown, 'inline-flex': ! showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{'hidden': ! showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}" class="sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            لوحة القيادة
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
        
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800">{{ $page.props.auth.user.name }}</div>
                            <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    الرئيسية
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('users.index')" :active="route().current('users.index')" v-if="$page.props.auth.user.type_id==1 ||$page.props.auth.user.type_id==5">
                                    المندوبين
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('formRegistration')" :active="route().current('formRegistration')"  v-if="$page.props.auth.user.type_id==1||$page.props.auth.user.type_id==2||$page.props.auth.user.type_id==6||$page.props.auth.user.type_id==5">
                                     العقد الإلكتروني
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('FormRegistrationCourt')" :active="route().current('FormRegistrationCourt')" v-if="$page.props.auth.user.type_id==1||$page.props.auth.user.type_id==5">
                                     حسابات المندوبين       
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('accounting')" :active="route().current('accounting')" v-if="$page.props.auth.user.type_id==1||$page.props.auth.user.type_id==2||$page.props.auth.user.type_id==5">
                                      المحاسبة       
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('hospital')" :active="route().current('hospital')" v-if="$page.props.auth.user.type_id==1||$page.props.auth.user.type_id==6">
                                    الحجوزات
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('card')" :active="route().current('card')" v-if="$page.props.auth.user.type_id==1||$page.props.auth.user.type_id==2||$page.props.auth.user.type_id==6">
                                عرض البطاقات
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('sort_phone')" :active="route().current('sort_phone')" v-if="$page.props.auth.user.type_id==8">
                                الإسناد
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('contact_phone')" :active="route().current('contact_phone')" v-if="$page.props.auth.user.type_id==8||$page.props.auth.user.type_id==9">
                                تم الاسناد
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('accept_offer')" :active="route().current('accept_offer')" v-if="$page.props.auth.user.type_id==8||$page.props.auth.user.type_id==9">
                                تم قبول العرض
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('reject_offer')" :active="route().current('reject_offer')" v-if="$page.props.auth.user.type_id==8||$page.props.auth.user.type_id==9">
                                رفض العرض
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('follow_up')" :active="route().current('follow_up')" v-if="$page.props.auth.user.type_id==8||$page.props.auth.user.type_id==9">
                                معاودة مره اخرى
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('busy')" :active="route().current('busy')" v-if="$page.props.auth.user.type_id==8||$page.props.auth.user.type_id==9">
                                مشغول
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                                تسجيل الخروج    
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>
            <!-- Page Heading -->
            <header class="bg-white shadow dark:bg-gray-900 dark:text-gray-200" v-if="$slots.header">
                <div class="max-w-7xl mx-auto  px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-row">
                        <div class="basis-1/2 flex flex-col justify-center">
                            <slot name="header" />
                        </div>
                        <div class="basis-1/2">
                            <img src="/asset/img/logo.jpg" alt="karbala-alhassan"   style="margin-right: auto;width: 200px;" />
                        </div>
                </div>
                </div>
  

            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>

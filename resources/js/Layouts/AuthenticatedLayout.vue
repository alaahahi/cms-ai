<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import DarkModeToggle from '@/Components/DarkToggle.vue';
import { Link } from '@inertiajs/inertia-vue3';

const showingNavigationDropdown = ref(false);
const openMobileGroup = ref(null);

const can = (userType, ...types) => types.includes(Number(userType));

const isRouteActive = (name) => {
    try {
        return route().current(name);
    } catch (e) {
        return false;
    }
};

const groupHasActive = (items) => items.some((item) => isRouteActive(item.route));

const buildGroups = (userType) => {
    const t = Number(userType);
    const groups = [
        {
            key: 'erp',
            label: 'نظام ERP',
            items: [
                { label: 'لوحة التدفق', route: 'dashboard', show: can(t, 1, 2, 5, 6, 8, 9) },
                { label: '1) الاشتراكات', route: 'erp.subscriptions.index', show: can(t, 1, 2, 5, 6) },
                { label: '2) المزودين', route: 'erp.providers.index', show: can(t, 1, 2, 5, 6) },
                { label: '2) الزيارات', route: 'erp.visits.index', show: can(t, 1, 2, 5, 6) },
                { label: '3) المبيعات', route: 'erp.sales.index', show: can(t, 1, 2, 5) },
                { label: '4) التذاكر', route: 'erp.tickets.index', show: can(t, 1, 2, 5, 6) },
                { label: '5) الفواتير', route: 'erp.invoices.index', show: can(t, 1, 5) },
                { label: '6) التحليلات', route: 'erp.analytics.index', show: can(t, 1, 2, 5) },
            ],
        },
        {
            key: 'phones',
            label: 'الهاتف والمبيعات',
            items: [
                { label: 'لوحة الهاتف', route: 'dashboard_phone', show: can(t, 1, 8, 9) },
                { label: 'الإسناد', route: 'sort_phone', show: can(t, 1, 8) },
                { label: 'أرقام جديدة', route: 'new_phone', show: can(t, 1, 8) },
                { label: 'تم الإسناد', route: 'contact_phone', show: can(t, 1, 8, 9) },
                { label: 'قبول العرض', route: 'accept_offer', show: can(t, 1, 8, 9) },
                { label: 'رفض العرض', route: 'reject_offer', show: can(t, 1, 8, 9) },
                { label: 'معاودة مرة أخرى', route: 'follow_up', show: can(t, 1, 8, 9) },
                { label: 'مشغول', route: 'busy', show: can(t, 1, 8, 9) },
            ],
        },
        {
            key: 'legacy',
            label: 'النظام القديم',
            items: [
                { label: 'لوحة قديمة', route: 'dashboard.legacy', show: can(t, 1, 2, 5, 6) },
                { label: 'العقد الإلكتروني', route: 'formRegistration', show: can(t, 1, 2, 5, 6) },
                { label: 'عرض البطاقات', route: 'card', show: can(t, 1, 2, 5, 6) },
                { label: 'الطلبات المعلقة', route: 'PendingRequest', show: can(t, 1, 2, 5, 6) },
                { label: 'حسابات المندوبين', route: 'FormRegistrationCourt', show: can(t, 1, 5) },
                { label: 'العقود المكتملة', route: 'FormRegistrationCompleted', show: can(t, 1, 2, 5) },
                { label: 'تسجيل استمارة', route: 'تسجيل-الاستمارة', show: can(t, 1, 2, 5) },
                { label: 'البطاقات', route: 'CardsMobile', show: can(t, 1, 2, 5, 6) },
                { label: 'تصنيفات البطاقات', route: 'CategoryCardMobile', show: can(t, 1, 2, 5, 6) },
                { label: 'خدمات البطاقات', route: 'ServicesCardMobile', show: can(t, 1, 2, 5, 6) },
                { label: 'الحجوزات', route: 'hospital', show: can(t, 1, 6) },
                { label: 'المحاسبة القديمة', route: 'accounting', show: can(t, 1, 5) },
                { label: 'المندوبين', route: 'users.index', show: can(t, 1, 2, 5) },
                { label: 'إعدادات التطبيق', route: 'settings', show: can(t, 1, 2, 5, 6) },
                { label: 'نظام واتساب', route: 'whatsapp-system', show: can(t, 1, 8) },
                { label: 'فحص واتساب', route: 'whatsapp-checker', show: can(t, 1, 8) },
                { label: 'استيراد أرقام', route: 'import-numbers', show: can(t, 1, 8) },
                { label: 'إدارة data_cv', route: 'data-cv-manage', show: can(t, 1, 8) },
                { label: 'إدارة الطوابير', route: 'queue-manage', show: can(t, 1, 8) },
            ],
        },
    ];

    return groups
        .map((g) => ({ ...g, items: g.items.filter((i) => i.show) }))
        .filter((g) => g.items.length > 0);
};

const hrefFor = (name) => {
    try {
        return route(name);
    } catch (e) {
        return '#';
    }
};

const toggleMobileGroup = (key) => {
    openMobileGroup.value = openMobileGroup.value === key ? null : key;
};
</script>

<template>
    <div class="min-h-screen bg-slate-100 dark:bg-slate-950">
        <nav class="print:hidden sticky top-0 z-50 overflow-visible border-b border-slate-800/40 bg-slate-900 text-slate-100 shadow-lg">
            <div class="mx-auto max-w-[1600px] overflow-visible px-3 sm:px-5 lg:px-6">
                <div class="flex h-16 items-center justify-between gap-3 overflow-visible">
                    <div class="flex min-w-0 flex-1 items-center gap-3 overflow-visible">
                        <Link :href="hrefFor('dashboard')" class="flex shrink-0 items-center gap-2.5 rounded-lg px-2 py-1.5 hover:bg-white/5">
                            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-teal-500/15 ring-1 ring-teal-400/30">
                                <ApplicationLogo class="block h-6 w-auto fill-current text-teal-300" />
                            </span>
                            <span class="hidden leading-tight lg:block">
                                <span class="block text-sm font-semibold text-white">CMS AI</span>
                                <span class="block text-[11px] text-slate-400">نظام التأمين الصحي</span>
                            </span>
                        </Link>

                        <div class="hidden min-w-0 flex-wrap items-center gap-1 overflow-visible lg:flex">
                            <Dropdown
                                v-for="group in buildGroups($page.props.auth.user.type_id)"
                                :key="group.key"
                                align="left"
                                width="64"
                                :content-classes="['py-2', 'bg-white', 'dark:bg-slate-800']"
                            >
                                <template #trigger>
                                    <button
                                        type="button"
                                        class="inline-flex items-center gap-1.5 whitespace-nowrap rounded-lg px-3 py-2 text-sm font-medium transition"
                                        :class="groupHasActive(group.items)
                                            ? 'bg-teal-500/20 text-teal-200 ring-1 ring-teal-400/30'
                                            : 'text-slate-300 hover:bg-white/5 hover:text-white'"
                                    >
                                        {{ group.label }}
                                        <svg class="h-3.5 w-3.5 opacity-70" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </template>

                                <template #content>
                                    <div class="border-b border-slate-100 px-4 py-2 text-[11px] font-semibold text-slate-400 dark:border-slate-700">
                                        {{ group.label }}
                                    </div>
                                    <DropdownLink
                                        v-for="item in group.items"
                                        :key="item.route"
                                        :href="hrefFor(item.route)"
                                        :class="isRouteActive(item.route) ? '!bg-teal-50 !text-teal-800 dark:!bg-teal-900/40 dark:!text-teal-200' : ''"
                                    >
                                        {{ item.label }}
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                    </div>

                    <div class="hidden items-center gap-2 sm:flex">
                        <DarkModeToggle />
                        <Dropdown align="right" width="56" :content-classes="['py-1', 'bg-white', 'dark:bg-slate-800']">
                            <template #trigger>
                                <button type="button" class="inline-flex items-center gap-2 rounded-lg bg-white/5 px-3 py-2 text-sm text-slate-200 ring-1 ring-white/10 hover:bg-white/10">
                                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-teal-500/20 text-xs font-bold text-teal-200">
                                        {{ $page.props.auth.user.name?.charAt(0) || 'U' }}
                                    </span>
                                    <span class="max-w-[120px] truncate">{{ $page.props.auth.user.name }}</span>
                                    <svg class="h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </template>
                            <template #content>
                                <div class="border-b border-slate-100 px-4 py-3 dark:border-slate-700">
                                    <div class="text-sm font-semibold text-slate-800 dark:text-slate-100">{{ $page.props.auth.user.name }}</div>
                                    <div class="truncate text-xs text-slate-500">{{ $page.props.auth.user.email }}</div>
                                </div>
                                <DropdownLink :href="route('logout')" method="post" as="button" class="!text-rose-600">
                                    تسجيل الخروج
                                </DropdownLink>
                            </template>
                        </Dropdown>
                    </div>

                    <button
                        type="button"
                        class="inline-flex items-center justify-center rounded-lg p-2 text-slate-300 hover:bg-white/10 lg:hidden"
                        @click="showingNavigationDropdown = !showingNavigationDropdown"
                    >
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div v-show="showingNavigationDropdown" class="border-t border-white/10 bg-slate-950/95 lg:hidden">
                <div class="max-h-[75vh] space-y-1 overflow-y-auto px-3 py-3">
                    <div
                        v-for="group in buildGroups($page.props.auth.user.type_id)"
                        :key="'m-' + group.key"
                        class="rounded-lg bg-white/5"
                    >
                        <button
                            type="button"
                            class="flex w-full items-center justify-between px-3 py-2.5 text-sm font-semibold text-slate-100"
                            @click="toggleMobileGroup(group.key)"
                        >
                            <span>{{ group.label }}</span>
                            <svg class="h-4 w-4 transition" :class="{ 'rotate-180': openMobileGroup === group.key }" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div v-show="openMobileGroup === group.key || groupHasActive(group.items)" class="space-y-0.5 border-t border-white/10 px-2 py-2">
                            <Link
                                v-for="item in group.items"
                                :key="'mi-' + item.route"
                                :href="hrefFor(item.route)"
                                class="block rounded-md px-3 py-2 text-sm"
                                :class="isRouteActive(item.route) ? 'bg-teal-500/20 text-teal-200' : 'text-slate-300 hover:bg-white/5'"
                                @click="showingNavigationDropdown = false"
                            >
                                {{ item.label }}
                            </Link>
                        </div>
                    </div>

                    <div class="mt-3 border-t border-white/10 pt-3">
                        <div class="px-3 pb-2">
                            <div class="text-sm font-semibold text-white">{{ $page.props.auth.user.name }}</div>
                            <div class="truncate text-xs text-slate-400">{{ $page.props.auth.user.email }}</div>
                        </div>
                        <Link :href="route('logout')" method="post" as="button" class="block w-full rounded-lg px-3 py-2.5 text-right text-sm font-medium text-rose-300 hover:bg-rose-500/10">
                            تسجيل الخروج
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <header
            v-if="$slots.header"
            class="border-b border-slate-200/80 bg-white/90 dark:border-slate-800 dark:bg-slate-900/90 dark:text-slate-100"
        >
            <div class="mx-auto flex max-w-[1600px] items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                <div class="min-w-0 flex-1">
                    <slot name="header" />
                </div>
                <img src="/asset/img/logo.jpg" alt="logo" class="hidden h-12 w-auto object-contain sm:block" />
            </div>
        </header>

        <main class="mx-auto max-w-[1600px]">
            <slot />
        </main>
    </div>
</template>

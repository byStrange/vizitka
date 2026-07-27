<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Check,
    ArrowRight,
    Factory,
    Palette,
    Clock,
    DollarSign,
    Package,
} from 'lucide-vue-next';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { useTranslations } from '@/composables/useTranslations';
import { catalog, contact, home } from '@/routes';

defineOptions({ layout: PublicLayout });
const { __ } = useTranslations();

const props = defineProps<{
    categories: Array<{
        slug: string;
        name: string;
        description: string;
        itemCount: number;
    }>;
    newProducts: Array<{
        id: number;
        slug: string;
        name: string;
        sku: string;
        image?: string;
        isNew: boolean;
    }>;
    discountProducts: Array<{
        id: number;
        slug: string;
        name: string;
        sku: string;
        image?: string;
        discount?: number;
    }>;
    settings: Record<string, string>;
}>();

function getInitials(name: string): string {
    return name
        .split(' ')
        .map((word) => word[0])
        .join('')
        .slice(0, 2)
        .toUpperCase();
}

function discountBadge(product: { discount?: number }): string {
    return product.discount ? `-${product.discount}%` : '';
}
</script>

<template>
    <Head
        :title="
            __('Textile accessories from the manufacturer') + ' | Mirum Textile'
        "
    />

    <!-- Hero -->
    <section class="relative overflow-hidden bg-[#04452c] text-white">
        <!-- Large logo watermark -->
        <div class="pointer-events-none absolute right-[50px] top-[-114px]">
            <img
                src="/images/logo.jpeg"
                alt=""
                class="h-[500px] w-[500px] object-contain sm:h-[600px] sm:w-[600px] lg:h-[800px] lg:w-[800px]"
            />
        </div>

        <!-- Content -->
        <div class="relative mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:py-28">
            <div class="max-w-2xl">
                <!-- Logo badge -->
                <div class="mb-6 inline-flex items-center gap-3 rounded-xl bg-white/10 px-4 py-2 backdrop-blur-sm">
                    <img
                        src="/images/logo.jpeg"
                        alt="Mirum Textile"
                        class="h-8 w-8 rounded object-contain"
                    />
                    <span class="text-sm font-medium text-yellow-300">
                        Mirum Textile
                    </span>
                </div>

                <h1 class="mb-4 text-4xl leading-tight font-bold sm:text-5xl">
                    {{ __('Textile accessories from the manufacturer') }}
                </h1>
                <p class="mb-8 text-lg text-white/80">
                    {{
                        __(
                            'Production of flat-knit collars and cuffs, cords, ribbons, finished knitwear and scarves.',
                        )
                    }}
                </p>
                <div class="flex flex-wrap gap-3">
                    <Button
                        size="lg"
                        class="bg-yellow-400 text-[#0a4d3c] hover:bg-yellow-300"
                        as-child
                    >
                        <Link :href="catalog()">
                            {{ __('View Products') }}
                            <ArrowRight class="ml-2 h-4 w-4" />
                        </Link>
                    </Button>
                    <Button
                        size="lg"
                        variant="outline"
                        class="border-white bg-transparent text-white hover:bg-white hover:text-[#0a4d3c]"
                        as-child
                    >
                        <Link :href="contact()">{{
                            __('Request a Quote')
                        }}</Link>
                    </Button>
                </div>
            </div>
        </div>

        <!-- Subtle bottom edge fade -->
        <div class="absolute bottom-0 h-16 w-full bg-gradient-to-t from-[#0a4d3c]/40 to-transparent"></div>
    </section>

    <!-- Advantages -->
    <section class="border-b bg-white py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-5">
                <div class="flex items-start gap-3">
                    <div
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50"
                    >
                        <Check class="h-5 w-5 text-blue-600" />
                    </div>
                    <div>
                        <div class="font-semibold text-slate-900">
                            {{ __('Product Quality') }}
                        </div>
                        <div class="text-sm text-slate-500">
                            {{ __('Quality control at every stage') }}
                        </div>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50"
                    >
                        <Package class="h-5 w-5 text-blue-600" />
                    </div>
                    <div>
                        <div class="font-semibold text-slate-900">
                            {{ __('Wide Assortment') }}
                        </div>
                        <div class="text-sm text-slate-500">
                            {{ __('5+ categories, 100+ items') }}
                        </div>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50"
                    >
                        <Palette class="h-5 w-5 text-blue-600" />
                    </div>
                    <div>
                        <div class="font-semibold text-slate-900">
                            {{ __('Custom Projects') }}
                        </div>
                        <div class="text-sm text-slate-500">
                            {{ __('Logo, colors, sizes') }}
                        </div>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50"
                    >
                        <Clock class="h-5 w-5 text-blue-600" />
                    </div>
                    <div>
                        <div class="font-semibold text-slate-900">
                            {{ __('Honest Deadlines') }}
                        </div>
                        <div class="text-sm text-slate-500">
                            {{ __('We deliver on time') }}
                        </div>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50"
                    >
                        <DollarSign class="h-5 w-5 text-blue-600" />
                    </div>
                    <div>
                        <div class="font-semibold text-slate-900">
                            {{ __('Reasonable Prices') }}
                        </div>
                        <div class="text-sm text-slate-500">
                            {{ __('Wholesale from manufacturer') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories -->
    <section class="bg-slate-50 py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <h2 class="mb-10 text-center text-3xl font-bold text-slate-900">
                {{ __('Our Products') }}
            </h2>
            <div
                class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5"
            >
                <Link
                    v-for="cat in categories"
                    :key="cat.slug"
                    :href="catalog.url() + '?category=' + cat.slug"
                    class="group block"
                >
                    <Card
                        class="h-full overflow-hidden transition-all hover:-translate-y-1 hover:shadow-lg"
                    >
                        <div
                            class="flex h-32 items-center justify-center bg-blue-50"
                        >
                            <Factory
                                class="h-12 w-12 text-blue-300 transition-colors group-hover:text-blue-500"
                            />
                        </div>
                        <CardHeader class="p-4 pb-2">
                            <CardTitle class="text-base">{{
                                cat.name
                            }}</CardTitle>
                        </CardHeader>
                        <CardContent class="p-4 pt-0">
                            <p class="text-sm text-slate-500">
                                {{ cat.itemCount }} {{ __('items') }}
                            </p>
                            <p
                                class="mt-2 text-sm font-medium text-blue-700 group-hover:underline"
                            >
                                {{ __('Go to \u2192') }}
                            </p>
                        </CardContent>
                    </Card>
                </Link>
            </div>
        </div>
    </section>

    <!-- New Products -->
    <section class="bg-white py-16" v-if="newProducts.length">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="mb-10 flex items-center justify-between">
                <h2 class="text-3xl font-bold text-slate-900">
                    {{ __('New Products') }}
                </h2>
                <Link
                    :href="catalog()"
                    class="text-sm font-medium text-blue-700 hover:underline"
                >
                    {{ __('All Products \u2192') }}
                </Link>
            </div>
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <Link
                    v-for="product in newProducts"
                    :key="product.id"
                    :href="`/products/${product.slug}`"
                    class="group block"
                >
                    <Card
                        class="h-full overflow-hidden transition-all hover:-translate-y-1 hover:shadow-lg"
                    >
                        <div
                            class="relative flex aspect-[3/2] items-center justify-center bg-slate-100"
                        >
                            <div
                                v-if="product.image"
                                class="flex h-full w-full items-center justify-center"
                            >
                                <img
                                    :src="product.image"
                                    :alt="product.name"
                                    class="h-full w-full object-cover"
                                />
                            </div>
                            <div
                                v-else
                                class="flex h-20 w-20 items-center justify-center rounded-full bg-blue-100 text-2xl font-bold text-blue-600"
                            >
                                {{ getInitials(product.name) }}
                            </div>
                            <Badge
                                v-if="product.isNew"
                                class="absolute top-2 left-2 bg-emerald-600 text-white"
                            >
                                {{ __('New') }}
                            </Badge>
                        </div>
                        <CardHeader class="p-4 pb-0">
                            <CardTitle class="text-base">{{
                                product.name
                            }}</CardTitle>
                        </CardHeader>
                        <CardContent class="p-4 pt-2">
                            <p class="text-xs text-slate-500">
                                {{ __('SKU:') }} {{ product.sku }}
                            </p>
                            <p
                                class="mt-2 text-sm font-medium text-blue-700 group-hover:underline"
                            >
                                {{ __('Details \u2192') }}
                            </p>
                        </CardContent>
                    </Card>
                </Link>
            </div>
        </div>
    </section>

    <!-- Discounts -->
    <section class="bg-slate-50 py-16" v-if="discountProducts.length">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="mb-10 flex items-center justify-between">
                <h2 class="text-3xl font-bold text-slate-900">
                    {{ __('Discounts') }}
                </h2>
                <Link
                    :href="catalog()"
                    class="text-sm font-medium text-blue-700 hover:underline"
                >
                    {{ __('All discounted products \u2192') }}
                </Link>
            </div>
            <div
                class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
            >
                <Link
                    v-for="product in discountProducts"
                    :key="product.id"
                    :href="`/products/${product.slug}`"
                    class="group block"
                >
                    <Card
                        class="h-full overflow-hidden transition-all hover:-translate-y-1 hover:shadow-lg"
                    >
                        <div
                            class="relative flex aspect-[3/2] items-center justify-center bg-slate-100"
                        >
                            <div
                                v-if="product.image"
                                class="flex h-full w-full items-center justify-center"
                            >
                                <img
                                    :src="product.image"
                                    :alt="product.name"
                                    class="h-full w-full object-cover"
                                />
                            </div>
                            <div
                                v-else
                                class="flex h-20 w-20 items-center justify-center rounded-full bg-blue-100 text-2xl font-bold text-blue-600"
                            >
                                {{ getInitials(product.name) }}
                            </div>
                            <div
                                class="absolute top-2 right-2 flex h-12 w-12 items-center justify-center rounded-full bg-orange-600 text-xs font-bold text-white"
                            >
                                -{{ product.discount }}%
                            </div>
                        </div>
                        <CardHeader class="p-4 pb-0">
                            <CardTitle class="text-base">{{
                                product.name
                            }}</CardTitle>
                        </CardHeader>
                        <CardContent class="p-4 pt-2">
                            <p class="text-xs text-slate-500">
                                {{ __('SKU:') }} {{ product.sku }}
                            </p>
                            <p
                                class="mt-2 text-sm font-medium text-blue-700 group-hover:underline"
                            >
                                {{ __('Details \u2192') }}
                            </p>
                        </CardContent>
                    </Card>
                </Link>
            </div>
        </div>
    </section>

    <!-- About teaser -->
    <section class="bg-white py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="grid items-center gap-12 lg:grid-cols-2">
                <div
                    class="flex aspect-video items-center justify-center rounded-xl bg-slate-100"
                >
                    <Factory class="h-24 w-24 text-slate-300" />
                </div>
                <div>
                    <h2 class="mb-4 text-3xl font-bold text-slate-900">
                        {{ __('About the Company') }}
                    </h2>
                    <p class="mb-4 text-slate-600">
                        {{
                            __(
                                'MIRUM TEXTILE is a textile company operating in Uzbekistan, specializing in knitted textile accessories and auxiliary products for garment manufacturers. We produce rib collars, rib cuffs, drawcords, webbing tape, bias tape, and custom knitted products.',
                            )
                        }}
                    </p>
                    <p class="mb-6 text-slate-600">
                        {{
                            __(
                                'We use high-quality raw materials and modern equipment, fulfilling orders of any size \u2014 from small batches to large-scale production \u2014 for both domestic and export markets.',
                            )
                        }}
                    </p>
                    <Button class="bg-blue-700 hover:bg-blue-800" as-child>
                        <Link :href="'/about'">{{
                            __('Learn more about the company')
                        }}</Link>
                    </Button>
                </div>
            </div>
        </div>
    </section>
</template>

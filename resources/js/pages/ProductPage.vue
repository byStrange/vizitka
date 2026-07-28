<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useTranslations } from '@/composables/useTranslations';
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { ArrowRight, Factory, Phone, MessageCircle } from 'lucide-vue-next';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { home, catalog, contact } from '@/routes';

defineOptions({ layout: PublicLayout });
const { __ } = useTranslations();

const props = defineProps<{
    product: {
        id: number;
        slug: string;
        name: string;
        sku: string;
        category: {
            id: number
            name: string
            slug: string
        };
        description: string;
        image?: string;
        specs: Record<string, string>;
        moq: string;
        leadTime: string;
        customization: boolean;
        isNew: boolean;
        discount?: number;
        gallery?: string[];
    } | null;
    relatedProducts: Array<{
        id: number;
        slug: string;
        name: string;
        sku: string;
        category: {
            id: number
            name: string
            slug: string
        };
        description: string;
        image?: string;
        moq: string;
        leadTime: string;
        customization: boolean;
        isNew: boolean;
        discount?: number;
        specs: Record<string, string>;
    }>;
}>();

const categorySlug = computed(() => props.product?.category.slug);

function getInitials(name: string): string {
    return name
        .split(' ')
        .map((word) => word[0])
        .join('')
        .slice(0, 2)
        .toUpperCase();
}
</script>

<template>
    <Head
        :title="
            product
                ? `${product.name} \u2014 ${__('SKU:')} ${product.sku} | Mirum Textile`
                : `${__('Product Not Found')} | Mirum Textile`
        "
    />

    <div v-if="product" class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-6 flex items-center gap-2 text-sm text-slate-500">
            <Link :href="home()" class="hover:text-slate-900 hover:underline">
                {{ __('Home') }}
            </Link>
            <span>/</span>
            <Link
                :href="catalog.url() + (categorySlug ? '?category=' + categorySlug : '')"
                class="hover:text-slate-900 hover:underline"
            >
                {{ product.category.name }}
            </Link>
            <span>/</span>
            <span class="text-slate-900">{{ product.name }}</span>
        </nav>

        <!-- Product detail -->
        <div class="grid gap-8 lg:grid-cols-2">
            <!-- Image -->
            <div class="space-y-4">
                <div
                    class="flex aspect-square items-center justify-center rounded-xl bg-slate-100"
                >
                    <img
                        v-if="product.image"
                        :src="product.image"
                        :alt="product.name"
                        class="h-full w-full rounded-xl object-cover"
                    />
                    <div
                        v-else
                        class="flex h-32 w-32 items-center justify-center rounded-full bg-blue-100 text-5xl font-bold text-blue-600"
                    >
                        {{ getInitials(product.name) }}
                    </div>
                </div>
                <div
                    v-if="product.gallery && product.gallery.length"
                    class="grid grid-cols-4 gap-2"
                >
                    <button
                        v-for="img in product.gallery"
                        :key="img"
                        class="flex items-center justify-center rounded-lg bg-slate-100 p-1"
                        @click="product.image = img"
                    >
                        <img
                            :src="img"
                            :alt="product.name"
                            class="h-20 w-full rounded-lg object-cover"
                        />
                    </button>
                </div>
            </div>

            <!-- Info -->
            <div class="flex flex-col gap-6">
                <div>
                    <div class="mb-2 flex items-center gap-2">
                        <Badge variant="secondary">{{ product.category.name }}</Badge>
                        <Badge v-if="product.isNew" class="bg-emerald-600 text-white">
                            {{ __('New') }}
                        </Badge>
                        <Badge
                            v-if="product.discount"
                            class="bg-orange-600 text-white"
                        >
                            -{{ product.discount }}%
                        </Badge>
                    </div>
                    <h1 class="text-3xl font-bold text-slate-900">
                        {{ product.name }}
                    </h1>
                </div>

                <p class="text-slate-600">{{ product.description }}</p>

                <!-- Specs table -->
                <div>
                    <h3 class="mb-3 text-sm font-semibold text-slate-900">
                        {{ __('Technical Specifications') }}
                    </h3>
                    <div class="divide-y rounded-md border">
                        <div
                            v-for="[key, value] in Object.entries(product.specs)"
                            :key="key"
                            class="flex items-center justify-between px-4 py-3"
                        >
                            <span class="text-sm font-medium text-slate-600">
                                {{ key }}
                            </span>
                            <span class="text-sm text-slate-900">
                                {{ value }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- CTAs -->
                <div class="space-y-3">
                    <Button class="w-full bg-blue-700 hover:bg-blue-800" size="lg" as-child>
                        <Link :href="contact.url() + '?product=' + product.slug">
                            {{ __('Request a Commercial Proposal') }}
                            <ArrowRight class="ml-2 h-4 w-4" />
                        </Link>
                    </Button>
                    <div class="flex gap-3">
                        <Button
                            variant="outline"
                            class="flex-1"
                            as-child
                        >
                            <a href="https://wa.me/998712539540" target="_blank">
                                <MessageCircle class="mr-2 h-4 w-4" />
                                {{ __('WhatsApp') }}
                            </a>
                        </Button>
                        <Button variant="outline" class="flex-1" as-child>
                            <a href="tel:+998712539540">
                                <Phone class="mr-2 h-4 w-4" />
                                {{ __('Call') }}
                            </a>
                        </Button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related products -->
        <div v-if="relatedProducts.length" class="mt-16">
            <h2 class="mb-6 text-2xl font-bold text-slate-900">
                {{ __('Related Products') }}
            </h2>
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <Link
                    v-for="rp in relatedProducts"
                    :key="rp.id"
                    :href="`/products/${rp.slug}`"
                    class="group block"
                >
                    <Card
                        class="h-full overflow-hidden transition-all hover:-translate-y-1 hover:shadow-lg"
                    >
                        <div
                            class="flex aspect-3/2 items-center justify-center bg-slate-100"
                        >
                            <div
                                v-if="rp.image"
                                class="flex h-full w-full items-center justify-center"
                            >
                                <img
                                    :src="rp.image"
                                    :alt="rp.name"
                                    class="h-full w-full object-cover"
                                />
                            </div>
                            <div
                                v-else
                                class="flex h-16 w-16 items-center justify-center rounded-full bg-blue-100 text-xl font-bold text-blue-600"
                            >
                                {{ getInitials(rp.name) }}
                            </div>
                        </div>
                        <CardHeader class="p-4 pb-0">
                            <CardTitle class="text-sm">{{ rp.name }}</CardTitle>
                        </CardHeader>
                    </Card>
                </Link>
            </div>
        </div>
    </div>

    <!-- Not found -->
    <div
        v-else
        class="mx-auto max-w-7xl px-4 py-20 text-center sm:px-6 lg:px-8"
    >
        <Factory class="mx-auto mb-4 h-16 w-16 text-slate-300" />
        <h1 class="text-2xl font-bold text-slate-900">{{ __('Product Not Found') }}</h1>
        <p class="mt-2 text-slate-500">
            {{ __('The product may have been removed or moved to another category.') }}
        </p>
        <Button class="mt-6 bg-blue-700 hover:bg-blue-800" as-child>
            <Link :href="catalog()">{{ __('Back to Catalog') }}</Link>
        </Button>
    </div>
</template>

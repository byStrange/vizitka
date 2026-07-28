<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Card, CardContent } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Factory,
    Users,
    Award,
    Globe,
    ArrowRight,
    Check,
    Target,
    Handshake,
    Star,
} from 'lucide-vue-next';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { useTranslations } from '@/composables/useTranslations';
import { contact, catalog } from '@/routes';

defineOptions({ layout: PublicLayout });
const { __ } = useTranslations();

const stats = [
    { value: '4+', labelKey: 'Years on the market', icon: Factory },
    { value: '5+', labelKey: 'Product categories', icon: Award },
];

const products = [
    'Rib Collars',
    'Rib Cuffs',
    'Drawcords and Ties',
    'Webbing Tape',
    'Bias Tape',
    'Custom knitted textile products',
];

const advantages = [
    'High-quality raw materials',
    'Modern production equipment',
    'Custom manufacturing to client requirements (color, size, and design)',
    'Fulfillment of both small and large orders',
    'Production for domestic market and export',
];

const partners = [
    'Garment factories',
    'Sportswear manufacturers',
    'Children\u2019s clothing manufacturers',
    'Uniform and workwear manufacturers',
    'Fashion brands',
];

interface Client {
    name: string;
    logo?: string;
    dark?: boolean;
}

const clients: Client[] = [
    { name: 'ARTATEX', logo: '/images/clients/artatex.png', dark: true },
    { name: 'BFM GROUP JCS', logo: '/images/clients/bfmgroup.png' },
    { name: 'BUKA BOXING SPORTSWEAR', logo: '/images/clients/buka.svg' },
    {
        name: 'PRONTO TEXTILE GROUP',
        logo: '/images/clients/prontotextile.png',
        dark: true,
    },
    { name: 'SULTON STYLES', logo: '/images/clients/sultonstyles.png' },
    { name: 'AKSARTEKS INDUSTRY' },
    { name: 'ARDENTEX' },
    { name: 'BHR OUTPUT' },
    { name: 'CHELEBI TEXTILE' },
    { name: 'DELUXE PRODUCTION' },
    { name: 'EAST WEST TEX STROY' },
    { name: 'INNOVATIVE APPAREL' },
    { name: 'FIRST ANDIJAN TEXTILE' },
    { name: 'GRAND EMPIRE MARKET' },
    { name: 'GURLAN GLOBAL TEKS' },
    { name: 'HEAT ANSOR' },
    { name: 'HILALE TEXTILES' },
    { name: 'LINAS-GROUP' },
    { name: 'MASIS TEKS' },
    { name: 'MAYS BLUE FASHION' },
    { name: 'POYAFZALCHI' },
    { name: 'QUVA TEKSTIL' },
    { name: 'SAAS TEKS INVEST' },
    { name: 'SHOKIRJON FAYZ NUR' },
    { name: 'Soft White Gold Cotton Tekstil' },
    { name: 'VERO MODA' },
    { name: 'ZARAFSHON TEKSTIL' },
];

const row1 = clients.slice(0, 14);
const row2 = clients.slice(14);
</script>

<template>
    <Head :title="__('About the Company') + ' \u2014 Mirum Textile'" />

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-12 text-center">
            <Badge variant="outline" class="mb-4 border-blue-400 text-blue-600">
                {{ __('About the Company') }}
            </Badge>
            <h1 class="mb-4 text-4xl font-bold text-slate-900">
                MIRUM TEXTILE
            </h1>
            <p class="mx-auto max-w-2xl text-lg text-slate-600">
                {{
                    __(
                        'MIRUM TEXTILE is a textile company operating in Uzbekistan, specializing in the production of knitted textile accessories and auxiliary products for garment manufacturers.',
                    )
                }}
            </p>
        </div>

        <!-- Stats -->
        <div class="mb-16 grid gap-4 sm:grid-cols-2  justify-center">
            <Card
                v-for="stat in stats"
                :key="stat.labelKey"
                class="text-center"
            >
                <CardContent class="py-6">
                    <component
                        :is="stat.icon"
                        class="mx-auto mb-3 h-8 w-8 text-blue-600"
                    />
                    <div class="text-3xl font-bold text-slate-900">
                        {{ stat.value }}
                    </div>
                    <div class="text-sm text-slate-500">
                        {{ __(stat.labelKey) }}
                    </div>
                </CardContent>
            </Card>
        </div>

        <!-- Main Products -->
        <div class="mb-16">
            <h2 class="mb-8 text-center text-2xl font-bold text-slate-900">
                {{ __('Main Products') }}
            </h2>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div
                    v-for="product in products"
                    :key="product"
                    class="flex items-start gap-3 rounded-lg border bg-white p-4"
                >
                    <Check class="mt-0.5 h-5 w-5 shrink-0 text-emerald-600" />
                    <span class="text-slate-700">{{ __(product) }}</span>
                </div>
            </div>
        </div>

        <!-- Advantages -->
        <div class="mb-16 rounded-2xl bg-slate-50 p-8 sm:p-12">
            <h2 class="mb-8 text-center text-2xl font-bold text-slate-900">
                {{ __('Our Advantages') }}
            </h2>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div
                    v-for="adv in advantages"
                    :key="adv"
                    class="flex items-start gap-3 rounded-lg bg-white p-4 shadow-sm"
                >
                    <Star class="mt-0.5 h-5 w-5 shrink-0 text-blue-600" />
                    <span class="text-slate-700">{{ __(adv) }}</span>
                </div>
            </div>
        </div>

        <!-- Partnership -->
        <div class="mb-16">
            <h2 class="mb-8 text-center text-2xl font-bold text-slate-900">
                {{ __('Partnership Directions') }}
            </h2>
            <p class="mb-6 text-center text-slate-600">
                {{ __('MIRUM TEXTILE collaborates with:') }}
            </p>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div
                    v-for="p in partners"
                    :key="p"
                    class="flex items-start gap-3 rounded-lg border bg-white p-4"
                >
                    <Handshake class="mt-0.5 h-5 w-5 shrink-0 text-blue-600" />
                    <span class="text-slate-700">{{ __(p) }}</span>
                </div>
            </div>
        </div>

        <!-- Goal -->
        <div class="mb-16">
            <div
                class="mx-auto max-w-3xl rounded-2xl bg-slate-900 p-8 text-center text-white sm:p-12"
            >
                <Target class="mx-auto mb-4 h-10 w-10 text-blue-400" />
                <h2 class="mb-4 text-2xl font-bold">{{ __('Our Goal') }}</h2>
                <p class="text-slate-300">
                    {{
                        __(
                            'To provide clients with high-quality, reliable, and modern textile accessories, and to produce competitive products for domestic and international markets.',
                        )
                    }}
                </p>
            </div>
        </div>
    </div>

    <!-- Clients — full-width marquee -->
    <div class="mb-16 overflow-hidden bg-slate-50 py-12">
        <h2 class="mb-8 text-center text-2xl font-bold text-slate-900">
            {{ __('Our Clients') }}
        </h2>
        <div class="marquee-row mb-4">
            <div class="marquee-track marquee-track--left">
                <template v-for="client in row1" :key="client.name">
                    <div
                        :class="[
                            'marquee-card',
                            { 'marquee-card--dark': client.dark },
                        ]"
                    >
                        <img
                            v-if="client.logo"
                            :src="client.logo"
                            :alt="client.name"
                            class="max-h-full max-w-full object-contain"
                        />
                        <span
                            v-else
                            class="text-xs font-medium text-slate-600"
                            >{{ client.name }}</span
                        >
                    </div>
                </template>
                <template v-for="client in row1" :key="'dup-' + client.name">
                    <div
                        :class="[
                            'marquee-card',
                            { 'marquee-card--dark': client.dark },
                        ]"
                    >
                        <img
                            v-if="client.logo"
                            :src="client.logo"
                            :alt="client.name"
                            class="max-h-full max-w-full object-contain"
                        />
                        <span
                            v-else
                            class="text-xs font-medium text-slate-600"
                            >{{ client.name }}</span
                        >
                    </div>
                </template>
            </div>
        </div>
        <div class="marquee-row">
            <div class="marquee-track marquee-track--right">
                <template v-for="client in row2" :key="client.name">
                    <div
                        :class="[
                            'marquee-card',
                            { 'marquee-card--dark': client.dark },
                        ]"
                    >
                        <img
                            v-if="client.logo"
                            :src="client.logo"
                            :alt="client.name"
                            class="max-h-full max-w-full object-contain"
                        />
                        <span
                            v-else
                            class="text-xs font-medium text-slate-600"
                            >{{ client.name }}</span
                        >
                    </div>
                </template>
                <template v-for="client in row2" :key="'dup-' + client.name">
                    <div
                        :class="[
                            'marquee-card',
                            { 'marquee-card--dark': client.dark },
                        ]"
                    >
                        <img
                            v-if="client.logo"
                            :src="client.logo"
                            :alt="client.name"
                            class="max-h-full max-w-full object-contain"
                        />
                        <span
                            v-else
                            class="text-xs font-medium text-slate-600"
                            >{{ client.name }}</span
                        >
                    </div>
                </template>
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- CTA -->
        <div
            class="rounded-2xl bg-slate-900 p-8 text-center text-white sm:p-12"
        >
            <h2 class="mb-4 text-2xl font-bold">
                {{ __('Want to become our partner?') }}
            </h2>
            <p class="mb-6 text-slate-300">
                {{
                    __(
                        'Contact us \u2014 we will prepare a commercial proposal and product samples.',
                    )
                }}
            </p>
            <div class="flex flex-wrap justify-center gap-3">
                <Button
                    class="bg-blue-600 hover:bg-blue-700"
                    size="lg"
                    as-child
                >
                    <Link :href="contact()">
                        {{ __('Request a Quote') }}
                        <ArrowRight class="ml-2 h-4 w-4" />
                    </Link>
                </Button>
                <Button
                    variant="outline"
                    class="border-white text-white hover:bg-white hover:text-slate-900"
                    size="lg"
                    as-child
                >
                    <Link :href="catalog()">{{ __('View Products') }}</Link>
                </Button>
            </div>
        </div>
    </div>
</template>

<style scoped>
.marquee-row {
    width: 100%;
    overflow: hidden;
}
.marquee-track {
    display: flex;
    gap: 1rem;
    width: max-content;
}
.marquee-track--left {
    animation: marquee-left 40s linear infinite;
}
.marquee-track--right {
    animation: marquee-right 40s linear infinite;
}
.marquee-card {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 4rem;
    width: 10rem;
    flex-shrink: 0;
    border-radius: 0.5rem;
    background-color: white;
    padding: 0.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}
.marquee-card--dark {
    background-color: #1e293b;
}
@keyframes marquee-left {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-50%);
    }
}
@keyframes marquee-right {
    0% {
        transform: translateX(-50%);
    }
    100% {
        transform: translateX(0);
    }
}
</style>

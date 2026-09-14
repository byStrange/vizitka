<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useTranslations } from '@/composables/useTranslations';
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Badge } from '@/components/ui/badge';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import {
    Phone,
    Mail,
    MapPin,
    Send,
    Clock,
    Factory,
} from 'lucide-vue-next';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { home } from '@/routes';

defineOptions({ layout: PublicLayout });
const { __ } = useTranslations();

const props = defineProps<{
    product?: string;
    categories: Array<{ slug: string; name: string }>;
}>();

const page = usePage();
const flashSuccess = computed(() => (page.props.flash as Record<string, string>)?.success);
const settings = computed(() => (page.props.sharedSettings as Record<string, string>) ?? {});

const form = useForm({
    name: '',
    company: '',
    phone: '',
    email: '',
    product_interest: props.product ?? '',
    quantity: '',
    message: '',
});

function submit() {
    form.post('/contact', {
        onSuccess: () => form.reset(),
    });
}
</script>

<template>
    <Head :title="__('Contacts') + ' \u2014 Mirum Textile'" />

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-6 flex items-center gap-2 text-sm text-slate-500">
            <Link :href="home()" class="hover:text-slate-900 hover:underline"
                >{{ __('Home') }}</Link
            >
            <span>/</span>
            <span class="text-slate-900">{{ __('Contacts') }}</span>
        </nav>

        <h1 class="mb-8 text-3xl font-bold text-slate-900">{{ __('Contacts') }}</h1>

        <div class="grid gap-8 lg:grid-cols-3">
            <!-- Contact info sidebar -->
            <div class="space-y-6">
                <Card>
                    <CardHeader class="pb-3">
                        <CardTitle class="text-base">{{ __('Contact Information') }}</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="flex items-start gap-3">
                            <MapPin
                                class="mt-0.5 h-5 w-5 shrink-0 text-blue-600"
                            />
                            <div>
                                <p class="font-medium text-slate-900">
                                    Mirum Textile
                                </p>
                                <p class="text-sm text-slate-500">
                                    {{ settings.address || __('Tashkent, Uzbekistan') }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <Phone class="h-5 w-5 text-blue-600" />
                            <a
                                :href="'tel:' + (settings.phone || '+998712539540')"
                                class="font-medium text-slate-900 hover:text-blue-700"
                            >
                                {{ settings.phone || '+(99871) 253 95 40' }}
                            </a>
                        </div>

                        <div
                            v-if="settings.phone_secondary"
                            class="flex items-center gap-3"
                        >
                            <Phone class="h-5 w-5 text-blue-600" />
                            <a
                                :href="'tel:' + settings.phone_secondary"
                                class="font-medium text-slate-900 hover:text-blue-700"
                            >
                                {{ settings.phone_secondary }}
                            </a>
                        </div>
                        <div class="flex items-center gap-3">
                            <Mail class="h-5 w-5 text-blue-600" />
                            <a
                                :href="'mailto:' + (settings.email || 'info@mirumtextile.uz')"
                                class="font-medium text-slate-900 hover:text-blue-700"
                            >
                                {{ settings.email || 'info@mirumtextile.uz' }}
                            </a>
                        </div>
                        <div class="flex items-center gap-3">
                            <Clock class="h-5 w-5 text-blue-600" />
                            <span class="text-sm text-slate-500"
                                >{{ settings.working_hours || __('Mon\u2013Fri, 09:00\u201318:00') }}</span
                            >
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="pb-3">
                        <CardTitle class="text-base">{{ __('Quick Contact') }}</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-3">
                        <Button variant="outline" class="w-full" as-child>
                            <a :href="'tel:' + (settings.phone || '+998712539540')"
                                ><Phone class="mr-2 h-4 w-4" />{{ __('Call') }}</a
                            >
                        </Button>
                    </CardContent>
                </Card>

                <div
                    class="flex aspect-video items-center justify-center rounded-xl bg-slate-100"
                >
                    <MapPin class="h-16 w-16 text-slate-300" />
                </div>
            </div>

            <!-- Quote form -->
            <div class="lg:col-span-2">
                <Card v-if="!flashSuccess">
                    <CardHeader>
                        <CardTitle>{{ __('Request a commercial proposal') }}</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p class="mb-6 text-slate-600">
                            {{ __('Leave a request \u2014 our manager will contact you within one business day.') }}
                        </p>
                        <form
                            @submit.prevent="submit"
                            class="space-y-5"
                        >
                            <input type="hidden" name="_token" :value="$page.props.csrf_token" />
                            <div class="grid gap-5 sm:grid-cols-2">
                                <div class="space-y-2">
                                    <Label for="name">{{ __('Name *') }}</Label>
                                    <Input
                                        id="name"
                                        v-model="form.name"
                                        :placeholder="__('Your name')"
                                        required
                                    />
                                </div>
                                <div class="space-y-2">
                                    <Label for="company">{{ __('Company') }}</Label>
                                    <Input
                                        id="company"
                                        v-model="form.company"
                                        :placeholder="__('Company name')"
                                    />
                                </div>
                            </div>

                            <div class="grid gap-5 sm:grid-cols-2">
                                <div class="space-y-2">
                                    <Label for="phone">{{ __('Phone *') }}</Label>
                                    <Input
                                        id="phone"
                                        v-model="form.phone"
                                        type="tel"
                                        :placeholder="__('+998 XX XXX XX XX')"
                                        required
                                    />
                                    <div v-if="form.errors.phone" class="text-sm text-red-600">{{ form.errors.phone }}</div>
                                </div>
                                <div class="space-y-2">
                                    <Label for="email">{{ __('Email') }}</Label>
                                    <Input
                                        id="email"
                                        v-model="form.email"
                                        type="email"
                                        :placeholder="__('you@example.com')"
                                    />
                                    <div v-if="form.errors.email" class="text-sm text-red-600">{{ form.errors.email }}</div>
                                </div>
                            </div>

                            <div class="grid gap-5 sm:grid-cols-2">
                                <div class="space-y-2">
                                    <Label for="product">{{ __('Product of interest') }}</Label>
                                    <Select v-model="form.product_interest">
                                        <SelectTrigger>
                                            <SelectValue
                                                :placeholder="__('Select a category')"
                                            />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem
                                                v-for="cat in categories"
                                                :key="cat.slug"
                                                :value="cat.slug"
                                            >
                                                {{ cat.name }}
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                    <div v-if="form.errors.product_interest" class="text-sm text-red-600">{{ form.errors.product_interest }}</div>
                                </div>
                                <div class="space-y-2">
                                    <Label for="quantity">{{ __('Order volume') }}</Label>
                                    <Input
                                        id="quantity"
                                        v-model="form.quantity"
                                        :placeholder="__('e.g. 1000 pcs')"
                                    />
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="message">{{ __('Message') }}</Label>
                                <Textarea
                                    id="message"
                                    v-model="form.message"
                                    :placeholder="__('Describe your requirements, desired timeline and specifics...')"
                                />
                                <div v-if="form.errors.message" class="text-sm text-red-600">{{ form.errors.message }}</div>
                            </div>

                            <div class="flex items-start gap-2">
                                <input
                                    id="consent"
                                    type="checkbox"
                                    required
                                    class="mt-1 h-4 w-4 rounded border-slate-300 text-blue-700"
                                />
                                <Label for="consent" class="text-xs text-slate-500"
                                    >
                                    {{ __('I agree to the processing of personal data in accordance with the privacy policy') }}
                                </Label>
                            </div>

                            <Button
                                type="submit"
                                class="bg-blue-700 hover:bg-blue-800"
                                size="lg"
                                :disabled="form.processing"
                            >
                                <Send class="mr-2 h-4 w-4" />
                                {{ form.processing ? __('Sending...') : __('Send Request') }}
                            </Button>
                        </form>
                    </CardContent>
                </Card>

                <Card v-else class="border-emerald-200 bg-emerald-50">
                    <CardContent class="py-12 text-center">
                        <div
                            class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100"
                        >
                            <Send class="h-8 w-8 text-emerald-600" />
                        </div>
                        <h3 class="mb-2 text-xl font-semibold text-emerald-900"
                            >{{ __('Request Sent!') }}</h3
                        >
                        <p class="text-emerald-700"
                            >
                            {{ __('We have received your request and will contact you within one business day.') }}
                        </p>
                    </CardContent>
                </Card>
            </div>
        </div>
    </div>
</template>

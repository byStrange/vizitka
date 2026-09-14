<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"  @class(['dark' => ($appearance ?? 'light') == 'dark'])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- Inline script to apply the saved dark mode preference immediately --}}
        <script>
            (function() {
                const appearance = '{{ $appearance ?? "light" }}';

                if (appearance === 'dark') {
                    document.documentElement.classList.add('dark');
                }
            })();
        </script>

        {{-- Inline style to set the HTML background color based on our theme in app.css --}}
        <style>
            html {
                background-color: oklch(1 0 0);
            }

            html.dark {
                background-color: oklch(0.145 0 0);
            }
        </style>

        <link rel="icon" href="/favicon.ico?v=2" sizes="any">
        <link rel="icon" href="/favicon.svg?v=2" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png?v=2">

        @fonts

        @vite(['resources/css/app.css', 'resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
        
        {{-- Default SEO Meta Tags (Inertia head may override these for JS clients, but crawlers see this first) --}}
        <meta name="description" content="Mirum Textile (Mirum Tekstil) - производство и продажа трикотажной фурнитуры от производителя. Производство воротников, манжет, шнуров, тесьмы и готового трикотажа в Узбекистане.">
        <meta name="keywords" content="mirum textile, mirum tekstil, mirum textil, трикотажная фурнитура, воротники, манжеты, шнуры, тесьма, узбекистан, производство текстиля">
        
        {{-- Open Graph / Facebook --}}
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="{{ config('app.name', 'Mirum Textile') }}">
        <meta property="og:description" content="Mirum Textile (Mirum Tekstil) - производство и продажа трикотажной фурнитуры от производителя в Узбекистане.">
        <meta property="og:image" content="{{ url('/images/logo.jpeg') }}">

        <x-inertia::head>
            <title>{{ config('app.name', 'Mirum Textile') }}</title>
        </x-inertia::head>
    </head>
    <body class="font-sans antialiased">
        <x-inertia::app />
    </body>
</html>

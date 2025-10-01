<!DOCTYPE html>
<html lang="en">
    <head>
        @include('partials.meta')

        @vite(['resources/css/app.css'])

        @stack('head')
    </head>

    <body class="font-sans overflow-y-scroll bg-zinc-100">
        <div class="max-w-3xl mx-auto p-8">
            <main role="main">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>

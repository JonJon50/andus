<header class="border-b bg-white">
    <nav class="mx-auto max-w-6xl px-6">
        <div class="flex items-center justify-between py-4">
            <a href="{{ route('home') }}" class="text-xl font-black tracking-tight text-gray-900">
                AndUs LLC
            </a>

            <div class="hidden items-center gap-7 text-sm font-semibold text-gray-900 md:flex">
                <a href="{{ route('home') }}" class="transition hover:text-blue-600">
                    Home
                </a>

                <a href="{{ route('about') }}" class="transition hover:text-blue-600">
                    About
                </a>

                <a href="{{ route('services') }}" class="transition hover:text-blue-600">
                    Services
                </a>

                <a href="{{ route('projects') }}" class="transition hover:text-blue-600">
                    Projects
                </a>

                <a href="{{ route('contact') }}" class="transition hover:text-blue-600">
                    Contact
                </a>
            </div>

            <label for="mobile-menu" class="cursor-pointer rounded border px-3 py-2 text-sm font-semibold text-gray-900 md:hidden">
                Menu
            </label>
        </div>

        <input id="mobile-menu" type="checkbox" class="peer hidden">

        <div class="hidden border-t py-4 peer-checked:grid md:hidden">
            <div class="grid gap-3 text-sm font-semibold text-gray-900">
                <a href="{{ route('home') }}" class="rounded px-3 py-2 transition hover:bg-gray-100">
                    Home
                </a>

                <a href="{{ route('about') }}" class="rounded px-3 py-2 transition hover:bg-gray-100">
                    About
                </a>

                <a href="{{ route('services') }}" class="rounded px-3 py-2 transition hover:bg-gray-100">
                    Services
                </a>

                <a href="{{ route('projects') }}" class="rounded px-3 py-2 transition hover:bg-gray-100">
                    Projects
                </a>

                <a href="{{ route('contact') }}" class="rounded bg-gray-900 px-3 py-2 text-white transition hover:bg-black">
                    Contact
                </a>
            </div>
        </div>
    </nav>
</header>
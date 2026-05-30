<footer class="mt-16 border-t bg-white">
    <div class="mx-auto max-w-6xl px-6 py-10">
        <div class="grid gap-8 md:grid-cols-3">
            <div>
                <h2 class="text-lg font-black text-gray-900">
                    AndUs LLC
                </h2>

                <p class="mt-3 max-w-sm text-sm leading-6 text-gray-600">
                    Practical websites, automation workflows, and data systems built for growing businesses.
                </p>
            </div>

            <div>
                <h3 class="text-sm font-bold uppercase tracking-widest text-gray-900">
                    Quick Links
                </h3>

                <div class="mt-4 grid gap-2 text-sm text-gray-600">
                    <a href="{{ route('home') }}" class="transition hover:text-gray-900">
                        Home
                    </a>

                    <a href="{{ route('about') }}" class="transition hover:text-gray-900">
                        About
                    </a>

                    <a href="{{ route('services') }}" class="transition hover:text-gray-900">
                        Services
                    </a>

                    <a href="{{ route('projects') }}" class="transition hover:text-gray-900">
                        Projects
                    </a>

                    <a href="{{ route('contact') }}" class="transition hover:text-gray-900">
                        Contact
                    </a>
                </div>
            </div>

            <div>
                <h3 class="text-sm font-bold uppercase tracking-widest text-gray-900">
                    Contact
                </h3>

                <div class="mt-4 grid gap-2 text-sm text-gray-600">
                    <p>
                        Have a project in mind?
                    </p>

                    <a href="{{ route('contact') }}" class="font-semibold text-blue-700 transition hover:text-blue-900">
                        Start a Project
                    </a>

                    <p class="text-gray-500">
                        Websites • Automation • Data Systems
                    </p>
                </div>
            </div>
        </div>

        <div class="mt-10 border-t pt-6 text-center text-sm text-gray-500">
            &copy; {{ date('Y') }} AndUs LLC. All rights reserved.
        </div>
    </div>
</footer>
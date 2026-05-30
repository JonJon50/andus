@extends('layouts.app')

@section('content')
    <section class="bg-white">
        <div class="mx-auto max-w-6xl px-6 py-20">
            <div class="grid items-center gap-12 md:grid-cols-2">
                <div>
                    <p class="mb-4 text-sm font-bold uppercase tracking-widest text-blue-600">
                        About AndUs
                    </p>

                    <h1 class="text-4xl font-black leading-tight text-gray-900 md:text-5xl">
                        Building practical digital systems for businesses that want to grow.
                    </h1>

                    <p class="mt-6 text-lg leading-8 text-gray-600">
                        AndUs LLC helps small businesses, entrepreneurs, and organizations build websites,
                        automation workflows, and database-driven systems that make daily operations easier.
                    </p>

                    <p class="mt-5 text-lg leading-8 text-gray-600">
                        Our focus is simple: create clean, reliable, and practical technology that supports
                        real business needs without overcomplicating the process.
                    </p>
                </div>

                <div class="rounded-2xl border bg-gray-50 p-8 shadow-sm">
                    <h2 class="text-2xl font-bold text-gray-900">
                        What AndUs Focuses On
                    </h2>

                    <div class="mt-6 grid gap-4">
                        <div class="rounded-xl bg-white p-4 shadow-sm">
                            Business websites that clearly present services and build trust.
                        </div>

                        <div class="rounded-xl bg-white p-4 shadow-sm">
                            Custom web applications for internal tools, portals, and workflows.
                        </div>

                        <div class="rounded-xl bg-white p-4 shadow-sm">
                            Automation concepts that reduce repetitive manual work.
                        </div>

                        <div class="rounded-xl bg-white p-4 shadow-sm">
                            Database and reporting systems that help organize business information.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-gray-50">
        <div class="mx-auto max-w-6xl px-6 py-20">
            <div class="mb-12 text-center">
                <p class="mb-3 text-sm font-bold uppercase tracking-widest text-blue-600">
                    Our Approach
                </p>

                <h2 class="text-3xl font-black text-gray-900">
                    Simple process. Clear communication. Practical results.
                </h2>

                <p class="mx-auto mt-4 max-w-2xl text-gray-600">
                    Every project starts with understanding the business problem first,
                    then choosing the right technology to solve it.
                </p>
            </div>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                <article class="rounded-2xl border bg-white p-6 shadow-sm">
                    <span class="text-sm font-bold uppercase tracking-widest text-blue-600">
                        Step 01
                    </span>

                    <h3 class="mt-3 text-xl font-bold text-gray-900">
                        Discover
                    </h3>

                    <p class="mt-4 text-sm leading-6 text-gray-600">
                        We learn about your business, goals, current process, and what problem needs to be solved.
                    </p>
                </article>

                <article class="rounded-2xl border bg-white p-6 shadow-sm">
                    <span class="text-sm font-bold uppercase tracking-widest text-blue-600">
                        Step 02
                    </span>

                    <h3 class="mt-3 text-xl font-bold text-gray-900">
                        Plan
                    </h3>

                    <p class="mt-4 text-sm leading-6 text-gray-600">
                        We outline the pages, features, data, workflow, and tools needed for the project.
                    </p>
                </article>

                <article class="rounded-2xl border bg-white p-6 shadow-sm">
                    <span class="text-sm font-bold uppercase tracking-widest text-blue-600">
                        Step 03
                    </span>

                    <h3 class="mt-3 text-xl font-bold text-gray-900">
                        Build
                    </h3>

                    <p class="mt-4 text-sm leading-6 text-gray-600">
                        We develop the website, application, automation, or database-driven solution.
                    </p>
                </article>

                <article class="rounded-2xl border bg-white p-6 shadow-sm">
                    <span class="text-sm font-bold uppercase tracking-widest text-blue-600">
                        Step 04
                    </span>

                    <h3 class="mt-3 text-xl font-bold text-gray-900">
                        Support
                    </h3>

                    <p class="mt-4 text-sm leading-6 text-gray-600">
                        We help test, launch, improve, and support the project after it goes live.
                    </p>
                </article>
            </div>
        </div>
    </section>

    <section class="bg-white">
        <div class="mx-auto max-w-6xl px-6 py-20">
            <div class="grid gap-10 md:grid-cols-2">
                <div>
                    <p class="mb-3 text-sm font-bold uppercase tracking-widest text-blue-600">
                        Who We Help
                    </p>

                    <h2 class="text-3xl font-black text-gray-900">
                        Built for businesses that need better systems.
                    </h2>

                    <p class="mt-5 text-gray-600 leading-7">
                        AndUs works with businesses and organizations that need a stronger online presence,
                        cleaner workflows, and better ways to manage information.
                    </p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-xl border bg-gray-50 p-5 font-semibold text-gray-900">
                        Small Businesses
                    </div>

                    <div class="rounded-xl border bg-gray-50 p-5 font-semibold text-gray-900">
                        Service-Based Companies
                    </div>

                    <div class="rounded-xl border bg-gray-50 p-5 font-semibold text-gray-900">
                        Real Estate Teams
                    </div>

                    <div class="rounded-xl border bg-gray-50 p-5 font-semibold text-gray-900">
                        Nonprofits
                    </div>

                    <div class="rounded-xl border bg-gray-50 p-5 font-semibold text-gray-900">
                        Entrepreneurs
                    </div>

                    <div class="rounded-xl border bg-gray-50 p-5 font-semibold text-gray-900">
                        Growing Organizations
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-gray-900">
        <div class="mx-auto max-w-4xl px-6 py-20 text-center">
            <p class="text-sm font-bold uppercase tracking-widest text-blue-400">
                Ready to Build?
            </p>

            <h2 class="mt-4 text-3xl font-black text-white">
                Let&apos;s create a digital system that supports your business.
            </h2>

            <p class="mx-auto mt-4 max-w-2xl text-gray-300">
                Whether you need a website, application, automation workflow, or database solution,
                AndUs can help bring the idea together.
            </p>

            <a href="{{ route('contact') }}"
               class="mt-8 inline-flex rounded bg-white px-7 py-3 font-semibold text-gray-900 transition hover:bg-gray-200">
                Start a Project
            </a>
        </div>
    </section>
@endsection
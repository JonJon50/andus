@extends('layouts.app')

@section('content')
    <section class="px-6 py-20 text-center">
        <div class="mx-auto max-w-5xl">
            <p class="text-sm font-semibold uppercase tracking-widest text-blue-700">
                AndUs Projects
            </p>

            <h1 class="mt-4 text-4xl font-bold text-slate-900 md:text-5xl">
                Real projects. Practical solutions. Built for business growth.
            </h1>

            <p class="mx-auto mt-5 max-w-2xl text-lg leading-8 text-slate-600">
                Explore websites, applications, automation demos, and data-driven solutions built with modern development tools.
            </p>
        </div>
    </section>

    <section class="bg-slate-50 px-6 py-16">
        <div class="mx-auto grid max-w-6xl gap-6 md:grid-cols-2 lg:grid-cols-3">
            <article class="flex h-full flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                <p class="text-sm font-semibold uppercase tracking-wide text-blue-700">
                    Laravel Application
                </p>

                <h2 class="mt-3 text-xl font-bold text-slate-900">
                    AndUs Business Platform
                </h2>

                <p class="mt-4 text-sm leading-6 text-slate-600">
                    A Laravel-based business platform built to showcase services, projects, contact workflows, and future client-focused tools.
                </p>

                <p class="mt-6 text-sm font-medium text-slate-500">
                    Laravel · Livewire · Tailwind · MariaDB
                </p>

                <div class="mt-auto pt-8">
                    <span class="inline-flex rounded bg-slate-100 px-5 py-2 text-sm font-semibold text-slate-500">
                        In Development
                    </span>
                </div>
            </article>

            <article class="flex h-full flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                <p class="text-sm font-semibold uppercase tracking-wide text-blue-700">
                    Web Development
                </p>

                <h2 class="mt-3 text-xl font-bold text-slate-900">
                    Carroll Farmhouse Cemetery Preservation
                </h2>

                <p class="mt-4 text-sm leading-6 text-slate-600">
                    A historical preservation website designed to share community history, media, and important site information.
                </p>

                <p class="mt-6 text-sm font-medium text-slate-500">
                    Next.js · React · Tailwind
                </p>

                <div class="mt-auto flex items-center gap-3 pt-8">
                    <a
                        href="https://carroll-farmhouse-cemetery-preservation.vercel.app/"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex rounded bg-slate-900 px-5 py-2 text-sm font-semibold text-white transition hover:bg-slate-700"
                    >
                        View Project
                    </a>

                    <span class="inline-flex rounded bg-green-100 px-4 py-2 text-sm font-semibold text-green-700">
                        Live
                    </span>
                </div>
            </article>

            <article class="flex h-full flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                <p class="text-sm font-semibold uppercase tracking-wide text-blue-700">
                    Automation Concept
                </p>

                <h2 class="mt-3 text-xl font-bold text-slate-900">
                    AI Workflow Automation Demo
                </h2>

                <p class="mt-4 text-sm leading-6 text-slate-600">
                    A demonstration concept focused on automating repetitive business tasks and improving operational workflows.
                </p>

                <p class="mt-6 text-sm font-medium text-slate-500">
                    Automation · Data Processing · AI Prompting
                </p>

                <div class="mt-auto flex items-center gap-3 pt-8">
                    <a
                        href="https://ai-integration-olive.vercel.app/"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex rounded bg-slate-900 px-5 py-2 text-sm font-semibold text-white transition hover:bg-slate-700"
                    >
                        View Project
                    </a>

                    <span class="inline-flex rounded bg-blue-100 px-4 py-2 text-sm font-semibold text-blue-700">
                        Demo
                    </span>
                </div>
            </article>
        </div>
    </section>

    <section class="px-6 py-20 text-center">
        <div class="mx-auto max-w-3xl">
            <h2 class="text-3xl font-bold text-slate-900">
                Have a project in mind?
            </h2>

            <p class="mx-auto mt-4 max-w-xl text-base leading-7 text-slate-600">
                Let&apos;s discuss how AndUs can help build a website, application, database solution, or automation workflow for your business.
            </p>

            <a
                href="{{ route('contact') }}"
                class="mt-8 inline-flex rounded bg-slate-900 px-7 py-3 font-semibold text-white transition hover:bg-slate-700"
            >
                Start a Project
            </a>
        </div>
    </section>
@endsection
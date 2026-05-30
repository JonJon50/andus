@extends('layouts.app')

@section('content')
    <section class="bg-white">
        <div class="mx-auto grid min-h-[70vh] max-w-6xl items-center gap-10 px-6 py-20 md:grid-cols-2">
            <div>
                <p class="mb-4 text-sm font-bold uppercase tracking-widest text-blue-600">
                    Websites • AI Automation • Data Systems
                </p>

                <h1 class="mb-6 text-5xl font-black leading-tight tracking-tight text-gray-900">
                    AndUs builds practical digital systems for growing businesses.
                </h1>

                <p class="mb-8 text-lg leading-8 text-gray-600">
                    We help businesses improve their online presence, automate repetitive work,
                    organize data, and build tools that make daily operations easier.
                </p>

                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('contact') }}"
                       class="rounded bg-gray-900 px-6 py-3 font-semibold text-white transition hover:bg-black">
                        Get a Quote
                    </a>

                    <a href="{{ route('services') }}"
                       class="rounded border border-gray-900 px-6 py-3 font-semibold text-gray-900 transition hover:bg-gray-900 hover:text-white">
                        View Services
                    </a>
                </div>
            </div>

            <div class="rounded-2xl border bg-gray-50 p-8 shadow-sm">
                <h2 class="mb-4 text-2xl font-bold text-gray-900">
                    What We Help With
                </h2>

                <div class="grid gap-4">
                    <div class="rounded-xl bg-white p-4 shadow-sm">
                        Business Website Development
                    </div>

                    <div class="rounded-xl bg-white p-4 shadow-sm">
                        Custom Web Applications
                    </div>

                    <div class="rounded-xl bg-white p-4 shadow-sm">
                        Workflow Automation
                    </div>

                    <div class="rounded-xl bg-white p-4 shadow-sm">
                        Database & Reporting Solutions
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
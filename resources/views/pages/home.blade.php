@extends('layouts.app')

@section('content')
    <section class="bg-white">
        <div class="mx-auto grid min-h-[70vh] max-w-6xl items-center gap-10 px-6 py-20 md:grid-cols-2">
            <div>
                <p class="mb-4 text-sm font-bold uppercase tracking-widest text-blue-600">
                    AI Automation • Web Apps • Data Systems
                </p>

                <h1 class="mb-6 text-5xl font-black leading-tight tracking-tight text-gray-900">
                    AndUs builds smarter digital systems for growing businesses.
                </h1>

                <p class="mb-8 text-lg text-gray-600">
                    We develop AI-powered automation, data infrastructure, analytics systems,
                    and modern web applications using Laravel, Livewire, React, Next.js,
                    MERN, MySQL, and MariaDB.
                </p>

                <div class="flex gap-4">
                    <a href="/contact" class="rounded bg-gray-900 px-6 py-3 font-semibold text-white">
                        Get a Quote
                    </a>

                    <a href="/services" class="rounded border px-6 py-3 font-semibold text-gray-900">
                        View Services
                    </a>
                </div>
            </div>

            <div class="rounded-2xl border bg-gray-50 p-8 shadow-sm">
                <h2 class="mb-4 text-2xl font-bold text-gray-900">
                    What We Build
                </h2>

                <div class="grid gap-4">
                    <div class="rounded-xl bg-white p-4 shadow-sm">
                        AI Automation / Prompting Systems
                    </div>

                    <div class="rounded-xl bg-white p-4 shadow-sm">
                        Laravel & Livewire Applications
                    </div>

                    <div class="rounded-xl bg-white p-4 shadow-sm">
                        React & Next.js Websites
                    </div>

                    <div class="rounded-xl bg-white p-4 shadow-sm">
                        MERN Stack Development
                    </div>

                    <div class="rounded-xl bg-white p-4 shadow-sm">
                        MySQL & MariaDB Database Solutions
                    </div>

                    <div class="rounded-xl bg-white p-4 shadow-sm">
                        Data Analytics & Reporting
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
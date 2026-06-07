@extends('layouts.app')

@section('content')
    <section class="px-6 py-20 text-center">
        <div class="mx-auto max-w-5xl">
            <p class="text-sm font-semibold uppercase tracking-widest text-blue-700">
                Contact AndUs
            </p>

            <h1 class="mt-4 text-4xl font-bold text-slate-900">
                Let&apos;s build something that works for your business.
            </h1>

            <p class="mx-auto mt-5 max-w-2xl text-lg leading-8 text-slate-600">
                Tell us about your website, application, automation, or database needs.
            </p>
        </div>
    </section>

    <section class="bg-slate-50 px-6 py-16">
        <div class="mx-auto max-w-4xl">
            <div class="mb-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
                <div class="flex flex-col gap-6 md:flex-row md:items-start md:justify-between">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-widest text-blue-700">
                            Smart Project Intake
                        </p>

                        <h2 class="mt-2 text-2xl font-bold text-slate-900">
                            Before we get started
                        </h2>

                        <p class="mt-3 text-sm leading-6 text-slate-600">
                            Tell us what you are trying to build, what problem you want to solve,
                            and which service best fits your project. After reviewing your message,
                            we will follow up with next steps.
                        </p>
                    </div>

                    <div class="rounded-xl bg-blue-50 px-4 py-3 text-sm font-semibold text-blue-700">
                        Guided by automation
                    </div>
                </div>

                <div class="mt-6 grid gap-3 text-sm text-slate-600 md:grid-cols-2">
                    <p class="rounded-lg bg-slate-50 p-3">
                        • What problem are you trying to solve?
                    </p>

                    <p class="rounded-lg bg-slate-50 p-3">
                        • What type of service do you need?
                    </p>

                    <p class="rounded-lg bg-slate-50 p-3">
                        • Do you have a timeline in mind?
                    </p>

                    <p class="rounded-lg bg-slate-50 p-3">
                        • What would a successful project look like?
                    </p>
                </div>
            </div>

            <livewire:contact-form />
        </div>
    </section>
@endsection
@extends('layouts.app')

@section('content')
    <section class="px-6 py-20 text-center">
        <div class="mx-auto max-w-5xl">
            <p class="text-sm font-semibold uppercase tracking-widest text-blue-700">
                Contact Andus
            </p>

            <h1 class="mt-4 text-4xl font-bold text-slate-900">
                Let&apos;s build something that works for your business.
            </h1>

            <p class="mx-auto mt-5 max-w-2xl text-lg text-slate-600">
                Tell us about your website, application, automation, or database needs.
            </p>
        </div>
    </section>

    <section class="bg-slate-50 px-6 py-16">
        <div class="mx-auto max-w-4xl">
            <livewire:contact-form />
        </div>
    </section>
@endsection
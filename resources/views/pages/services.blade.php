@extends('layouts.app')

@section('content')
    <section class="bg-white">
        <div class="mx-auto max-w-6xl px-6 py-16">
            <div class="mb-12 text-center">
                <p class="mb-3 text-sm font-bold uppercase tracking-widest text-blue-600">
                    AndUs Services
                </p>

                <h1 class="text-4xl font-black text-gray-900">
                    Practical digital services built for growing businesses.
                </h1>

                <p class="mx-auto mt-4 max-w-2xl text-gray-600">
                    We help businesses improve their websites, automate repetitive tasks,
                    organize data, and build systems that make daily operations easier.
                </p>
            </div>

            <livewire:services-list />

            <div class="mt-12 text-center">
                <a href="{{ route('contact') }}"
                   class="rounded bg-gray-900 px-6 py-3 font-semibold text-white hover:bg-black">
                    Start a Project
                </a>
            </div>
        </div>
    </section>
@endsection
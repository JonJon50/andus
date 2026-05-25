<div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
    @foreach ($services as $service)
        <div class="rounded-2xl border bg-white p-6 shadow-sm">
            <h3 class="mb-3 text-xl font-bold text-gray-900">
                {{ $service->title }}
            </h3>

            <p class="text-sm leading-6 text-gray-600">
                {{ $service->description }}
            </p>
        </div>
    @endforeach
</div>
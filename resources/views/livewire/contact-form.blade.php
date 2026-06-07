<div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:p-10">
    @if ($submitted)
        <div class="mb-8 rounded-lg border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-800">
            Thank you for contacting AndUs. Your inquiry has been received.
        </div>
    @endif

    <form wire:submit="save" class="space-y-6">
        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label for="name" class="mb-2 block text-sm font-semibold text-slate-900">
                    Name <span class="text-red-600">*</span>
                </label>

                <input
                    id="name"
                    type="text"
                    wire:model="name"
                    class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-blue-700"
                    placeholder="Your name"
                >

                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="mb-2 block text-sm font-semibold text-slate-900">
                    Email <span class="text-red-600">*</span>
                </label>

                <input
                    id="email"
                    type="email"
                    wire:model="email"
                    class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-blue-700"
                    placeholder="you@example.com"
                >

                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label for="phone" class="mb-2 block text-sm font-semibold text-slate-900">
                    Phone
                </label>

                <input
                    id="phone"
                    type="text"
                    wire:model="phone"
                    class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-blue-700"
                    placeholder="Optional"
                >

                @error('phone')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="company" class="mb-2 block text-sm font-semibold text-slate-900">
                    Company
                </label>

                <input
                    id="company"
                    type="text"
                    wire:model="company"
                    class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-blue-700"
                    placeholder="Optional"
                >

                @error('company')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label for="service_id" class="mb-2 block text-sm font-semibold text-slate-900">
                Service Interested In
            </label>

            <select
                id="service_id"
                wire:model.live="service_id"
                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-blue-700"
            >
                <option value="">Select a service</option>

                @foreach ($services as $service)
                    <option value="{{ $service->id }}">
                        {{ $service->title }}
                    </option>
                @endforeach
            </select>

            @error('service_id')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        @if ($selectedService)
            <div class="rounded-2xl border border-blue-100 bg-blue-50 p-5 shadow-sm transition">
                <div class="flex items-start gap-4">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-700 text-sm font-bold text-white">
                        AI
                    </div>

                    <div>
                        <p class="text-sm font-bold uppercase tracking-widest text-blue-700">
                            Smart Project Guidance
                        </p>

                        <h3 class="mt-1 text-lg font-bold text-slate-900">
                            {{ $servicePrompt['title'] }}
                        </h3>

                        <p class="mt-2 text-sm leading-6 text-slate-700">
                            {{ $servicePrompt['description'] }}
                        </p>

                        <div class="mt-4 rounded-xl bg-white p-4">
                            <p class="text-sm font-semibold text-slate-900">
                                Helpful details to include in your message:
                            </p>

                            <ul class="mt-3 space-y-2 text-sm text-slate-600">
                                @foreach ($servicePrompt['questions'] as $question)
                                    <li>
                                        • {{ $question }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                <p class="text-sm font-bold uppercase tracking-widest text-slate-500">
                    Smart Project Guidance
                </p>

                <h3 class="mt-1 text-lg font-bold text-slate-900">
                    {{ $servicePrompt['title'] }}
                </h3>

                <p class="mt-2 text-sm leading-6 text-slate-600">
                    {{ $servicePrompt['description'] }}
                </p>
            </div>
        @endif

        <div>
            <label for="message" class="mb-2 block text-sm font-semibold text-slate-900">
                Tell Us About Your Project <span class="text-red-600">*</span>
            </label>

            <textarea
                id="message"
                wire:model="message"
                rows="6"
                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-blue-700"
                placeholder="What can we help you build?"
            ></textarea>

            @error('message')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <button
            type="submit"
            class="rounded bg-slate-900 px-7 py-3 font-semibold text-white transition hover:bg-slate-700"
        >
            <span wire:loading.remove wire:target="save">
                Send Message
            </span>

            <span wire:loading wire:target="save">
                Sending...
            </span>
        </button>
    </form>
</div>
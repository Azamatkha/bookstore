@extends('layouts.admin')

@section('title', 'API Docs | Admin')

@section('content')
    <div class="space-y-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">API Docs</p>
                <h1 class="mt-2 text-5xl">Simple API guide.</h1>
                <p class="mt-3 max-w-3xl text-slate-600">
                    Use this page in Postman. Login first, copy the token, then send it as a Bearer token.
                </p>
            </div>

            <div class="panel px-5 py-4">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Base URL</p>
                <p class="mt-2 font-mono text-sm text-slate-900">{{ $baseUrl }}</p>
            </div>
        </div>

        <div class="grid gap-8 xl:grid-cols-[0.8fr_1.2fr]">
            <div class="space-y-6">
                <div class="panel p-6">
                    <h2 class="text-3xl">How to get token</h2>
                    <div class="mt-5 space-y-3">
                        @foreach($tokenSteps as $index => $step)
                            <div class="flex gap-3">
                                <span class="mt-0.5 inline-flex h-7 w-7 items-center justify-center rounded-full bg-slate-900 text-xs font-semibold text-white">{{ $index + 1 }}</span>
                                <p class="text-sm leading-7 text-slate-600">{{ $step }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="panel p-6">
                    <h2 class="text-3xl">Admin login for testing</h2>
                    <div class="mt-5 space-y-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Email</p>
                            <p class="mt-1 font-mono text-sm text-slate-900">admin@bookstore.test</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Password</p>
                            <p class="mt-1 font-mono text-sm text-slate-900">password</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm leading-7 text-slate-600">
                            Login request must go to <span class="font-mono text-slate-900">{{ $baseUrl }}/api/login</span>
                            with header <span class="font-mono text-slate-900">Accept: application/json</span>.
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                @foreach($endpoints as $endpoint)
                    <div class="panel p-6">
                        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em]
                                    {{ $endpoint['method'] === 'GET' ? 'bg-emerald-100 text-emerald-700' : 'bg-sky-100 text-sky-700' }}">
                                    {{ $endpoint['method'] }}
                                </span>
                                <p class="font-mono text-sm text-slate-900">{{ $endpoint['path'] }}</p>
                            </div>
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-slate-700">
                                {{ $endpoint['auth'] }}
                            </span>
                        </div>

                        <div class="mt-5 space-y-4 text-sm text-slate-600">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">What it does</p>
                                <p class="mt-2 leading-7">{{ $endpoint['description'] }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Request</p>
                                @if($endpoint['request'])
                                    <pre class="mt-2 overflow-x-auto rounded-2xl bg-slate-950 p-4 text-xs leading-6 text-slate-100">{{ json_encode($endpoint['request'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                @else
                                    <p class="mt-2 leading-7">No JSON body.</p>
                                @endif
                            </div>

                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Response example</p>
                                <pre class="mt-2 overflow-x-auto rounded-2xl bg-slate-950 p-4 text-xs leading-6 text-slate-100">{{ json_encode($endpoint['response'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

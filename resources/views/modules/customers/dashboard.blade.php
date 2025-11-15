@extends('layouts.app')

@section('title', 'Mi cuenta - Angelow')

@section('content')
    <section class="customer-dashboard container mx-auto px-4 py-10">
        <div class="grid gap-6 lg:grid-cols-[320px,1fr]">
            <aside class="bg-slate-50 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-sky-500 to-indigo-500 text-white flex items-center justify-center text-2xl font-semibold">
                        {{ strtoupper(mb_substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Bienvenido</p>
                        <p class="text-lg font-semibold text-slate-900">{{ $user->name }}</p>
                        <p class="text-sm text-slate-500">{{ $user->email }}</p>
                    </div>
                </div>
                <dl class="mt-8 space-y-4 text-sm text-slate-600">
                    <div class="flex justify-between">
                        <dt>Rol</dt>
                        <dd class="font-semibold capitalize">{{ $user->role }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt>Último acceso</dt>
                        <dd>{{ optional($user->last_access)->format('d/m/Y H:i') ?? '—' }}</dd>
                    </div>
                </dl>
                <form method="POST" action="{{ route('customers.logout') }}" class="mt-8">
                    @csrf
                    <button type="submit" class="w-full btn btn-outline">Cerrar sesión</button>
                </form>
            </aside>
            <div class="bg-white rounded-3xl p-6 shadow-lg border border-slate-100">
                <h2 class="text-2xl font-semibold text-slate-900 mb-4">Panel en construcción</h2>
                <p class="text-slate-600">Muy pronto podrás gestionar tus direcciones, pedidos, alertas y métodos de pago desde este espacio centralizado.</p>
                <ul class="mt-6 list-disc pl-6 text-slate-600 space-y-2">
                    <li>Seguimiento de pedidos y estados.</li>
                    <li>Wishlist sincronizada con alertas de stock.</li>
                    <li>Preferencias de notificaciones y seguridad.</li>
                </ul>
            </div>
        </div>
    </section>
@endsection

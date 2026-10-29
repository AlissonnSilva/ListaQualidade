@extends('layout')
@section('content')
<div class="actions"><h1 style="margin-right:auto">Fretes e entregas</h1><a class="btn" data-cy="new-delivery" href="{{ route('entregas.create') }}">+ Novo frete</a></div>
<div class="table-wrap">
    <table data-cy="deliveries-table">
        <thead><tr><th>Pedido</th><th>Cliente</th><th>Destino</th><th>Frete</th><th>Data prevista</th><th>Rastreio</th><th>Status</th><th>Ações</th></tr></thead>
        <tbody>
        @forelse($deliveries as $delivery)
            <tr data-cy="delivery-row">
                <td>#{{ $delivery->order_id }}</td><td>{{ $delivery->order->customer->name }}</td><td>{{ $delivery->address }}, {{ $delivery->city }}</td>
                <td>R$ {{ number_format($delivery->fee, 2, ',', '.') }}</td><td>{{ $delivery->scheduled_at?->format('d/m/Y') }}</td><td data-cy="delivery-tracking-code">{{ $delivery->tracking_code ?: '—' }}</td>
                <td data-cy="delivery-status-label"><small class="badge">{{ ['scheduled' => 'Agendada', 'in_transit' => 'Em trânsito', 'delivered' => 'Entregue', 'cancelled' => 'Cancelada'][$delivery->status] ?? $delivery->status }}</small></td>
                <td class="actions"><a class="btn secondary" data-cy="edit-delivery" href="{{ route('entregas.edit', $delivery) }}">Editar</a><form method="POST" action="{{ route('entregas.destroy', $delivery) }}" onsubmit="return confirm('Remover frete?')">@csrf @method('DELETE')<button class="danger" data-cy="delete-delivery">Excluir</button></form></td>
            </tr>
        @empty
            <tr><td colspan="8">Nenhum frete cadastrado.</td></tr>
        @endforelse
        </tbody>
    </table>
    @include('pagination', ['paginator' => $deliveries])
</div>
@endsection

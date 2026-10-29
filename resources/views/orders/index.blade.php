@extends('layout')
@section('content')
<div class="actions"><h1 style="margin-right:auto">Pedidos</h1><a class="btn" data-cy="new-order" href="{{ route('pedidos.create') }}">+ Novo pedido</a></div>
<div class="table-wrap">
    <table data-cy="orders-table">
        <thead><tr><th>#</th><th>Cliente</th><th>Produto</th><th>Total</th><th>Status</th><th>Ações</th></tr></thead>
        <tbody>
        @forelse($orders as $order)
            <tr data-cy="order-row">
                <td>{{ $order->id }}</td><td>{{ $order->customer->name }}</td><td>{{ $order->product->name }} ({{ $order->quantity }})</td>
                <td>R$ {{ number_format($order->total, 2, ',', '.') }}</td>
                <td data-cy="order-status"><small class="badge">{{ ['pending' => 'Pendente', 'confirmed' => 'Confirmado', 'ready' => 'Pronto', 'completed' => 'Concluído', 'cancelled' => 'Cancelado'][$order->status] ?? $order->status }}</small></td>
                <td class="actions"><a class="btn secondary" data-cy="edit-order" href="{{ route('pedidos.edit', $order) }}">Editar</a><form method="POST" action="{{ route('pedidos.destroy', $order) }}" onsubmit="return confirm('Remover pedido?')">@csrf @method('DELETE')<button class="danger" data-cy="delete-order">Excluir</button></form></td>
            </tr>
        @empty
            <tr><td colspan="6">Nenhum pedido cadastrado.</td></tr>
        @endforelse
        </tbody>
    </table>
    @include('pagination', ['paginator' => $orders])
</div>
@endsection

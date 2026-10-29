@extends('layout')
@section('content')
<div class="actions"><h1 style="margin-right:auto">Clientes</h1><a class="btn" data-cy="new-customer" href="{{ route('clientes.create') }}">+ Novo cliente</a></div>
<div class="table-wrap">
    <table data-cy="customers-table">
        <thead><tr><th>Nome</th><th>Contato</th><th>Endereço</th><th>Ações</th></tr></thead>
        <tbody>
        @forelse($customers as $customer)
            <tr data-cy="customer-row">
                <td>{{ $customer->name }}</td><td>{{ $customer->email }}<br>{{ $customer->phone }}</td><td>{{ $customer->address }}<br>{{ $customer->city }}</td>
                <td class="actions"><a class="btn secondary" data-cy="edit-customer" href="{{ route('clientes.edit', $customer) }}">Editar</a><form method="POST" action="{{ route('clientes.destroy', $customer) }}" onsubmit="return confirm('Remover cliente?')">@csrf @method('DELETE')<button class="danger" data-cy="delete-customer">Excluir</button></form></td>
            </tr>
        @empty
            <tr><td colspan="4">Nenhum cliente cadastrado.</td></tr>
        @endforelse
        </tbody>
    </table>
    @include('pagination', ['paginator' => $customers])
</div>
@endsection

@extends('layout')
@section('content')
<div class="actions"><h1 style="margin-right:auto">Produtos e estoque</h1><a class="btn" data-cy="new-product" href="{{ route('produtos.create') }}">+ Novo produto</a></div>
<div class="table-wrap">
    <table data-cy="products-table">
        <thead><tr><th>Produto</th><th>Categoria</th><th>Preço</th><th>Estoque</th><th>Ações</th></tr></thead>
        <tbody>
        @forelse($products as $product)
            <tr data-cy="product-row">
                <td><strong>{{ $product->name }}</strong><br><small>{{ $product->active ? 'Ativo' : 'Inativo' }}</small></td>
                <td>{{ $product->category }}</td>
                <td>R$ {{ number_format($product->price, 2, ',', '.') }}</td>
                <td data-cy="product-stock"><small data-cy="{{ $product->stock <= $product->minimum_stock ? 'stock-low' : 'stock-normal' }}" class="badge {{ $product->stock <= $product->minimum_stock ? 'stock-low' : '' }}">{{ $product->stock }} un.</small></td>
                <td class="actions">
                    <a class="btn secondary" data-cy="edit-product" href="{{ route('produtos.edit', $product) }}">Editar</a>
                    <form method="POST" action="{{ route('produtos.destroy', $product) }}" onsubmit="return confirm('Remover produto?')">@csrf @method('DELETE')<button class="danger" data-cy="delete-product">Excluir</button></form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5">Nenhum produto cadastrado.</td></tr>
        @endforelse
        </tbody>
    </table>
    @include('pagination', ['paginator' => $products])
</div>
@endsection

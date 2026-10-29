@extends('layout')
@section('content')
<h1>Painel da Doce Gestão</h1>
<p>Acompanhe produtos, clientes, pedidos e entregas da confeitaria.</p>
<div class="cards">
    @foreach([['Produtos em estoque',$products,'produtos.index'],['Clientes',$customers,'clientes.index'],['Pedidos',$orders,'pedidos.index'],['Fretes',$deliveries,'entregas.index']] as [$label,$value,$route])
        <a class="card" href="{{ route($route) }}" style="text-decoration:none;color:inherit"><div>{{ $label }}</div><b>{{ $value }}</b></a>
    @endforeach
</div>
<section class="card">
    <h2>Como usar</h2>
    <ol>
        <li>Cadastre produtos e defina o estoque mínimo.</li>
        <li>Cadastre clientes e seus endereços.</li>
        <li>Crie um pedido. O preço é salvo e o estoque é baixado automaticamente.</li>
        <li>Agende um frete e acompanhe o status até a entrega.</li>
    </ol>
</section>
@endsection

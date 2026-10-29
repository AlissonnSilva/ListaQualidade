@extends('layout')
@section('content')
<h1>Editar frete</h1>
<form data-cy="delivery-form" method="POST" action="{{ route('entregas.update', $delivery) }}">@method('PUT') @include('deliveries.form')</form>
@endsection

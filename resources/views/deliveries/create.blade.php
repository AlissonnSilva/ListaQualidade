@extends('layout')
@section('content')
<h1>Novo frete</h1>
<form data-cy="delivery-form" method="POST" action="{{ route('entregas.store') }}">@include('deliveries.form')</form>
@endsection

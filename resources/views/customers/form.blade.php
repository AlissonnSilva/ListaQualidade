@csrf
<label>Nome<input data-cy="customer-name" name="name" value="{{ old('name', $customer->name ?? '') }}" required></label>
@error('name')<small class="error" data-cy="error-name" role="alert">{{ $message }}</small>@enderror
<label>E-mail<input data-cy="customer-email" type="email" name="email" value="{{ old('email', $customer->email ?? '') }}"></label>
@error('email')<small class="error" data-cy="error-email" role="alert">{{ $message }}</small>@enderror
<label>Telefone<input data-cy="customer-phone" name="phone" value="{{ old('phone', $customer->phone ?? '') }}"></label>
<label>Endereço<input data-cy="customer-address" name="address" value="{{ old('address', $customer->address ?? '') }}"></label>
<label>Cidade<input data-cy="customer-city" name="city" value="{{ old('city', $customer->city ?? '') }}"></label>
<div class="actions"><button data-cy="customer-submit">Salvar</button><a class="btn secondary" href="{{ route('clientes.index') }}">Cancelar</a></div>

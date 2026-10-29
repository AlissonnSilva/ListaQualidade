@csrf
<label>Pedido<select data-cy="delivery-order" name="order_id" required><option value="">Selecione um pedido</option>@foreach($orders as $order)<option value="{{ $order->id }}" @selected(old('order_id', $delivery->order_id ?? '') == $order->id)>#{{ $order->id }} — {{ $order->customer->name }}</option>@endforeach</select></label>
@error('order_id')<small class="error" data-cy="error-order-id" role="alert">{{ $message }}</small>@enderror
<label>Endereço<input data-cy="delivery-address" name="address" value="{{ old('address', $delivery->address ?? '') }}" required></label>
@error('address')<small class="error" data-cy="error-address" role="alert">{{ $message }}</small>@enderror
<label>Cidade<input data-cy="delivery-city" name="city" value="{{ old('city', $delivery->city ?? '') }}" required></label>
@error('city')<small class="error" data-cy="error-city" role="alert">{{ $message }}</small>@enderror
<label>Taxa de frete<input data-cy="delivery-fee" type="number" step="0.01" min="0" name="fee" value="{{ old('fee', $delivery->fee ?? 0) }}" required></label>
@error('fee')<small class="error" data-cy="error-fee" role="alert">{{ $message }}</small>@enderror
<label>Data prevista<input data-cy="delivery-scheduled-at" type="date" name="scheduled_at" value="{{ old('scheduled_at', isset($delivery) && $delivery->scheduled_at ? $delivery->scheduled_at->format('Y-m-d') : '') }}"></label>
<label>Status<select data-cy="delivery-status" name="status" required>@foreach(['scheduled' => 'Agendada', 'in_transit' => 'Em trânsito', 'delivered' => 'Entregue', 'cancelled' => 'Cancelada'] as $value => $label)<option value="{{ $value }}" @selected(old('status', $delivery->status ?? 'scheduled') === $value)>{{ $label }}</option>@endforeach</select></label>
<label>Código de rastreio<input data-cy="delivery-tracking-code" name="tracking_code" value="{{ old('tracking_code', $delivery->tracking_code ?? '') }}"></label>
<div class="actions"><button data-cy="delivery-submit">Salvar frete</button><a class="btn secondary" href="{{ route('entregas.index') }}">Cancelar</a></div>

@csrf
<label>Cliente<select data-cy="order-customer" name="customer_id" required><option value="">Selecione um cliente</option>@foreach($customers as $customer)<option value="{{ $customer->id }}" @selected(old('customer_id', $order->customer_id ?? '') == $customer->id)>{{ $customer->name }}</option>@endforeach</select></label>
@error('customer_id')<small class="error" data-cy="error-customer-id" role="alert">{{ $message }}</small>@enderror
<label>Produto<select data-cy="order-product" name="product_id" required><option value="">Selecione um produto</option>@foreach($products as $product)<option value="{{ $product->id }}" @selected(old('product_id', $order->product_id ?? '') == $product->id)>{{ $product->name }} — R$ {{ number_format($product->price, 2, ',', '.') }} ({{ $product->stock }} em estoque)</option>@endforeach</select></label>
@error('product_id')<small class="error" data-cy="error-product-id" role="alert">{{ $message }}</small>@enderror
<label>Quantidade<input data-cy="order-quantity" type="number" min="1" name="quantity" value="{{ old('quantity', $order->quantity ?? 1) }}" required></label>
@error('quantity')<small class="error" data-cy="error-quantity" role="alert">{{ $message }}</small>@enderror
<label>Status<select data-cy="order-status" name="status" required>@foreach(['pending' => 'Pendente', 'confirmed' => 'Confirmado', 'ready' => 'Pronto', 'completed' => 'Concluído', 'cancelled' => 'Cancelado'] as $value => $label)<option value="{{ $value }}" @selected(old('status', $order->status ?? 'pending') === $value)>{{ $label }}</option>@endforeach</select></label>
@error('status')<small class="error" data-cy="error-status" role="alert">{{ $message }}</small>@enderror
<label>Observações<textarea data-cy="order-notes" name="notes">{{ old('notes', $order->notes ?? '') }}</textarea></label>
<div class="actions"><button data-cy="order-submit">Salvar pedido</button><a class="btn secondary" href="{{ route('pedidos.index') }}">Cancelar</a></div>

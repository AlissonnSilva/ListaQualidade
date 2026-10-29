@csrf
<label>Nome<input data-cy="product-name" name="name" value="{{ old('name', $product->name ?? '') }}" required></label>
@error('name')<small class="error" data-cy="error-name" role="alert">{{ $message }}</small>@enderror
<label>Categoria<select data-cy="product-category" name="category" required><option value="">Selecione</option>@foreach(['Bolos','Doces','Salgados','Bebidas'] as $category)<option value="{{ $category }}" @selected(old('category', $product->category ?? '') === $category)>{{ $category }}</option>@endforeach</select></label>
@error('category')<small class="error" data-cy="error-category" role="alert">{{ $message }}</small>@enderror
<label>Descrição<textarea data-cy="product-description" name="description">{{ old('description', $product->description ?? '') }}</textarea></label>
<label>Preço<input data-cy="product-price" type="number" step="0.01" min="0" name="price" value="{{ old('price', $product->price ?? '') }}" required></label>
@error('price')<small class="error" data-cy="error-price" role="alert">{{ $message }}</small>@enderror
<label>Estoque atual<input data-cy="product-stock" type="number" min="0" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" required></label>
@error('stock')<small class="error" data-cy="error-stock" role="alert">{{ $message }}</small>@enderror
<label>Estoque mínimo<input data-cy="product-minimum-stock" type="number" min="0" name="minimum_stock" value="{{ old('minimum_stock', $product->minimum_stock ?? 5) }}" required></label>
@error('minimum_stock')<small class="error" data-cy="error-minimum-stock" role="alert">{{ $message }}</small>@enderror
<label><input data-cy="product-active" type="checkbox" name="active" value="1" @checked(old('active', $product->active ?? true))> Produto ativo</label>
<div class="actions"><button data-cy="product-submit">Salvar</button><a class="btn secondary" href="{{ route('produtos.index') }}">Cancelar</a></div>

@csrf
<label>Nome<input name="name" value="{{ old('name',$product->name ?? '') }}" required></label>
<label>Categoria<select name="category" required><option value="">Selecione</option>@foreach(['Bolos','Doces','Salgados','Bebidas'] as $category)<option value="{{ $category }}" @selected(old('category',$product->category ?? '') === $category)>{{ $category }}</option>@endforeach</select></label>
<label>Descrição<textarea name="description">{{ old('description',$product->description ?? '') }}</textarea></label>
<label>Preço<input type="number" step="0.01" min="0" name="price" value="{{ old('price',$product->price ?? '') }}" required></label>
<label>Estoque atual<input type="number" min="0" name="stock" value="{{ old('stock',$product->stock ?? 0) }}" required></label>
<label>Estoque mínimo<input type="number" min="0" name="minimum_stock" value="{{ old('minimum_stock',$product->minimum_stock ?? 5) }}" required></label>
<label><input type="checkbox" name="active" value="1" @checked(old('active',$product->active ?? true))> Produto ativo</label>
<div class="actions"><button>Salvar</button><a class="btn secondary" href="{{ route('produtos.index') }}">Cancelar</a></div>

<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function index(): View { return view('orders.index', ['orders' => Order::with(['customer', 'product'])->latest()->paginate(10)]); }
    public function create(): View { return view('orders.create', ['customers' => Customer::orderBy('name')->get(), 'products' => Product::where('active', true)->orderBy('name')->get()]); }
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedOrder($request);

        DB::transaction(function () use ($data): void {
            $product = Product::query()->whereKey($data['product_id'])->lockForUpdate()->firstOrFail();

            if ($product->stock < $data['quantity']) {
                throw ValidationException::withMessages([
                    'quantity' => 'Estoque insuficiente para este produto.',
                ]);
            }

            $product->decrement('stock', $data['quantity']);

            Order::create($data + ['unit_price' => $product->price]);
        });

        return to_route('pedidos.index')->with('success', 'Pedido criado e estoque atualizado.');
    }
    public function edit(Order $pedido): View { return view('orders.edit', ['order' => $pedido->load(['customer', 'product']), 'customers' => Customer::orderBy('name')->get(), 'products' => Product::orderBy('name')->get()]); }
    public function update(Request $request, Order $pedido): RedirectResponse
    {
        $data = $this->validatedOrder($request);

        DB::transaction(function () use ($data, $pedido): void {
            $currentOrder = Order::query()->whereKey($pedido->id)->lockForUpdate()->firstOrFail();
            $productIds = collect([$currentOrder->product_id, $data['product_id']])->unique()->sort()->values();
            $products = Product::query()
                ->whereIn('id', $productIds)
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $stockChanges = [];
            if ($currentOrder->status !== 'cancelled') {
                $stockChanges[$currentOrder->product_id] = ($stockChanges[$currentOrder->product_id] ?? 0) + $currentOrder->quantity;
            }
            if ($data['status'] !== 'cancelled') {
                $stockChanges[$data['product_id']] = ($stockChanges[$data['product_id']] ?? 0) - $data['quantity'];
            }

            foreach ($stockChanges as $productId => $change) {
                $product = $products->get($productId);
                $newStock = $product->stock + $change;

                if ($newStock < 0) {
                    throw ValidationException::withMessages([
                        'quantity' => 'Estoque insuficiente para este produto.',
                    ]);
                }

                $product->stock = $newStock;
                $product->save();
            }

            $data['unit_price'] = (int) $currentOrder->product_id === (int) $data['product_id']
                ? $currentOrder->unit_price
                : $products->get($data['product_id'])->price;

            $currentOrder->update($data);
        });

        return to_route('pedidos.index')->with('success', 'Pedido atualizado.');
    }
    public function destroy(Order $pedido): RedirectResponse
    {
        DB::transaction(function () use ($pedido): void {
            $order = Order::query()->whereKey($pedido->id)->lockForUpdate()->firstOrFail();

            if ($order->status !== 'cancelled') {
                $product = Product::query()->whereKey($order->product_id)->lockForUpdate()->firstOrFail();
                $product->increment('stock', $order->quantity);
            }

            $order->delete();
        });

        return to_route('pedidos.index')->with('success', 'Pedido removido.');
    }

    /** @return array{customer_id: int, product_id: int, quantity: int, status: string, notes: ?string} */
    private function validatedOrder(Request $request): array
    {
        return $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'in:pending,confirmed,ready,completed,cancelled'],
            'notes' => ['nullable', 'string'],
        ]);
    }
}

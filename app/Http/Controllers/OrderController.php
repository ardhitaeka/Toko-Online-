<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::with('user');

        if ($request->filled('search')) {
            $query->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function ($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $orders = $query->latest()->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function create(): View
    {
        $customers = User::where('role', 'customer')->get();
        $products = Product::where('is_active', true)->where('stock', '>', 0)->get();

        return view('orders.create', compact('customers', 'products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.size' => 'nullable|string',
            'items.*.color' => 'nullable|string',
            'shipping_cost' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:transfer,cod,e-wallet',
            'shipping_address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $subtotal = 0;
        $orderItems = [];

        foreach ($validated['items'] as $item) {
            $product = Product::findOrFail($item['product_id']);
            $itemSubtotal = $product->effective_price * $item['quantity'];
            $subtotal += $itemSubtotal;

            $orderItems[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'price' => $product->effective_price,
                'quantity' => $item['quantity'],
                'subtotal' => $itemSubtotal,
                'size' => $item['size'] ?? $product->size,
                'color' => $item['color'] ?? $product->color,
            ];

            $product->decrement('stock', $item['quantity']);
        }

        $shippingCost = $validated['shipping_cost'] ?? 0;
        $discount = $validated['discount'] ?? 0;
        $total = $subtotal + $shippingCost - $discount;

        $order = Order::create([
            'user_id' => $validated['user_id'],
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'discount' => $discount,
            'total' => $total,
            'payment_method' => $validated['payment_method'],
            'shipping_address' => $validated['shipping_address'],
            'notes' => $validated['notes'],
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        foreach ($orderItems as $item) {
            $item['order_id'] = $order->id;
            \App\Models\OrderItem::create($item);
        }

        return redirect()->route('orders.show', $order)->with('success', 'Pesanan berhasil dibuat!');
    }

    public function show(Order $order): View
    {
        $order->load('user', 'items.product');

        return view('orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,cancelled',
        ]);

        if ($validated['status'] === 'cancelled' && $order->status !== 'cancelled') {
            foreach ($order->items as $item) {
                $item->product->increment('stock', $item->quantity);
            }
        }

        $order->update($validated);

        return redirect()->route('orders.show', $order)->with('success', 'Status pesanan berhasil diperbarui!');
    }

    public function updatePayment(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'payment_status' => 'required|in:unpaid,paid,refunded',
        ]);

        $order->update($validated);

        return redirect()->route('orders.show', $order)->with('success', 'Status pembayaran berhasil diperbarui!');
    }

    public function destroy(Order $order): RedirectResponse
    {
        foreach ($order->items as $item) {
            $item->product->increment('stock', $item->quantity);
        }

        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dihapus!');
    }
}

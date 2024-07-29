<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return OrderResource::collection(Order::with('user', 'books')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'books' => 'required|array',
            'books.*.id' => 'required|exists:books,id',
            'books.*.quantity' => 'required|integer|min:1',
        ]);

        $order = new Order();
        $order->user_id = $request->user_id;
        $order->total_price = 0; // Calculate this based on books and their quantities
        $order->save();

        $totalPrice = 0;

        foreach ($request->books as $book) {
            $order->books()->attach($book['id'], ['quantity' => $book['quantity']]);
            $bookModel = \App\Models\Book::find($book['id']);
            $totalPrice += $bookModel->price * $book['quantity'];
        }

        $order->total_price = $totalPrice;
        $order->save();

        return new OrderResource($order);
    }

    public function show($id)
    {
        $order = Order::with('user', 'books')->findOrFail($id);
        return new OrderResource($order);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'sometimes|required|exists:users,id',
            'books' => 'sometimes|required|array',
            'books.*.id' => 'sometimes|required|exists:books,id',
            'books.*.quantity' => 'sometimes|required|integer|min:1',
        ]);

        $order = Order::findOrFail($id);

        if ($request->has('user_id')) {
            $order->user_id = $request->user_id;
        }

        if ($request->has('books')) {
            $order->books()->detach();
            $totalPrice = 0;

            foreach ($request->books as $book) {
                $order->books()->attach($book['id'], ['quantity' => $book['quantity']]);
                $bookModel = \App\Models\Book::find($book['id']);
                $totalPrice += $bookModel->price * $book['quantity'];
            }

            $order->total_price = $totalPrice;
        }

        $order->save();

        return new OrderResource($order);
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->books()->detach();
        $order->delete();
        return response()->json(null, 204);
    }
}

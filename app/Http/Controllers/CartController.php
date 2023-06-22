<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCartRequest;
use App\Http\Requests\CheckoutRequest;
use App\Http\Requests\DeleteCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Http\Resources\CartResource;
use App\Http\Resources\TransactionResource;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Auth;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function create(AddCartRequest $request)
    {
        try {
            $cart    = $this->getCarts();
            $newItem = [
                "id"       => $request->product_id,
                "quantity" => (int) $request->quantity
            ];

            if ($cart) {
                $items = $cart->cart_items;

                foreach ($items as $key => $item) {
                    if ($item["id"] === $request->product_id) {
                        $items[$key]["quantity"] += $request->quantity;
                        $cart->cart_items        = $items;
                        $cart->save();

                        return response()->json([
                            "status"  => true,
                            "message" => "updated",
                            "data"    => new CartResource($cart)
                        ], 200);
                    }
                }

                $items[]          = $newItem;
                $cart->cart_items = $items;
                $cart->save();
            } else {
                $cart = Cart::create([
                    'user_id'    => Auth::user()->id,
                    'cart_items' => [$newItem]
                ]);
            }

            return response()->json([
                "status"  => true,
                "message" => "created",
                "data"    => new CartResource($cart)
            ], 201);

        } catch (\Throwable $th) {
            return handleException($th);
        }
    }

    /**
     * Summary of index
     * @throws \Exception
     * @return JsonResponse
     */
    public function index()
    {
        try {
            $data = $this->getCarts();

            if ($data === null) {
                throw new Exception("data_not_found", 404);
            }

            return response()->json([
                "status"  => true,
                "message" => "found",
                "data"    => new CartResource($data),
            ], 200);

        } catch (\Throwable $th) {
            return handleException($th);
        }
    }

    /**
     * Get cart data
     * @return mixed
     */
    public function getCarts()
    {
        return Cart::where([
            'user_id' => Auth::user()->id
        ])->first();
    }

    /**
     * Api function for deleting cart items
     * @return JsonResponse
     */
    public function delete(DeleteCartRequest $request): JsonResponse
    {
        try {
            $items = $this->getCarts();

            if ($items === null) {
                throw new Exception("data_not_found", 404);
            }

            $found = false;

            // dump($items->cart_items);
            $cartItems = $items->cart_items;

            foreach ($items->cart_items as $key => $cart) {
                if ($cart["id"] === $request->product_id) {
                    Arr::forget($cartItems, $key);
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                throw new Exception("item_not_found", 404);
            }

            $items->cart_items = array_values($cartItems);
            $items->save();

            return response()->json([
                "status"  => true,
                "message" => "deleted",
                "data"    => new CartResource($items)
            ], 200);
        } catch (\Throwable $th) {
            return handleException($th);
        }
    }

    public function update(UpdateCartRequest $request): JsonResponse
    {
        try {
            $items = $this->getCarts();

            if ($items === null) {
                throw new Exception("data_not_found", 404);
            }

            $cartItems = $items->cart_items;

            $key = array_search($request->product_id, array_column($cartItems, 'id'));

            // dd($key, $key !== false);

            if ($key !== false) {
                if ($request->quantity == 0) {
                    unset($cartItems[$key]);
                } else {
                    $cartItems[$key]["quantity"] = $request->quantity;
                }
            } else {
                throw new Exception("item_not_found", 404);
            }

            $items->cart_items = array_values($cartItems);
            $items->save();

            return response()->json([
                "status"  => true,
                "message" => "updated",
                "data"    => new CartResource($items)
            ], 200);
        } catch (\Throwable $th) {
            return handleException($th);
        }
    }

    public function checkout(CheckoutRequest $request): JsonResponse
    {
        try {
            $user          = auth()->user();
            $cart          = collect(new CartResource($user->cart()->first()));
            $filteredItems = array_values(array_filter($cart["cart_items"]->resource, fn($item) => in_array($item['id'], $request->product_id)));
            $amount        = array_sum(array_column($filteredItems, 'price'));
            $transaction   = null;

            DB::transaction(function () use ($user, $amount, &$transaction, $filteredItems, $request) {
                $transaction = Transaction::create([
                    "user_id"     => $user->id,
                    "amount"      => $amount,
                    "description" => $request->description
                ]);

                $now = now();

                $transactionDetails = collect($filteredItems)->map(function ($item) use ($transaction, $now) {
                    return [
                        "transaction_id" => $transaction->id,
                        "product_id"     => $item["id"],
                        "quantity"       => $item["quantity"],
                        "subtotal"       => $item["price"] * $item["quantity"],
                        "created_at"     => $now,
                        "updated_at"     => $now,
                    ];
                })->all();

                // create transaction details
                TransactionDetail::insert($transactionDetails);

                // update the quantity in products table
                foreach ($filteredItems as $item) {
                    $query = DB::selectOne('UPDATE products SET quantity = quantity - ? WHERE id = ? RETURNING id, quantity', [$item["quantity"], $item["id"]]);

                    if ($query->quantity < 0) {
                        throw new Exception("One or more items in your shopping cart are out of stock.", 422);
                    }
                }

                // delete cart items
                $newCartItems = array_values(array_filter($user->cart()->first()->cart_items, function ($item) use ($request) {
                    return !in_array($item['id'], $request->product_id);
                }));

                $user->cart()->update(["cart_items" => $newCartItems]);
            }, 4);

            return response()->json([
                "status"  => true,
                "message" => "checked_out",
                "data"    => new TransactionResource($transaction)
            ], 200);
        } catch (\Throwable $th) {
            return handleException($th);
        }
    }
}
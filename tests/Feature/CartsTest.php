<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Str;
use Tests\TestCase;
use App\Models\User;

class CartsTest extends TestCase
{
    use RefreshDatabase;

    public $headers = [
        "Accept"       => "application/json",
        "Content-Type" => "application/json",
    ];

    public $product;

    public $products;

    public function setUp(): void
    {
        parent::setUp();

        $this->create_products();

        $this->product = Product::first();

        $this->products = Product::all();
    }

    public function tearDown(): void
    {

        Cart::truncate();

        parent::tearDown();
    }

    public function create_products()
    {
        User::factory()->count(10)->create();
        Product::factory()->count(10)->create();
    }

    public function test_add_to_cart_no_session()
    {
        $response = $this
            ->withHeaders($this->headers)->post('/api/user/cart');

        $response->assertUnauthorized();
    }

    public function test_add_to_cart_no_session_without_header()
    {
        $response = $this->post('/api/user/cart');

        $response->assertStatus(401);
    }

    public function test_add_cart_valid()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->withHeaders($this->headers);

        $response = $this->json("POST", "/api/user/cart", [
            "product_id" => $this->product->id,
            "quantity"   => 1,
        ]);

        // $response->dump();

        $response->assertCreated();
    }

    public function test_add_cart_quantity_not_integer()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->withHeaders($this->headers);

        $response = $this->json("POST", "/api/user/cart", [
            "product_id" => $this->product->id,
            "quantity"   => 1.1,
        ]);

        $response->assertUnprocessable()
            ->assertSee("The quantity must be an integer");
    }

    public function test_add_cart_invalid_uuid()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->withHeaders($this->headers);

        $response = $this->json("POST", "/api/user/cart", [
            "product_id" => "invalid_id",
            "quantity"   => 1,
        ]);

        $response->assertUnprocessable();

        $response->assertSee("The product id must be a valid UUID");

    }

    public function test_add_cart_valid_uuid_but_random()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->withHeaders($this->headers);

        $response = $this->json("POST", "/api/user/cart", [
            "product_id" => Str::uuid()->toString(),
            "quantity"   => 1,
        ]);

        $response->assertUnprocessable();

        $response->assertSee("The selected product id is invalid.");
    }

    public function test_add_cart_without_quantity()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->withHeaders($this->headers);

        $response = $this->json("POST", "/api/user/cart", [
            "product_id" => $this->product->id
        ]);

        $response->assertUnprocessable();

        $response->assertSee("The quantity field is required");
    }
    public function test_add_cart_without_product_id()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->withHeaders($this->headers);

        $response = $this->json("POST", "/api/user/cart", [
            "quantity" => 1
        ]);

        $response->assertUnprocessable();

        $response->assertSee("The product id field is required.");
    }
    public function test_add_cart_without_payload()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->withHeaders($this->headers);

        $response = $this->json("POST", "/api/user/cart");

        $response->assertUnprocessable();

        $response->assertSee("The product id field is required.");

        $response->assertSee("The quantity field is required");
    }
    public function test_add_cart_with_quantity_as_string()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->withHeaders($this->headers);

        $response = $this->json("POST", "/api/user/cart", [
            "product_id" => $this->product->id,
            "quantity"   => "rand_string"
        ]);

        $response->assertUnprocessable();
    }

    public function test_add_cart_more_than_one_products_and_get_items()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->withHeaders($this->headers);

        foreach ($this->products as $key => $product) {
            $response = $this->json("POST", "/api/user/cart", [
                "product_id" => $product->id,
                "quantity"   => 1
            ]);

            $response->assertCreated();
        }

        $getCartResponse = $this->json("GET", "/api/user/cart");

        // $getCartResponse->dump();

        $getCartResponse->assertOk()
            ->assertJson([
                "status" => true,
            ])->assertJsonCount(10, "data.cart_items");

    }

    public function test_get_carts_but_cart_not_available()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->withHeaders($this->headers);

        $response = $this->json("GET", "/api/user/cart");

        $response->assertNotFound()
            ->assertJson([
                "status"  => false,
                "message" => "data_not_found"
            ]);
    }
    public function test_get_cart()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->withHeaders($this->headers);

        $addToCartResponse = $this->json("POST", "/api/user/cart", [
            "product_id" => $this->product->id,
            "quantity"   => 3
        ]);

        $addToCartResponse->assertCreated()
            ->assertJson([
                "status"  => true,
                "message" => "created"
            ])
            ->assertJsonCount(1, "data.cart_items");

        $addToCartResponse = $this->json("POST", "/api/user/cart", [
            "product_id" => $this->product->id,
            "quantity"   => 3
        ]);

        $addToCartResponse->assertOK()
            ->assertJson([
                "status"  => true,
                "message" => "updated",
            ])
            ->assertJsonFragment([
                "id"       => $this->product->id,
                "quantity" => 6
            ])
            ->assertJsonCount(1, "data.cart_items");

        $response = $this->json("GET", "/api/user/cart");

        $response->assertStatus(200)
            ->assertJson([
                "status"  => true,
                "message" => "found"
            ]);
    }

    public function test_delete_item_in_cart()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->withHeaders($this->headers);

        $addToCartResponse = $this->json("POST", "/api/user/cart", [
            "product_id" => $this->product->id,
            "quantity"   => 3
        ]);

        $addToCartResponse->assertCreated()
            ->assertJson([
                "status"  => true,
                "message" => "created"
            ])
            ->assertJsonCount(1, "data.cart_items")->assertSee($this->product->id);

        $response->json("delete", "/api/user/cart", [
            "product_id" => $this->product->id
        ])->assertOk()->assertJson([
                    "status"  => true,
                    "message" => "deleted"
                ])->assertDontSee($this->product->id);

        // try to delete item that already deleted
        $deletedResponse = $this->json("delete", "/api/user/cart", [
            "product_id" => $this->product->id
        ]);

        // $deletedResponse->dump();

        $deletedResponse->assertStatus(404)
            ->assertJson([
                "status"  => false,
                "message" => "item_not_found"
            ]);
    }

    public function test_delete_item_in_cart_without_body()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->withHeaders($this->headers)
            ->json("delete", "/api/user/cart");

        $response->assertUnprocessable();
    }

    public function test_delete_item_in_cart_without_authentication()
    {
        $response = $this->withHeaders($this->headers)
            ->json("delete", "/api/user/cart");

        $response->assertUnauthorized()
            ->assertJson([
                "message" => "Unauthenticated."
            ]);
    }

    public function test_delete_without_any_items_in_cart()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->withHeaders($this->headers)
            ->json("delete", "/api/user/cart", [
                "product_id" => $this->product->id
            ]);

        // $response->dump();

        $response->assertStatus(404)
            ->assertJson([
                "status"  => false,
                "message" => "data_not_found"
            ]);
    }

    public function test_delete_cart_item_random_uuid()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->withHeaders($this->headers)
            ->json("delete", "/api/user/cart", [
                "product_id" => Str::uuid()->toString()
            ]);

        $response->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
            ])
            ->assertSee("The selected product id is invalid.");
    }

    public function test_update_item_in_cart()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->withHeaders($this->headers);

        $addToCartResponse = $this->json("POST", "/api/user/cart", [
            "product_id" => $this->product->id,
            "quantity"   => 3
        ]);

        $addToCartResponse->assertCreated()
            ->assertJson([
                "status"  => true,
                "message" => "created"
            ])
            ->assertJsonCount(1, "data.cart_items")->assertSee($this->product->id);

        $response->json("put", "/api/user/cart", [
            "product_id" => $this->product->id,
            "quantity"   => 1
        ])->assertOk()->assertJson([
                    "status"  => true,
                    "message" => "updated"
                ])->assertSee([
                    "id"       => $this->product->id,
                    "quantity" => 1
                ]);

        // quantity == 0 means item deleted from cart
        $deletedResponse = $this->json("put", "/api/user/cart", [
            "product_id" => $this->product->id,
            "quantity"   => 0
        ]);

        $deletedResponse->assertStatus(200)
            ->assertJson([
                "status"  => true,
                "message" => "updated"
            ])
            ->assertJsonCount(0, "data.cart_items");
    }

    public function test_update_cart_item_random_uuid()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->withHeaders($this->headers)
            ->json("put", "/api/user/cart", [
                "product_id" => Str::uuid()->toString(),
                "quantity"   => 10
            ]);

        $response->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
            ])
            ->assertSee("The selected product id is invalid.");
    }

    public function test_update_cart_item_not_found()
    {
        $user = User::factory()->create();

        $addToCartResponse = $this->actingAs($user)->withHeaders($this->headers)
            ->json("post", "/api/user/cart", [
                "product_id" => $this->product->id,
                "quantity"   => 10
            ]);


        $addToCartResponse->assertCreated();

        $anotherProduct = $this->products->whereNotIn("id", $this->product->id)->first();

        // dump($anotherProduct);

        $response = $this->actingAs($user)->withHeaders($this->headers)
            ->json("put", "/api/user/cart", [
                "product_id" => $anotherProduct->id,
                "quantity"   => 1
            ]);

        $response->assertStatus(404)
            ->assertSee("item_not_found");
    }

    public function test_update_without_any_items_in_cart()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->withHeaders($this->headers)
            ->json("put", "/api/user/cart", [
                "product_id" => $this->product->id,
                "quantity"   => 10
            ]);

        $response->assertStatus(404)
            ->assertJson([
                "status"  => false,
                "message" => "data_not_found"
            ]);
    }

    public function test_update_item_in_cart_with_string_payload()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->withHeaders($this->headers);

        $addToCartResponse = $this->json("POST", "/api/user/cart", [
            "product_id" => $this->product->id,
            "quantity"   => 3
        ]);

        $addToCartResponse->assertCreated()
            ->assertJson([
                "status"  => true,
                "message" => "created"
            ])
            ->assertJsonCount(1, "data.cart_items")->assertSee($this->product->id);

        $response->json("put", "/api/user/cart", [
            "product_id" => $this->product->id,
            "quantity"   => 1
        ])->assertOk()->assertJson([
                    "status"  => true,
                    "message" => "updated"
                ])->assertSee([
                    "id"       => $this->product->id,
                    "quantity" => 1
                ]);

        // quantity == 0 means item deleted from cart
        $deletedResponse = $this->json("put", "/api/user/cart", [
            "product_id" => $this->product->id,
            "quantity"   => "0"
        ]);

        // $deletedResponse->dump();

        $deletedResponse->assertStatus(200)
            ->assertJson([
                "status"  => true,
                "message" => "updated"
            ])
            ->assertJsonCount(0, "data.cart_items");
    }

    public function test_add_cart_quantity_over_stock()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->withHeaders($this->headers)
            ->json("POST", "/api/user/cart", [
                "product_id" => $this->product->id,
                "quantity"   => $this->product->quantity + 1000
            ])
            ->assertUnprocessable()
            ->assertSee("The stock is invalid. Quantity exceeds available stock");
    }

    public function test_add_cart_quantity_same_as_stock()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->withHeaders($this->headers)
            ->json("POST", "/api/user/cart", [
                "product_id" => $this->product->id,
                "quantity"   => $this->product->quantity
            ])
            ->assertCreated();
    }

    public function test_update_cart_quantity_over_stock()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->withHeaders($this->headers)
            ->json("PUT", "/api/user/cart", [
                "product_id" => $this->product->id,
                "quantity"   => $this->product->quantity + 1000
            ])
            ->assertUnprocessable()
            ->assertSee("The stock is invalid. Quantity exceeds available stock");
    }

    public function test_checkout_valid()
    {
        $user = User::factory()->create();

        // add to cart first
        $addToCartResponse = $this->actingAs($user)->withHeaders($this->headers)
            ->json("POST", "/api/user/cart", [
                'product_id' => $this->product->id,
                'quantity'   => 1
            ]);

        $addToCartResponse
            ->assertCreated()
            ->assertJson([
                "status"  => true,
                "message" => "created"
            ])
            ->assertJsonCount(1, "data.cart_items");

        // do checkout request 
        $checkoutResponse = $this->json("POST", "/api/user/cart/checkout", [
            "product_id" => [$user->cart()->first()->cart_items[0]["id"]]
        ]);

        $checkoutResponse
            ->assertOk()
            ->assertJson([
                "status"  => true,
                "message" => "checked_out"
            ]);

        $checkToCartResponse = $this->actingAs($user)->withHeaders($this->headers)
            ->json("GET", "/api/user/cart");

        $checkToCartResponse
            ->assertOk()
            ->assertJsonCount(0, "data.cart_items");
    }

    public function test_checkout_quantity_0()
    {
        $firstUser = User::factory()->create();

        $secondUser = User::factory()->create();

        // add to cart for user 1
        $responseFirstUser = $this->actingAs($firstUser)->json("POST", "/api/user/cart", [
            "product_id" => $this->product->id,
            "quantity"   => $this->product->quantity
        ]);

        $responseFirstUser->assertJson([
            "status"  => true,
            "message" => "created"
        ])
            ->assertJsonCount(1, "data.cart_items")
            ->assertJsonFragment(["quantity" => $this->product->quantity])
            ->assertCreated();

        // add to cart for user 2
        $responseSecondUser = $this->actingAs($secondUser)->json("POST", "/api/user/cart", [
            "product_id" => $this->product->id,
            "quantity"   => $this->product->quantity
        ]);

        $responseSecondUser->assertJson([
            "status"  => true,
            "message" => "created"
        ])
            ->assertJsonCount(1, "data.cart_items")
            ->assertJsonFragment(["quantity" => $this->product->quantity])
            ->assertCreated();

        // get cart first user. expected to response = OK, with cart_items is_checkoutable => true
        $responseGetCartFirstUser = $this->actingAs($firstUser)->json("GET", "/api/user/cart");

        $responseGetCartFirstUser //->dump()
            ->assertJson([
                "status"  => true,
                "message" => "found"
            ])
            ->assertJsonCount(1, "data.cart_items")
            ->assertSee(["is_checkoutable" => true])
            ->assertOk();

        // checkout first user, expected response = success checkout
        $responseCheckoutFirstUser = $this->actingAs($firstUser)->json("POST", "/api/user/cart/checkout", [
            "product_id"  => [$this->product->id],
            "description" => "example description"
        ]);

        $responseCheckoutFirstUser //->dump()
            ->assertJson([
                "status"  => true,
                "message" => "checked_out"
            ])
            ->assertSee(["description" => "example description"])
            ->assertOk();

        // get cart second user. expected to response = OK, with cart_items is_checkoutable => false
        $responseGetCartSecondUser = $this->actingAs($secondUser)->json("GET", "/api/user/cart");

        $responseGetCartSecondUser //->dump()
            ->assertJson([
                "status"  => true,
                "message" => "found"
            ])
            ->assertJsonCount(1, "data.cart_items")
            ->assertSee(["is_checkoutable" => false])
            ->assertOk();

        // check cart again, expected response = OK, with 0 cart_items
        $responseReGetCartFirstUser = $this->actingAs($firstUser)->json("GET", "/api/user/cart");

        $responseReGetCartFirstUser //->dump()
            ->assertJson([
                "status"  => true,
                "message" => "found"
            ])
            ->assertJsonCount(0, "data.cart_items")
            ->assertOk();

        // force checkout second user, expected response = failed checkout
        $responseCheckoutSecondUser = $this->actingAs($secondUser)->json("POST", "/api/user/cart/checkout", [
            "product_id"  => [$this->product->id],
            "description" => "example description"
        ]);

        $responseCheckoutSecondUser //->dump()
            ->assertJson([
                "status"  => false,
                "message" => "One or more items in your shopping cart are out of stock."
            ])
            ->assertStatus(422);
    }

    public function test_subtotal_calculations()
    {
        $checkoutQuantity = 5;
        $productPrice     = 50000;
        $product          = Product::factory(["price" => $productPrice, "quantity" => 100])->create();
        $user             = User::factory()->create();

        $addToCartResponse = $this->actingAs($user)->json("POST", "/api/user/cart", [
            "product_id" => $product->id,
            "quantity"   => $checkoutQuantity,
        ]);

        $addToCartResponse //->dump()
            ->assertJson([
                "status"  => true,
                "message" => "created"
            ])
            ->assertSee(["subtotal" => $checkoutQuantity * $productPrice])
            ->assertCreated();
    }

    public function test_checkout_but_cart_not_existed()
    {
        $user = User::factory()->create();

        $addToCartResponse = $this->actingAs($user)->json("POST", "/api/user/cart/checkout", [
            "product_id" => $this->product->id,
        ]);

        $addToCartResponse //->dump()
            ->assertUnprocessable()
            ->assertJson([
                "status"   => false,
                "messages" => "The given data was invalid.",
                "errors"   => [
                    "cart" => "Cart not found.",
                ]
            ]);
    }

    public function test_checkout_without_headers()
    {
        $user = User::factory()->create();

        $addToCartResponse = $this->actingAs($user)->json("POST", "/api/user/cart/checkout", [
            "product_id" => $this->product->id,
        ]);

        $addToCartResponse //->dump()
            ->assertUnprocessable()
            ->assertJson([
                "status"   => false,
                "messages" => "The given data was invalid.",
                "errors"   => [
                    "cart" => "Cart not found.",
                ]
            ]);
    }
}
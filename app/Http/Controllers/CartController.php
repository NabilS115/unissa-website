<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display the user's cart
     */
    public function index()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        $totalPrice = $cartItems->sum('total_price');

        return view('cart.index', compact('cartItems', 'totalPrice'));
    }

    /**
     * Add a product to the cart
     */
    public function addToCart(Request $request, Product $product)
    {
        try {
            $request->validate([
                'quantity' => 'required|integer|min:1|max:100'
            ]);

            $quantity = $request->input('quantity');
            $userId = Auth::id();

            // Check if item already exists in cart
            $cartItem = Cart::where('user_id', $userId)
                ->where('product_id', $product->id)
                ->first();

            if ($cartItem) {
                // Update quantity if item exists
                $cartItem->quantity += $quantity;
                $cartItem->save();
                $message = 'Product quantity updated in cart!';
            } else {
                // Create new cart item
                Cart::create([
                    'user_id' => $userId,
                    'product_id' => $product->id,
                    'quantity' => $quantity
                ]);
                $message = 'Product added to cart!';
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'cart_count' => Cart::where('user_id', $userId)->sum('quantity')
                ]);
            }

            return redirect()->back()->with('success', $message);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid quantity. Please enter a number between 1 and 100.',
                    'errors' => $e->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to add item to cart. Please try again.',
                    'error' => $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Failed to add item to cart. Please try again.');
        }
    }

    /**
     * Add a product to the cart (simple version for testing)
     */
    public function addToCartSimple(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|integer|exists:products,id',
                'quantity' => 'required|integer|min:1|max:100'
            ]);

            $productId = $request->input('product_id');
            $quantity = $request->input('quantity');
            $userId = Auth::id();

            // Check if item already exists in cart
            $cartItem = Cart::where('user_id', $userId)
                ->where('product_id', $productId)
                ->first();

            if ($cartItem) {
                // Update quantity if item exists
                $cartItem->quantity += $quantity;
                $cartItem->save();
                $message = 'Product quantity updated in cart!';
            } else {
                // Create new cart item
                Cart::create([
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'quantity' => $quantity
                ]);
                $message = 'Product added to cart!';
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'cart_count' => Cart::where('user_id', $userId)->sum('quantity')
                ]);
            }

            return redirect()->back()->with('success', $message);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid data provided.',
                    'errors' => $e->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to add item to cart. Please try again.',
                    'error' => $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Failed to add item to cart. Please try again.');
        }
    }

    /**
     * Update the quantity of a cart item
     */
    public function updateQuantity(Request $request, Cart $cartItem)
    {
        // Ensure the cart item belongs to the authenticated user
        if ($cartItem->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1|max:100'
        ]);

        // Check product stock if tracking is enabled
        $product = $cartItem->product;
        if ($product->track_stock && $request->quantity > $product->stock_quantity) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => "Only {$product->stock_quantity} units available in stock.",
                    'max_quantity' => $product->stock_quantity
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors(['quantity' => "Only {$product->stock_quantity} units available in stock."])
                ->withInput();
        }

        $cartItem->update([
            'quantity' => $request->quantity
        ]);

        if ($request->ajax()) {
            // Get updated cart totals
            $cartItems = Cart::where('user_id', Auth::id())->get();
            $cartTotal = $cartItems->sum('total_price');
            $totalItems = $cartItems->sum('quantity');
            
            return response()->json([
                'success' => true,
                'message' => 'Quantity updated!',
                'item_total' => $cartItem->total_price,
                'cart_total' => $cartTotal,
                'total_items' => $totalItems,
                'max_quantity' => $product->track_stock ? $product->stock_quantity : 100
            ]);
        }

        return redirect()->back()->with('success', 'Quantity updated!');
    }

    /**
     * Remove an item from the cart
     */
    public function removeItem(Cart $cartItem)
    {
        // Ensure the cart item belongs to the authenticated user
        if ($cartItem->user_id !== Auth::id()) {
            abort(403);
        }

        $cartItem->delete();

        return redirect()->back()->with('success', 'Item removed from cart!');
    }

    /**
     * Clear all items from the cart
     */
    public function clearCart()
    {
        Cart::where('user_id', Auth::id())->delete();

        return redirect()->back()->with('success', 'Cart cleared!');
    }

    /**
     * Get cart count for AJAX requests
     */
    public function getCartCount()
    {
        $count = Cart::where('user_id', Auth::id())->sum('quantity');
        
        return response()->json(['count' => $count]);
    }
}

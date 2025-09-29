<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;

class SearchController extends Controller
{
    public function suggestions(Request $request)
    {
        $query = $request->get('q', '');
        $scope = $request->get('scope', 'all');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }
        
        $suggestions = [];
        
        switch ($scope) {
            case 'products':
                $suggestions = $this->getProductSuggestions($query);
                break;
            case 'reviews':
                $suggestions = $this->getReviewSuggestions($query);
                break;
            case 'users':
                if (auth()->check() && auth()->user()->role === 'admin') {
                    $suggestions = $this->getUserSuggestions($query);
                }
                break;
            case 'all':
            default:
                $suggestions = array_merge(
                    $this->getProductSuggestions($query, 5),
                    $this->getReviewSuggestions($query, 3)
                );
                if (auth()->check() && auth()->user()->role === 'admin') {
                    $suggestions = array_merge($suggestions, $this->getUserSuggestions($query, 2));
                }
                break;
        }
        
        return response()->json(array_slice($suggestions, 0, 10));
    }
    
    public function search(Request $request)
    {
        $query = $request->get('search', '');
        $scope = $request->get('scope', 'all');
        $perPage = 12;
        
        $results = [
            'query' => $query,
            'scope' => $scope,
            'products' => collect(),
            'reviews' => collect(),
            'users' => collect(),
            'total' => 0,
            'searchTerms' => $this->extractSearchTerms($query) // Add search terms for highlighting
        ];
        
        if (strlen($query) >= 2) {
            switch ($scope) {
                case 'products':
                    $results['products'] = $this->searchProducts($query)->paginate($perPage);
                    $results['total'] = $results['products']->total();
                    break;
                case 'reviews':
                    $results['reviews'] = $this->searchReviews($query)->paginate($perPage);
                    $results['total'] = $results['reviews']->total();
                    break;
                case 'users':
                    if (auth()->check() && auth()->user()->role === 'admin') {
                        $results['users'] = $this->searchUsers($query)->paginate($perPage);
                        $results['total'] = $results['users']->total();
                    }
                    break;
                case 'all':
                default:
                    $results['products'] = $this->searchProducts($query)->paginate(8);
                    $results['reviews'] = $this->searchReviews($query)->paginate(6);
                    if (auth()->check() && auth()->user()->role === 'admin') {
                        $results['users'] = $this->searchUsers($query)->paginate(4);
                        $results['total'] = $results['products']->total() + $results['reviews']->total() + $results['users']->total();
                    } else {
                        $results['total'] = $results['products']->total() + $results['reviews']->total();
                    }
                    break;
            }
        }
        
        return view('search.results', $results);
    }
    
    private function getProductSuggestions($query, $limit = 10)
    {
        return Product::where('name', 'LIKE', "%{$query}%")
            ->orWhere('category', 'LIKE', "%{$query}%")
            ->orWhere('desc', 'LIKE', "%{$query}%")
            ->limit($limit)
            ->get()
            ->map(function($product) {
                return [
                    'type' => 'product',
                    'title' => $product->name,
                    'subtitle' => $product->category,
                    'url' => "/review/{$product->id}"
                ];
            })
            ->toArray();
    }
    
    private function getReviewSuggestions($query, $limit = 10)
    {
        return Review::with('product', 'user')
            ->where('review', 'LIKE', "%{$query}%")
            ->limit($limit)
            ->get()
            ->map(function($review) {
                return [
                    'type' => 'review',
                    'title' => substr($review->review, 0, 50) . '...',
                    'subtitle' => 'Review for ' . ($review->product->name ?? 'Unknown Product'),
                    'url' => "/review/{$review->product_id}"
                ];
            })
            ->toArray();
    }
    
    private function getUserSuggestions($query, $limit = 10)
    {
        return User::where('name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->limit($limit)
            ->get()
            ->map(function($user) {
                return [
                    'type' => 'user',
                    'title' => $user->name,
                    'subtitle' => $user->email,
                    'url' => "#" // Could link to user profile if available
                ];
            })
            ->toArray();
    }
    
    private function searchProducts($query)
    {
        return Product::where('name', 'LIKE', "%{$query}%")
            ->orWhere('category', 'LIKE', "%{$query}%")
            ->orWhere('desc', 'LIKE', "%{$query}%")
            ->orderBy('name');
    }
    
    private function searchReviews($query)
    {
        return Review::with('product', 'user')
            ->where('review', 'LIKE', "%{$query}%")
            ->orderBy('created_at', 'desc');
    }
    
    private function searchUsers($query)
    {
        return User::where('name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->orderBy('name');
    }
    
    public function catalogSearch(Request $request)
    {
        $query = $request->get('query', '');
        $type = $request->get('type', 'all');
        $category = $request->get('category', 'All');
        $sort = $request->get('sort', '');
        $page = (int) $request->get('page', 1);
        $perPage = (int) $request->get('per_page', 8);
        
        $productsQuery = Product::query();
        
        // Filter by type (food/merch)
        if ($type !== 'all') {
            $productsQuery->where('type', $type);
        }
        
        // Filter by category
        if ($category !== 'All') {
            $productsQuery->where('category', $category);
        }
        
        // Filter by search query
        if (!empty($query)) {
            $productsQuery->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('desc', 'LIKE', "%{$query}%");
            });
        }
        
        // Apply sorting
        switch ($sort) {
            case 'name':
                $productsQuery->orderBy('name');
                break;
            case 'category':
                $productsQuery->orderBy('category');
                break;
            case 'rating':
                // For now, just order by name. Rating sorting would require joins
                $productsQuery->orderBy('name');
                break;
            default:
                $productsQuery->orderBy('created_at', 'desc');
                break;
        }
        
        // Paginate
        $products = $productsQuery->paginate($perPage, ['*'], 'page', $page);
        
        return response()->json([
            'products' => $products->items(),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total()
            ]
        ]);
    }
    
    public function getFilters(Request $request)
    {
        $type = $request->get('type', 'all');
        
        $categoriesQuery = Product::query();
        
        if ($type !== 'all') {
            $categoriesQuery->where('type', $type);
        }
        
        $categories = $categoriesQuery->pluck('category')->unique()->values()->all();
        
        return response()->json([
            'categories' => $categories
        ]);
    }
    
    // Add method to extract search terms
    private function extractSearchTerms($query)
    {
        // Split query into individual terms and clean them
        $terms = preg_split('/\s+/', trim($query));
        return array_filter($terms, function($term) {
            return strlen($term) >= 2; // Only include terms with 2+ characters
        });
    }
}

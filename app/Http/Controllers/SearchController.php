<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class SearchController extends Controller
{
    public function suggestions(Request $request)
    {
        $query = $request->get('q', '');
        $scope = $request->get('scope', 'all');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }
        
        // Create cache key based on query and scope
        $cacheKey = "search.suggestions." . md5($query . $scope . (auth()->id() ?? 'guest'));
        
        $suggestions = Cache::remember($cacheKey, now()->addMinutes(15), function () use ($query, $scope) {
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
            
            return array_slice($suggestions, 0, 10);
        });
        
        return response()->json($suggestions);
    }
    
    public function search(Request $request)
    {
        $query = $request->get('search', '');
        $scope = $request->get('scope', 'all');
        $page = $request->get('page', 1);
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
            // Create cache key for search results
            $cacheKey = "search.results." . md5($query . $scope . $page . (auth()->id() ?? 'guest'));
            
            $cachedResults = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($query, $scope, $perPage) {
                $searchResults = [];
                
                switch ($scope) {
                    case 'products':
                        $products = $this->searchProducts($query)->paginate($perPage);
                        $searchResults['products'] = $products;
                        $searchResults['total'] = $products->total();
                        break;
                    case 'reviews':
                        $reviews = $this->searchReviews($query)->paginate($perPage);
                        $searchResults['reviews'] = $reviews;
                        $searchResults['total'] = $reviews->total();
                        break;
                    case 'users':
                        if (auth()->check() && auth()->user()->role === 'admin') {
                            $users = $this->searchUsers($query)->paginate($perPage);
                            $searchResults['users'] = $users;
                            $searchResults['total'] = $users->total();
                        }
                        break;
                    case 'all':
                    default:
                        $products = $this->searchProducts($query)->paginate(8);
                        $reviews = $this->searchReviews($query)->paginate(6);
                        $searchResults['products'] = $products;
                        $searchResults['reviews'] = $reviews;
                        if (auth()->check() && auth()->user()->role === 'admin') {
                            $users = $this->searchUsers($query)->paginate(4);
                            $searchResults['users'] = $users;
                            $searchResults['total'] = $products->total() + $reviews->total() + $users->total();
                        } else {
                            $searchResults['total'] = $products->total() + $reviews->total();
                        }
                        break;
                }
                
                return $searchResults;
            });
            
            $results = array_merge($results, $cachedResults);
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
                    'url' => "/product/{$product->id}"
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
                    'url' => "/product/{$review->product_id}"
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

// Header interactions and search suggestions (extracted from Blade)
// Expects window.__searchSuggestionsUrl and window.__cartCountUrl to be set by Blade bootstrap.

console.log('Header.js is loading...');

(function() {
    console.log('Header.js IIFE started');
    const SEARCH_URL = window.__searchSuggestionsUrl || '/search/suggestions';
    const CART_COUNT_URL = window.__cartCountUrl;
    
    console.log('🔍 Debug Cart URL:', {
        cartCountUrl: CART_COUNT_URL,
        windowCartCountUrl: window.__cartCountUrl,
        isNull: CART_COUNT_URL === null,
        isUndefined: CART_COUNT_URL === undefined
    });

    // --- Search suggestions ---
    let searchTimeout;
    function highlightText(text, searchTerm) {
        if (!searchTerm || searchTerm.length < 2) return text;
        const regex = new RegExp(`(${searchTerm.replace(/[.*+?^${}()|[\\]\\]/g, '\\$&')})`, 'gi');
        return text.replace(regex, '<mark class="bg-yellow-200 text-gray-900 font-medium px-1 rounded">$1</mark>');
    }

    function getTypeIcon(type) {
        switch(type) {
            case 'product':
                return '<svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/></svg>';
            case 'review':
                return '<svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/></svg>';
            case 'user':
                return '<svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>';
            default:
                return '<svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/></svg>';
        }
    }

    function getTypeColor(type) {
        switch(type) {
            case 'product': return 'bg-teal-500';
            case 'review': return 'bg-yellow-500';
            case 'user': return 'bg-blue-500';
            default: return 'bg-gray-500';
        }
    }

    async function fetchSuggestions(query, scope) {
        const suggestionsBox = document.getElementById('search-suggestions');
        if (!query || query.length < 2) {
            if (suggestionsBox) suggestionsBox.style.display = 'none';
            return;
        }

        try {
            const resp = await fetch(`${SEARCH_URL}?q=${encodeURIComponent(query)}&scope=${encodeURIComponent(scope)}`);
            const suggestions = await resp.json();
            if (suggestions && suggestions.length > 0) displaySuggestions(suggestions, query);
            else if (suggestionsBox) suggestionsBox.style.display = 'none';
        } catch (err) {
            console.error('Error fetching suggestions:', err);
            if (suggestionsBox) suggestionsBox.style.display = 'none';
        }
    }

    function displaySuggestions(suggestions, searchTerm) {
        const suggestionsBox = document.getElementById('search-suggestions');
        if (!suggestionsBox) return;
        const html = suggestions.map(suggestion => {
            const typeIcon = getTypeIcon(suggestion.type);
            const typeColor = getTypeColor(suggestion.type);
            const highlightedTitle = highlightText(suggestion.title, searchTerm);
            const highlightedSubtitle = highlightText(suggestion.subtitle, searchTerm);
            return `
                <div class="suggestion-item px-4 py-3 cursor-pointer hover:bg-gray-50 border-b border-gray-100 last:border-b-0" data-url="${suggestion.url}">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 w-6 h-6 ${typeColor} rounded-full flex items-center justify-center">${typeIcon}</div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-medium text-gray-900 truncate">${highlightedTitle}</div>
                            <div class="text-xs text-gray-500 truncate">${highlightedSubtitle}</div>
                        </div>
                        <div class="text-xs text-gray-400 capitalize">${suggestion.type}</div>
                    </div>
                </div>
            `;
        }).join('');
        suggestionsBox.innerHTML = html;
        suggestionsBox.style.display = 'block';

        document.querySelectorAll('.suggestion-item').forEach(item => {
            item.addEventListener('mousedown', function(e) {
                e.preventDefault();
                const url = this.getAttribute('data-url');
                if (url && url !== '#') window.location.href = url;
            });
        });
    }

    // Wire up search input behavior
    function wireSearch() {
        const searchInput = document.getElementById('main-search-input');
        const searchScope = document.getElementById('search-scope');
        const suggestionsBox = document.getElementById('search-suggestions');
        if (!searchInput || !searchScope) return;

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            const scope = searchScope.value;
            searchTimeout = setTimeout(() => fetchSuggestions(query, scope), 300);
        });

        searchScope.addEventListener('change', function() {
            const query = searchInput.value.trim();
            const scope = this.value;
            if (query.length >= 2) fetchSuggestions(query, scope);
        });

        searchInput.addEventListener('blur', function() {
            setTimeout(() => { if (suggestionsBox) suggestionsBox.style.display = 'none'; }, 200);
        });

        searchInput.addEventListener('focus', function() {
            const query = this.value.trim();
            if (query.length >= 2) fetchSuggestions(query, searchScope.value);
        });
    }

    // --- Header interactions (search/profile dropdowns) ---
    function initializeHeaderInteractions() {
        const searchIcon = document.getElementById('searchbar-icon');
        const searchGroup = document.getElementById('searchbar-group');
        const searchDropdown = document.getElementById('searchbar-dropdown');
        const profileIcon = document.getElementById('profileMenuButton');
        const profileGroup = document.getElementById('profile-group');
        const profileDropdown = document.getElementById('profileDropdown');

        if (!searchIcon || !profileIcon) {
            setTimeout(initializeHeaderInteractions, 100);
            return;
        }

        if (searchIcon.hasAttribute('data-initialized') || profileIcon.hasAttribute('data-initialized')) return;
        searchIcon.setAttribute('data-initialized', 'true');
        profileIcon.setAttribute('data-initialized', 'true');

        if (searchIcon && searchGroup) {
            searchIcon.addEventListener('click', function(e) {
                e.stopPropagation();
                if (profileGroup) profileGroup.classList.remove('active');
                searchGroup.classList.toggle('active');
                if (searchGroup.classList.contains('active')) {
                    setTimeout(() => { const si = document.getElementById('main-search-input'); if (si) si.focus(); }, 100);
                }
            });
        }

        if (profileIcon && profileGroup) {
            profileIcon.addEventListener('click', function(e) {
                e.stopPropagation();
                if (searchGroup) searchGroup.classList.remove('active');
                profileGroup.classList.toggle('active');
            });
        }

        document.addEventListener('click', function(e) {
            if (searchDropdown && searchDropdown.contains(e.target)) return;
            if (profileDropdown && profileDropdown.contains(e.target)) return;
            if (searchGroup) searchGroup.classList.remove('active');
            if (profileGroup) profileGroup.classList.remove('active');
        });

        if (searchDropdown) searchDropdown.addEventListener('click', function(e) { e.stopPropagation(); });
        if (profileDropdown) profileDropdown.addEventListener('click', function(e) { e.stopPropagation(); });
    }

    // --- Cart count ---
    async function loadCartCount() {
        // Skip cart count loading for unauthenticated users
        if (!window.__isAuthenticated || !CART_COUNT_URL || CART_COUNT_URL === null) {
            console.log('[CartCount] Skipping cart count - user not authenticated', { 
                isAuthenticated: window.__isAuthenticated,
                CART_COUNT_URL,
                userId: window.__userId
            });
            return;
        }
        
        console.log('[CartCount] Loading cart count for authenticated user:', {
            userId: window.__userId,
            url: CART_COUNT_URL,
            isAuth: window.__isAuthenticated
        });
        
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            const headers = { 
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            };
            
            // Only add CSRF token if it exists
            if (csrfToken) {
                headers['X-CSRF-TOKEN'] = csrfToken.getAttribute('content');
            }
            
            console.log('[CartCount] Making request with headers:', headers);
            
            const resp = await fetch(CART_COUNT_URL, { 
                method: 'GET',
                headers: headers,
                credentials: 'same-origin'
            });
            
            console.log('[CartCount] Response status:', resp.status, resp.statusText);
            
            if (!resp.ok) {
                if (resp.status === 401) {
                    console.warn('[CartCount] User session expired or invalid - disabling cart count');
                    // Mark user as not authenticated to prevent further calls
                    window.__isAuthenticated = false;
                    window.__cartCountUrl = null;
                    return;
                }
                throw new Error(`HTTP ${resp.status}: ${resp.statusText}`);
            }
            
            const data = await resp.json();
            console.log('[CartCount] Received data:', data);
            
            const cartCount = document.getElementById('cart-count');
            if (cartCount) {
                const oldCount = parseInt(cartCount.textContent) || 0;
                const newCount = data.count || 0;
                cartCount.textContent = newCount;
                cartCount.style.display = newCount > 0 ? 'flex' : 'none';
                if (newCount !== oldCount && newCount > 0) {
                    cartCount.classList.add('updated');
                    setTimeout(() => cartCount.classList.remove('updated'), 600);
                }
            }
        } catch (err) {
            console.error('[CartCount] Error loading cart count:', err);
            // Hide cart count on error and disable future calls
            const cartCount = document.getElementById('cart-count');
            if (cartCount) {
                cartCount.style.display = 'none';
            }
            // Mark as not authenticated to prevent repeated failures
            window.__isAuthenticated = false;
        }
    }

    window.updateCartCount = function(newCount) {
        // Only update cart count for authenticated users
        if (!window.__isAuthenticated || !CART_COUNT_URL) return;
        
        const cartCount = document.getElementById('cart-count');
        const mobileCartCount = document.getElementById('cart-count-mobile');
        
        if (cartCount) {
            cartCount.textContent = newCount || 0;
            cartCount.style.display = (newCount && newCount > 0) ? 'flex' : 'none';
            if (newCount > 0) { 
                cartCount.classList.add('updated'); 
                setTimeout(() => cartCount.classList.remove('updated'), 600); 
            }
        }
        
        // Also update mobile cart count
        if (mobileCartCount) {
            mobileCartCount.textContent = newCount || 0;
            mobileCartCount.style.display = (newCount && newCount > 0) ? 'flex' : 'none';
        }
    };

    // Mobile menu toggle functionality
    function initializeMobileMenu() {
        // Wait for DOM to be fully ready
        if (!document.getElementById('mobile-menu-btn')) {
            setTimeout(initializeMobileMenu, 100);
            return;
        }

        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileMenuPanel = document.getElementById('mobile-menu-panel');
        const menuIcon = document.getElementById('menu-icon');
        const closeIcon = document.getElementById('close-icon');

        // Mobile menu button toggle handler
        if (mobileMenuBtn) {
            mobileMenuBtn.onclick = function() {
                console.log('Mobile menu button clicked!');
                const isMenuOpen = !mobileMenu.classList.contains('hidden');
                
                if (isMenuOpen) {
                    // Close the menu
                    window.closeMobileMenu();
                } else {
                    // Open the menu
                    if (mobileMenu) {
                        mobileMenu.classList.remove('hidden');
                        console.log('Mobile menu shown');
                        if (mobileMenuPanel) {
                            mobileMenuPanel.style.transform = 'translateX(0)';
                            console.log('Panel transformed');
                        }
                    }
                    if (menuIcon) menuIcon.classList.add('hidden');
                    if (closeIcon) closeIcon.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                }
            };
        } else {
            console.log('Mobile menu button not found!');
        }

        // Close mobile menu function (make it global)
        window.closeMobileMenu = function() {
            if (mobileMenuPanel) {
                mobileMenuPanel.style.transform = 'translateX(100%)';
            }
            if (menuIcon) menuIcon.classList.remove('hidden');
            if (closeIcon) closeIcon.classList.add('hidden');
            document.body.style.overflow = '';
            
            setTimeout(() => {
                if (mobileMenu) mobileMenu.classList.add('hidden');
            }, 300);
        };

        // Note: Close button removed, users can tap backdrop or use window resize behavior

        // Close when clicking overlay
        if (mobileMenu) {
            mobileMenu.onclick = function(event) {
                if (event.target === mobileMenu) {
                    window.closeMobileMenu();
                }
            };
        }

        // Close mobile menu on window resize to desktop size
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) { // md breakpoint
                window.closeMobileMenu();
            }
        });

        // Sync mobile cart count with desktop
        const desktopCartCount = document.getElementById('cart-count');
        const mobileCartCount = document.getElementById('cart-count-mobile');
        
        if (desktopCartCount && mobileCartCount) {
            // Create observer for desktop cart count changes
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'childList' || mutation.type === 'characterData') {
                        mobileCartCount.textContent = desktopCartCount.textContent;
                        mobileCartCount.style.display = desktopCartCount.style.display;
                    }
                });
            });
            
            observer.observe(desktopCartCount, {
                childList: true,
                characterData: true,
                subtree: true
            });
            
            // Initial sync
            mobileCartCount.textContent = desktopCartCount.textContent;
            mobileCartCount.style.display = desktopCartCount.style.display;
        }
    }

    // Initialization
    function init() {
        wireSearch();
        initializeHeaderInteractions();
        initializeMobileMenu();
        
        // Only load cart count for authenticated users
        if (window.__isAuthenticated && CART_COUNT_URL && document.visibilityState === 'visible') {
            loadCartCount();
        }
        
        document.addEventListener('visibilitychange', function() { 
            if (window.__isAuthenticated && CART_COUNT_URL && document.visibilityState === 'visible') {
                loadCartCount(); 
            }
        });
    }

    // Run init when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            init();
            // Only load cart count if user is authenticated
            if (window.__isAuthenticated && CART_COUNT_URL) {
                loadCartCount();
            }
        });
    } else {
        init();
        // Only load cart count if user is authenticated
        if (window.__isAuthenticated && CART_COUNT_URL) {
            loadCartCount();
        }
    }
    
    // Also run with delay for dynamic content
    setTimeout(init, 500);
})();

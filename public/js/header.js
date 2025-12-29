// Header interactions (extracted from Blade)
// Expects window.__cartCountUrl to be set by Blade bootstrap.

console.log('Header.js is loading...');

(function() {
    console.log('Header.js IIFE started');
    const CART_COUNT_URL = window.__cartCountUrl;
    
    console.log('🔍 Debug Cart URL:', {
        cartCountUrl: CART_COUNT_URL,
        windowCartCountUrl: window.__cartCountUrl,
        isNull: CART_COUNT_URL === null,
        isUndefined: CART_COUNT_URL === undefined
    });

    // --- Header interactions (profile dropdown only) ---
    function initializeHeaderInteractions() {
        const profileIcon = document.getElementById('profileMenuButton');
        const profileGroup = document.getElementById('profile-group');
        const profileDropdown = document.getElementById('profileDropdown');

        if (!profileIcon) {
            setTimeout(initializeHeaderInteractions, 100);
            return;
        }

        if (profileIcon.hasAttribute('data-initialized')) return;
        profileIcon.setAttribute('data-initialized', 'true');

        if (profileIcon && profileGroup) {
            profileIcon.addEventListener('click', function(e) {
                e.stopPropagation();
                profileGroup.classList.toggle('active');
            });
        }

        document.addEventListener('click', function(e) {
            if (profileDropdown && profileDropdown.contains(e.target)) return;
            if (profileGroup) profileGroup.classList.remove('active');
        });

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
            const mobileCartCount = document.getElementById('cart-count-mobile');
            
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
            
            if (mobileCartCount) {
                const newCount = data.count || 0;
                mobileCartCount.textContent = newCount;
                mobileCartCount.style.display = newCount > 0 ? 'flex' : 'none';
            }
        } catch (err) {
            console.error('[CartCount] Error loading cart count:', err);
            // Hide cart count on error and disable future calls
            const cartCount = document.getElementById('cart-count');
            const mobileCartCount = document.getElementById('cart-count-mobile');
            if (cartCount) {
                cartCount.style.display = 'none';
            }
            if (mobileCartCount) {
                mobileCartCount.style.display = 'none';
            }
            // Mark as not authenticated to prevent repeated failures
            window.__isAuthenticated = false;
            window.__cartCountUrl = null;
        }
    }

    // --- Cart count update function for external calls ---
    function updateCartCount(newCount) {
        console.log('🛒 updateCartCount called with:', newCount);
        const cartCount = document.getElementById('cart-count');
        const mobileCartCount = document.getElementById('cart-count-mobile');
        
        if (cartCount) {
            cartCount.textContent = newCount || 0;
            cartCount.style.display = (newCount && newCount > 0) ? 'flex' : 'none';
            console.log('Updated desktop cart badge to:', newCount);
        }
        if (mobileCartCount) {
            mobileCartCount.textContent = newCount || 0;
            mobileCartCount.style.display = (newCount && newCount > 0) ? 'flex' : 'none';
            console.log('Updated mobile cart badge to:', newCount);
        }
    }

    // --- Mobile menu toggle ---
    function initializeMobileMenu() {
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuIcon = document.getElementById('menu-icon');
        const closeIcon = document.getElementById('close-icon');

        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                const isOpen = mobileMenu.classList.contains('open');
                
                if (isOpen) {
                    mobileMenu.classList.remove('open');
                    if (menuIcon) menuIcon.classList.remove('hidden');
                    if (closeIcon) closeIcon.classList.add('hidden');
                } else {
                    mobileMenu.classList.add('open');
                    if (menuIcon) menuIcon.classList.add('hidden');
                    if (closeIcon) closeIcon.classList.remove('hidden');
                }
            });

            // Close menu when clicking outside
            document.addEventListener('click', function(e) {
                if (!mobileMenu.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
                    mobileMenu.classList.remove('open');
                    if (menuIcon) menuIcon.classList.remove('hidden');
                    if (closeIcon) closeIcon.classList.add('hidden');
                }
            });
        }
    }

    // --- Initialization ---
    function init() {
        console.log('[Header] Initializing header functionality...');
        initializeHeaderInteractions();
        initializeMobileMenu();
        
        // Load cart count if authenticated
        if (window.__isAuthenticated && CART_COUNT_URL) {
            loadCartCount();
        }
        
        // Make updateCartCount globally available
        window.updateCartCount = updateCartCount;
        
        console.log('[Header] Header initialization complete');
    }

    // Run when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Also run a delayed initialization in case some elements are added later
    setTimeout(init, 500);
})();

console.log('Header.js loaded successfully');
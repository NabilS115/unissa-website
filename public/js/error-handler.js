// Global error handler for UNISSA website
(function() {
    'use strict';

    // Global error handling configuration
    const ErrorHandler = {
        // Enable/disable logging
        enableLogging: true,
        
        // Log errors to console
        log: function(error, context = '') {
            if (this.enableLogging) {
                console.group('🚨 UNISSA Error Handler');
                console.error('Context:', context);
                console.error('Error:', error);
                console.trace();
                console.groupEnd();
            }
        },

        // Handle AJAX errors
        handleAjaxError: function(xhr, status, error, context = 'AJAX Request') {
            const errorMsg = xhr.responseJSON?.message || error || 'Unknown error occurred';
            this.log(`${context}: ${errorMsg} (Status: ${status})`, context);
            
            // Show user-friendly message
            this.showUserMessage('An error occurred. Please try again.', 'error');
        },

        // Show user messages
        showUserMessage: function(message, type = 'info') {
            // Create toast notification
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm transition-all duration-300 ${
                type === 'error' ? 'bg-red-500 text-white' : 
                type === 'success' ? 'bg-green-500 text-white' : 
                'bg-blue-500 text-white'
            }`;
            toast.textContent = message;
            
            document.body.appendChild(toast);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-100%)';
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        },

        // Safe function execution wrapper
        safe: function(fn, context = 'Function') {
            return function(...args) {
                try {
                    return fn.apply(this, args);
                } catch (error) {
                    ErrorHandler.log(error, context);
                }
            };
        },

        // Initialize global error handling
        init: function() {
            // Handle uncaught JavaScript errors
            window.addEventListener('error', (event) => {
                this.log(event.error, 'Uncaught Error');
            });

            // Handle unhandled promise rejections
            window.addEventListener('unhandledrejection', (event) => {
                this.log(event.reason, 'Unhandled Promise Rejection');
                event.preventDefault(); // Prevent console spam
            });

            // Override fetch for global error handling
            const originalFetch = window.fetch;
            window.fetch = function(...args) {
                return originalFetch.apply(this, args)
                    .catch(error => {
                        ErrorHandler.log(error, 'Fetch Request');
                        throw error; // Re-throw for local handling
                    });
            };
        }
    };

    // Make ErrorHandler globally available
    window.ErrorHandler = ErrorHandler;

    // Auto-initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => ErrorHandler.init());
    } else {
        ErrorHandler.init();
    }

})();
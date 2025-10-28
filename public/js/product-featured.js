(function(){
  'use strict';

  window.storeScrollPositionAndNavigate = function(productUrl, source = 'featured') {
    try {
      const currentState = {
        source: source,
        sourcePage: window.location.pathname + window.location.search,
        scrollPosition: window.scrollY,
        timestamp: Date.now()
      };
      sessionStorage.setItem('catalogState', JSON.stringify(currentState));
    } catch (e) {
      console.error('Could not store catalog state:', e);
    }
    window.location.href = productUrl;
  };

  document.addEventListener('DOMContentLoaded', function() {
    try {
      const restoreState = sessionStorage.getItem('restoreCatalogState');
      if (restoreState) {
        const state = JSON.parse(restoreState);
        setTimeout(() => { if (state && state.scrollPosition) window.scrollTo(0, state.scrollPosition); }, 300);
        sessionStorage.removeItem('restoreCatalogState');
      }
    } catch (e) {
      console.error('Error restoring featured page state:', e);
    }
  });

})();

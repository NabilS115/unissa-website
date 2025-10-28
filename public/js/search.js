(function(){
  function safe(fn){ try{ fn(); } catch(e){ console.error(e); } }
  document.addEventListener('DOMContentLoaded', function(){
    safe(function(){
      window.storeScrollPositionAndNavigate = function(productUrl, source='search'){
        const currentState = {
          source: source,
          sourcePage: window.location.pathname + window.location.search,
          scrollPosition: window.scrollY,
          timestamp: Date.now()
        };
        try { sessionStorage.setItem('catalogState', JSON.stringify(currentState)); } catch(e){ console.warn('sessionStorage set error', e); }
        window.location.href = productUrl;
      };

      // Restore scroll position if returning from product detail
      try {
        const restoreState = sessionStorage.getItem('restoreCatalogState');
        if (restoreState) {
          const state = JSON.parse(restoreState);
          setTimeout(function(){ if (state && state.scrollPosition) window.scrollTo(0, state.scrollPosition); }, 300);
          sessionStorage.removeItem('restoreCatalogState');
        }
      } catch (e) { console.error('Error restoring search state:', e); }
    });
  });
})();

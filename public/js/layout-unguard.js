(function() {
    function unguardOverlays() {
        try {
            const nodes = document.querySelectorAll('[data-initial-hidden]');
            nodes.forEach(n => {
                n.removeAttribute('data-initial-hidden');
                if (n.hasAttribute('aria-hidden')) n.setAttribute('aria-hidden', 'false');
            });
        } catch (e) {
            console.error('guard removal error', e);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', unguardOverlays);
    } else {
        setTimeout(unguardOverlays, 0);
    }
})();

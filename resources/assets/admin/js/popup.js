document.addEventListener('DOMContentLoaded', function() {
    const popupOverlay = document.getElementById('popupOverlay');
    const popupClose = document.getElementById('popupClose');
    const dontShowAgain = document.getElementById('dontShowAgain');
    
    const STORAGE_KEY = 'popup_hidden_until';
    const TWENTY_FOUR_HOURS = 24 * 60 * 60 * 1000; // 24 hours in milliseconds

    // Check if popup should be shown
    function shouldShowPopup() {
        const hiddenUntil = localStorage.getItem(STORAGE_KEY);
        if (!hiddenUntil) return true;
        
        const now = new Date().getTime();
        const hiddenUntilTime = parseInt(hiddenUntil);
        
        if (now > hiddenUntilTime) {
            localStorage.removeItem(STORAGE_KEY);
            return true;
        }
        
        return false;
    }

    // Show popup
    function showPopup() {
        if (popupOverlay && shouldShowPopup()) {
            setTimeout(() => {
                popupOverlay.classList.add('show');
            }, 1000); // Show popup after 1 second
        }
    }

    // Hide popup
    function hidePopup() {
        if (popupOverlay) {
            popupOverlay.classList.remove('show');
            
            // If checkbox is checked, don't show for 24 hours
            if (dontShowAgain && dontShowAgain.checked) {
                const now = new Date().getTime();
                const hiddenUntil = now + TWENTY_FOUR_HOURS;
                localStorage.setItem(STORAGE_KEY, hiddenUntil.toString());
            }
        }
    }

    // Event listeners
    if (popupClose) {
        popupClose.addEventListener('click', hidePopup);
    }

    if (popupOverlay) {
        // Close popup when clicking outside the container
        popupOverlay.addEventListener('click', function(e) {
            if (e.target === popupOverlay) {
                hidePopup();
            }
        });

        // Close popup with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && popupOverlay.classList.contains('show')) {
                hidePopup();
            }
        });
    }

    // Initialize popup
    showPopup();
});

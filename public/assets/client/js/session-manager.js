// Simple session timeout tracker
class SessionManager {
    constructor() {
        this.sessionTimeout = window.sessionTimeout || 7200; // Use Laravel config or default 120 minutes
        this.checkInterval = 60 * 1000; // Check every minute
        this.lastActivityTime = Date.now();
        
        this.init();
    }
    
    init() {
        this.bindEvents();
        this.startTimer();
    }
    
    bindEvents() {
        // Track user activity
        const events = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'];
        events.forEach(event => {
            document.addEventListener(event, () => {
                this.updateActivity();
            }, { passive: true });
        });
    }
    
    updateActivity() {
        this.lastActivityTime = Date.now();
        // Send activity update to server
        this.sendActivityUpdate();
    }
    
    sendActivityUpdate() {
        // Update session on server
        fetch('/update-activity', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                timestamp: this.lastActivityTime
            })
        }).catch(() => {
            // Ignore errors for activity updates
        });
    }
    
    startTimer() {
        setInterval(() => {
            this.checkSession();
        }, this.checkInterval);
    }
    
    checkSession() {
        const currentTime = Date.now();
        const timeElapsed = (currentTime - this.lastActivityTime) / 1000;
        
        // Auto logout when session timeout
        if (timeElapsed >= this.sessionTimeout) {
            this.logout();
        }
    }
    
    logout() {
        // Redirect to logout URL
        window.location.href = '/doanh-nghiep/logout';
    }
}

// Initialize session manager when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.sessionManager === 'undefined') {
        window.sessionManager = new SessionManager();
    }
});

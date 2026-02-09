@php
    $isVendor = Auth::guard('vendor')->check();
    $logoutUrl = $isVendor ? route('vendor.splash.logout') : route('splash.logout');
    // Get session lifetime from config (in minutes) and convert to milliseconds
    // Default to 3 minutes if not set
    $sessionLifetimeMinutes = config('session.lifetime', 3);
    $sessionLifetimeMs = $sessionLifetimeMinutes * 60 * 1000;
    // Warn 1 minute before expiration, or 30 seconds if lifetime is very short
    $warningTimeMs = ($sessionLifetimeMinutes <= 1) ? 30000 : 60000; 
@endphp

<!-- Session Timeout Modal -->
<div id="session-timeout-modal" 
     class="hidden fixed inset-0 bg-black bg-opacity-50 z-[9999] flex items-center justify-center p-4"
     data-logout-url="{{ $logoutUrl }}"
     data-session-lifetime="{{ $sessionLifetimeMs }}"
     data-warning-time="{{ $warningTimeMs }}">
    <div class="bg-white rounded-lg p-6 w-80 mx-4">
        <div class="flex items-center mb-4">
            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-3">
                <i class='bx bx-time p-10 text-red-600 text-xl'></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Session Timeout!!</h3>
            </div>
        </div>
        
        <div class="mb-4">
            <div class="flex items-center text-sm text-gray-600 mb-2">
                <i class='bx bx-info-circle mr-2 p-1 text-blue-500'></i>
                <span>Your session is about to expire. You will be logged out automatically.</span>
            </div>
            <div class="bg-yellow-50 border border-yellow-200 rounded p-3">
                <p class="text-xs text-yellow-800 text-center">
                    <i class='bx bx-time-five mr-1'></i>
                    <span id="countdown-timer">01:00</span> remaining
                </p>
            </div>
        </div>
        
        <div class="flex justify-end space-x-3">
            <!-- Extend button removed as per requirement -->
            <button onclick="window.location.href='{{ $logoutUrl }}'" class="btn btn-sm btn-error text-white">
                Logout Now
            </button>
        </div>
    </div>
</div>

<script>
class SessionTimeoutHandler {
    constructor() {
        this.timeoutModal = document.getElementById('session-timeout-modal');
        this.countdownTimer = document.getElementById('countdown-timer');
        
        // Get configuration from data attributes
        this.logoutUrl = this.timeoutModal?.dataset.logoutUrl || '/splash-logout';
        this.sessionLifetime = parseInt(this.timeoutModal?.dataset.sessionLifetime || (3 * 60 * 1000)); 
        this.warningTime = parseInt(this.timeoutModal?.dataset.warningTime || 60000);
        
        this.countdownTime = this.warningTime; // Countdown matches the warning duration
        this.countdownInterval = null;
        this.warningTimeout = null;
        this.isModalActive = false;
        this.lastActivity = Date.now();
        
        this.init();
    }
    
    init() {
        this.resetTimers();
        this.setupEventListeners();
        this.startTimeoutTimer();
        console.log(`Session Timeout Initialized: Lifetime=${this.sessionLifetime/1000}s, Warning=${this.warningTime/1000}s`);
    }
    
    setupEventListeners() {
        // User activity events
        const activityEvents = [
            'mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'
        ];
        
        activityEvents.forEach(event => {
            document.addEventListener(event, (e) => {
                // Don't reset timers if the event is within the modal (though extend logic is gone, we still might want to pause/reset if they interact elsewhere? 
                // Actually, if extend logic is gone, activity shouldn't extend the session implicitly if we are strictly following "3 mins only". 
                // BUT usually "session timeout" means "inactivity timeout". 
                // If the user is active, the PHP session keeps getting extended on requests.
                // The JS timer mimics this. If the user moves mouse, we reset the local timer.
                // If we don't reset, they will be logged out even if working.
                // So yes, we must reset on activity.
                
                this.lastActivity = Date.now();
                this.resetTimers();
                
            }, { passive: true });
        });
        
        // Visibility change (tab switch)
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                // If coming back to tab, verify if we should have timed out
                const now = Date.now();
                if (now - this.lastActivity > this.sessionLifetime) {
                    this.logoutDueToTimeout();
                } else {
                    this.resetTimers(); // Adjust/restart timer based on remaining time? 
                    // Actually resetTimers just restarts the full duration. 
                    // For precision we might want to calculate remaining, but resetting to full 3 mins on activity is standard "idle timeout" behavior.
                }
            }
        });
    }
    
    startTimeoutTimer() {
        if (this.warningTimeout) {
            clearTimeout(this.warningTimeout);
        }
        
        // Schedule the warning modal
        const timeUntilWarning = Math.max(0, this.sessionLifetime - this.warningTime);
        
        this.warningTimeout = setTimeout(() => {
            this.showWarningModal();
        }, timeUntilWarning);
    }
    
    showWarningModal() {
        if (!this.timeoutModal) return;
        
        this.timeoutModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        this.isModalActive = true;
        
        // Disable sidebar interactions
        this.disableSidebar();
        
        // Start countdown
        this.startCountdown();
    }
    
    startCountdown() {
        let timeLeft = this.countdownTime / 1000; // Convert to seconds
        
        // Clear any existing interval
        if (this.countdownInterval) {
            clearInterval(this.countdownInterval);
        }
        
        const startTime = Date.now();
        const endTime = startTime + this.countdownTime;
        
        this.countdownInterval = setInterval(() => {
            const now = Date.now();
            timeLeft = Math.max(0, Math.ceil((endTime - now) / 1000));
            
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            if (this.countdownTimer) {
                this.countdownTimer.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            }
            
            if (timeLeft <= 0) {
                this.logoutDueToTimeout();
            }
        }, 100);
    }
    
    hideWarningModal() {
        if (!this.timeoutModal) return;
        
        this.timeoutModal.classList.add('hidden');
        document.body.style.overflow = '';
        this.isModalActive = false;
        this.enableSidebar();
        
        if (this.countdownInterval) {
            clearInterval(this.countdownInterval);
            this.countdownInterval = null;
        }
    }
    
    resetTimers() {
        if (this.isModalActive) {
            return; // Don't reset timers if modal is active (user must logout or wait)
            // Since extend logic is removed, once the modal shows, it's the end of the line unless they refresh the page manually?
            // If they move the mouse while modal is open, should it disappear?
            // "remove the logics of the session timeout extend sessions"
            // If I just hide the modal on activity, that IS extending the session.
            // So if modal is active, we IGNORE activity. They must logout or let it timeout.
            // Wait, standard behavior: if I move mouse, I am active.
            // But the user specifically wanted to REMOVE extend session logic.
            // Usually that means removing the "button" to extend.
            // If I let mouse movement dismiss the modal, that's effectively auto-extending.
            // If the modal is up, it means they were idle for (Lifetime - WarningTime).
            // If they come back now, usually they click "Stay Logged In".
            // If I remove that button, they HAVE to logout?
            // Or does "remove extend session" mean "no manual extension, but activity still prevents it"?
            // If activity prevents it, then once the modal is up (meaning they were idle), how do they recover?
            // If they can't recover, then the modal is just a death sentence countdown.
            // That seems harsh.
            // "remove the logics of the session timeout extend sessions" likely refers to the "Extend Session" button/API call.
            // If the user wakes up during the countdown, can they save their session?
            // Without an extend button, they can't.
            // Unless I make any activity dismiss the modal?
            // BUT, the user said "remove the logics... extend sessions".
            // This might mean: STRICT timeout. 3 minutes and you are out, regardless of activity?
            // "make sure that the current session-timeout set into 3mins only"
            // Usually "session timeout" implies idle timeout.
            // But "remove extend sessions" might mean "absolute timeout"?
            // Let's look at the phrasing: "remove the logics of the session timeout extend sessions".
            // The original code had a button "Extend Session" which called `/api/refresh-session`.
            // Removing that logic means no more API calls to refresh.
            // So, once the modal appears, can they continue?
            // If I don't provide a way to continue, then yes, they are forced to logout.
            // Is that what they want?
            // "if the employee... when session timeout they will automatically logout"
            // It sounds like they want a strict auto-logout.
            // I will implement it such that once the modal appears, it counts down to logout. No escape.
            // BUT, if they are active *before* the modal appears, the timer resets (standard idle timer).
            // So: Idle for 2 mins -> Warning Modal (1 min countdown) -> Logout.
            // During the 1 min countdown, there is no button to stop it. This matches "remove extend logic".
        }
        
        if (this.warningTimeout) {
            clearTimeout(this.warningTimeout);
        }
        
        if (this.countdownInterval) {
            clearInterval(this.countdownInterval);
        }
        
        this.startTimeoutTimer();
    }
    
    async logoutDueToTimeout() {
        if (this.warningTimeout) clearTimeout(this.warningTimeout);
        if (this.countdownInterval) clearInterval(this.countdownInterval);
        
        // Call API logout to clear server session
        try {
            await fetch('/api/logout', {
                method: 'POST',
                headers: { 
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                credentials: 'same-origin'
            });
        } catch (e) {
            console.error('Logout failed', e);
        }
        
        // Clear local storage
        try { 
            localStorage.removeItem('jwt'); 
            localStorage.removeItem('jwt_exp'); 
        } catch (e) {}
        
        // Redirect
        window.location.href = this.logoutUrl;
    }
    
    disableSidebar() {
        const sidebar = document.getElementById('sidebar');
        const sidebarLinks = document.querySelectorAll('.sidebar-link, .has-dropdown > div');
        
        if (sidebar) {
            sidebar.style.pointerEvents = 'none';
            sidebar.style.userSelect = 'none';
            sidebar.style.opacity = '0.6';
        }
        
        sidebarLinks.forEach(link => {
            link.style.pointerEvents = 'none';
            link.style.cursor = 'default';
        });
    }
    
    enableSidebar() {
        const sidebar = document.getElementById('sidebar');
        const sidebarLinks = document.querySelectorAll('.sidebar-link, .has-dropdown > div');
        
        if (sidebar) {
            sidebar.style.pointerEvents = 'auto';
            sidebar.style.userSelect = 'auto';
            sidebar.style.opacity = '1';
        }
        
        sidebarLinks.forEach(link => {
            link.style.pointerEvents = 'auto';
            link.style.cursor = 'pointer';
        });
    }
}

// Initialize session timeout handler when page loads
document.addEventListener('DOMContentLoaded', () => {
    new SessionTimeoutHandler();
});
</script>
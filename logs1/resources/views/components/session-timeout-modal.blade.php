@php
    $isVendor = Auth::guard('vendor')->check();
    $logoutUrl = $isVendor ? route('vendor.splash.logout') : route('splash.logout');
    // Get session lifetime from config (in minutes) and convert to milliseconds
    // Default to 3 minutes if not set
    $sessionLifetimeMinutes = config('session.lifetime', 3);
    $sessionLifetimeMs = $sessionLifetimeMinutes * 60 * 1000;
    // Set warning time to 5 seconds so the modal appears effectively when the session is "expired"
    // giving just a brief notice before the actual redirect.
    $warningTimeMs = 5000; 
@endphp

<!-- Session Timeout Modal -->
<div id="session-timeout-modal" 
     class="hidden fixed inset-0 bg-gray-900 bg-opacity-70 z-[9999] flex items-center justify-center p-4 backdrop-blur-sm transition-opacity duration-300"
     data-logout-url="{{ $logoutUrl }}"
     data-session-lifetime="{{ $sessionLifetimeMs }}"
     data-warning-time="{{ $warningTimeMs }}">
    
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-sm w-full mx-4 text-center transform transition-all scale-100 border border-gray-100">
        <!-- Icon -->
        <div class="mb-6 flex justify-center">
            <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center ring-8 ring-red-50/50">
                <i class='bx bx-log-out-circle text-4xl text-red-500'></i>
            </div>
        </div>

        <!-- Title -->
        <h3 class="text-2xl font-bold text-gray-800 mb-3 tracking-tight">Session Expired</h3>
        
        <!-- Message -->
        <div class="mb-8">
            <p class="text-gray-600 text-base leading-relaxed">
                Your Session has expired, you are now logging out automatically.
            </p>
        </div>
        
        <!-- Button -->
        <div class="flex justify-center">
            <button id="session-timeout-ok-btn" 
                    class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-xl transition duration-200 shadow-lg hover:shadow-red-500/30 flex items-center justify-center gap-2 group">
                <span>Ok</span>
                <i class='bx bx-right-arrow-alt group-hover:translate-x-1 transition-transform'></i>
            </button>
        </div>
        
        <!-- Hidden timer element to maintain JS compatibility if needed, though JS checks for existence -->
        <!-- <span id="countdown-timer" class="hidden"></span> -->
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
        this.warningTime = parseInt(this.timeoutModal?.dataset.warningTime || 5000);
        
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
                // If modal is active, user is effectively locked out/expired, so we don't reset timers on activity.
                if (this.isModalActive) return;

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
                    this.resetTimers(); 
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
        
        // Start countdown (internal, for auto-logout)
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
            
            // Timer element is removed from UI, so no text update needed
            // But if it existed:
            if (this.countdownTimer) {
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
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
            return; 
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
            await fetch('/api/v1/auth/logout', {
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
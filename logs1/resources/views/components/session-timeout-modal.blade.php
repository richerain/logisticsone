<!-- Session Timeout Modal -->
<div id="session-timeout-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[9999] flex items-center justify-center p-4">
    <div class="bg-white rounded-lg p-6 w-80 mx-4">
        <div class="flex items-center mb-4">
            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-3">
                <i class='bx bx-time text-red-600 text-xl'></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Session Timeout!!</h3>
                <p class="text-sm text-gray-600">Your session is about to expire due to inactivity.</p>
            </div>
        </div>
        
        <div class="mb-4">
            <div class="flex items-center text-sm text-gray-600 mb-2">
                <i class='bx bx-info-circle mr-2 text-blue-500'></i>
                <span>You will be logged out for security reasons.</span>
            </div>
            <div class="bg-yellow-50 border border-yellow-200 rounded p-3">
                <p class="text-xs text-yellow-800 text-center">
                    <i class='bx bx-time-five mr-1'></i>
                    <span id="countdown-timer">01:00</span> remaining
                </p>
            </div>
        </div>
        
        <div class="flex justify-end space-x-3">
            <button id="extend-session-btn" class="btn btn-primary text-white bg-green-600 hover:bg-green-700">
                <i class='bx bx-time mr-1'></i>Stay Logged In
            </button>
        </div>
    </div>
</div>

<script>
class SessionTimeoutHandler {
    constructor() {
        this.timeoutModal = document.getElementById('session-timeout-modal');
        this.countdownTimer = document.getElementById('countdown-timer');
        this.extendSessionBtn = document.getElementById('extend-session-btn');
        
        this.warningTime = 300000; // 5 minutes in milliseconds (changed from 600000)
        this.countdownTime = 60000; // 1 minute countdown (unchanged)
        this.countdownInterval = null;
        this.timeoutInterval = null;
        this.warningTimeout = null;
        
        this.init();
    }
    
    init() {
        this.resetTimers();
        this.setupEventListeners();
        this.startTimeoutTimer();
    }
    
    setupEventListeners() {
        // User activity events
        const activityEvents = [
            'mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'
        ];
        
        activityEvents.forEach(event => {
            document.addEventListener(event, () => this.resetTimers(), { passive: true });
        });
        
        // Extend session button
        this.extendSessionBtn.addEventListener('click', () => this.extendSession());
        
        // Visibility change (tab switch)
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                this.checkSession();
            }
        });
    }
    
    startTimeoutTimer() {
        this.warningTimeout = setTimeout(() => {
            this.showWarningModal();
        }, this.warningTime);
    }
    
    showWarningModal() {
        this.timeoutModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Disable sidebar interactions
        this.disableSidebar();
        
        // Start countdown
        this.startCountdown();
    }
    
    startCountdown() {
        let timeLeft = this.countdownTime / 1000; // Convert to seconds
        
        this.countdownInterval = setInterval(() => {
            timeLeft--;
            
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            this.countdownTimer.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            if (timeLeft <= 0) {
                this.logoutDueToTimeout();
            }
        }, 1000);
    }
    
    async extendSession() {
        try {
            const response = await fetch('/api/refresh-session', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.hideWarningModal();
                this.resetTimers();
            } else {
                throw new Error('Failed to extend session');
            }
        } catch (error) {
            console.error('Session extension error:', error);
            this.logoutDueToTimeout();
        }
    }
    
    hideWarningModal() {
        this.timeoutModal.classList.add('hidden');
        document.body.style.overflow = '';
        this.enableSidebar();
        
        if (this.countdownInterval) {
            clearInterval(this.countdownInterval);
            this.countdownInterval = null;
        }
    }
    
    resetTimers() {
        if (this.warningTimeout) {
            clearTimeout(this.warningTimeout);
        }
        
        if (this.timeoutInterval) {
            clearTimeout(this.timeoutInterval);
        }
        
        this.hideWarningModal();
        this.startTimeoutTimer();
    }
    
    async checkSession() {
        try {
            const response = await fetch('/api/me', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            if (response.status === 401) {
                this.logoutDueToTimeout();
            }
        } catch (error) {
            console.error('Session check error:', error);
        }
    }
    
    logoutDueToTimeout() {
        // Clear all intervals and timeouts
        if (this.warningTimeout) clearTimeout(this.warningTimeout);
        if (this.countdownInterval) clearInterval(this.countdownInterval);
        if (this.timeoutInterval) clearTimeout(this.timeoutInterval);
        
        // Redirect to splash logout
        window.location.href = '/splash-logout';
    }
    
    disableSidebar() {
        const sidebar = document.getElementById('sidebar');
        const sidebarLinks = document.querySelectorAll('.sidebar-link, .has-dropdown > div');
        
        if (sidebar) {
            sidebar.style.pointerEvents = 'none';
            sidebar.style.userSelect = 'none';
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
<!-- Session Timeout Modal -->
<div id="session-timeout-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[9999] flex items-center justify-center p-4">
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
                <span>Your session is about to expire due to inactivity. You will be logged out automatically.</span>
            </div>
            <div class="bg-yellow-50 border border-yellow-200 rounded p-3">
                <p class="text-xs text-yellow-800 text-center">
                    <i class='bx bx-time-five mr-1'></i>
                    <span id="countdown-timer">01:00</span> remaining
                </p>
            </div>
        </div>
        
        <div class="flex justify-end space-x-3">
            <button id="extend-session-btn" class="btn btn-primary border-none text-white bg-green-600 hover:bg-green-700">
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
        this.modalContent = this.timeoutModal?.querySelector('.bg-white');
        
        this.sessionLifetime = 5 * 60 * 1000; // 5 minutes in milliseconds
        this.warningTime = 60000; // Show warning 1 minute before expiry
        this.countdownTime = 60000; // 1 minute countdown
        this.countdownInterval = null;
        this.warningTimeout = null;
        this.isModalActive = false;
        this.isExtendingSession = false;
        this.extensionAttempts = 0;
        this.maxExtensionAttempts = 5;
        this.lastActivity = Date.now();
        
        this.init();
    }
    
    init() {
        this.resetTimers();
        this.setupEventListeners();
        this.startTimeoutTimer();
        
        // Pre-check session status on load
        this.checkSessionStatus();
    }
    
    setupEventListeners() {
        // User activity events
        const activityEvents = [
            'mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'
        ];
        
        activityEvents.forEach(event => {
            document.addEventListener(event, (e) => {
                // Don't reset timers if the event is within the modal
                if (!this.isEventInModal(e)) {
                    this.lastActivity = Date.now();
                    this.resetTimers();
                }
            }, { passive: true });
        });
        
        // Extend session button
        this.extendSessionBtn.addEventListener('click', () => this.extendSession());
        
        // Visibility change (tab switch)
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                this.checkSessionStatus();
            }
        });
        
        // Prevent modal from closing when clicking inside modal content
        if (this.modalContent) {
            this.modalContent.addEventListener('click', (e) => {
                e.stopPropagation();
            });
        }
    }
    
    isEventInModal(event) {
        if (!this.timeoutModal || this.timeoutModal.classList.contains('hidden')) {
            return false;
        }
        
        // Check if the event target is within the modal
        return this.timeoutModal.contains(event.target);
    }
    
    startTimeoutTimer() {
        if (this.warningTimeout) {
            clearTimeout(this.warningTimeout);
        }
        
        this.warningTimeout = setTimeout(() => {
            this.showWarningModal();
        }, this.sessionLifetime - this.warningTime);
    }
    
    showWarningModal() {
        this.timeoutModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        this.isModalActive = true;
        this.extensionAttempts = 0; // Reset attempts for new warning
        
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
            this.countdownTimer.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            // Enable extension button even at low time
            if (timeLeft <= 5) {
                this.extendSessionBtn.disabled = false;
                this.extendSessionBtn.innerHTML = '<i class="bx bx-time mr-1"></i>Stay Logged In';
            }
            
            if (timeLeft <= 0) {
                this.logoutDueToTimeout();
            }
        }, 100);
    }
    
    async extendSession() {
        if (this.isExtendingSession) {
            return;
        }
        
        this.extensionAttempts++;
        
        if (this.extensionAttempts > this.maxExtensionAttempts) {
            this.showTemporaryMessage('Too many extension attempts. Please login again.', 'error');
            setTimeout(() => {
                window.location.href = '/splash-logout';
            }, 2000);
            return;
        }
        
        this.isExtendingSession = true;
        this.extendSessionBtn.disabled = true;
        this.extendSessionBtn.innerHTML = '<i class="bx bx-loader bx-spin mr-1"></i>Extending...';
        
        try {
            // First, check if session is still valid
            const sessionCheck = await this.checkSessionStatus();
            if (!sessionCheck.authenticated) {
                throw new Error('Session no longer valid');
            }
            
            const csrfToken = this.getCsrfToken();
            
            const response = await fetch('/api/refresh-session', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                credentials: 'same-origin'
            });
            
            if (response.ok) {
                const data = await response.json();
                console.log('Successful extension of session');
                this.handleSuccessfulExtension(data);
                return;
            }
            
            // Handle CSRF token mismatch
            if (response.status === 419) {
                await this.refreshCsrfToken();
                const newCsrfToken = this.getCsrfToken();
                
                const retryResponse = await fetch('/api/refresh-session', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': newCsrfToken
                    },
                    credentials: 'same-origin'
                });
                
                if (retryResponse.ok) {
                    const retryData = await retryResponse.json();
                    console.log('Successful extension of session');
                    this.handleSuccessfulExtension(retryData);
                    return;
                } else {
                    throw new Error('Failed to extend session after CSRF refresh');
                }
            }
            
            // Handle other errors
            const errorData = await response.json().catch(() => ({}));
            throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
            
        } catch (error) {
            if (error.message.includes('expired') || error.message.includes('419') || error.message.includes('401') || error.message.includes('not valid')) {
                this.showTemporaryMessage('Session has expired. Please login again.', 'error');
                setTimeout(() => {
                    window.location.href = '/splash-logout';
                }, 2000);
            } else {
                this.showTemporaryMessage('Failed to extend session. Please try again.', 'error');
                this.resetExtendButton();
            }
        } finally {
            this.isExtendingSession = false;
        }
    }
    
    async checkSessionStatus() {
        try {
            const response = await fetch('/api/check-session', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken()
                },
                credentials: 'same-origin'
            });
            
            if (response.ok) {
                const data = await response.json();
                return data;
            } else {
                return { authenticated: false };
            }
        } catch (error) {
            return { authenticated: false };
        }
    }
    
    handleSuccessfulExtension(data) {
        // Update CSRF token if provided
        if (data.csrf_token) {
            this.updateCsrfToken(data.csrf_token);
        }
        
        this.hideWarningModal();
        this.resetTimers();
        this.showTemporaryMessage('Session extended successfully!', 'success');
        this.resetExtendButton();
        this.extensionAttempts = 0; // Reset attempts after successful extension
        this.lastActivity = Date.now(); // Update last activity
    }
    
    resetExtendButton() {
        this.extendSessionBtn.disabled = false;
        this.extendSessionBtn.innerHTML = '<i class="bx bx-time mr-1"></i>Stay Logged In';
    }
    
    async refreshCsrfToken() {
        try {
            const response = await fetch('/api/csrf-token', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            });
            
            if (response.ok) {
                const data = await response.json();
                if (data.csrf_token) {
                    this.updateCsrfToken(data.csrf_token);
                }
            }
        } catch (error) {
            // Silent fail
        }
    }
    
    getCsrfToken() {
        const metaTag = document.querySelector('meta[name="csrf-token"]');
        return metaTag ? metaTag.getAttribute('content') : '';
    }
    
    updateCsrfToken(newToken) {
        const metaTag = document.querySelector('meta[name="csrf-token"]');
        if (metaTag) {
            metaTag.setAttribute('content', newToken);
        }
        
        // Also update any hidden CSRF input fields
        const csrfInputs = document.querySelectorAll('input[name="_token"]');
        csrfInputs.forEach(input => {
            input.value = newToken;
        });
    }
    
    hideWarningModal() {
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
            return; // Don't reset timers if modal is active
        }
        
        if (this.warningTimeout) {
            clearTimeout(this.warningTimeout);
        }
        
        if (this.countdownInterval) {
            clearInterval(this.countdownInterval);
        }
        
        this.startTimeoutTimer();
    }
    
    logoutDueToTimeout() {
        // Clear all intervals and timeouts
        if (this.warningTimeout) clearTimeout(this.warningTimeout);
        if (this.countdownInterval) clearInterval(this.countdownInterval);
        
        // Show logout message
        this.showTemporaryMessage('Session expired. Logging out...', 'warning');
        
        // Redirect to splash logout after a brief delay
        setTimeout(() => {
            window.location.href = '/splash-logout';
        }, 1000);
    }
    
    showTemporaryMessage(message, type = 'info') {
        // Remove any existing temporary message
        const existingMessage = document.getElementById('temp-session-message');
        if (existingMessage) {
            existingMessage.remove();
        }
        
        const messageDiv = document.createElement('div');
        messageDiv.id = 'temp-session-message';
        messageDiv.className = `fixed top-4 right-4 z-[10000] p-4 rounded-lg shadow-lg ${
            type === 'success' ? 'bg-green-500 text-white' :
            type === 'error' ? 'bg-red-500 text-white' :
            type === 'warning' ? 'bg-yellow-500 text-white' :
            'bg-blue-500 text-white'
        }`;
        messageDiv.innerHTML = `
            <div class="flex items-center">
                <i class='bx ${
                    type === 'success' ? 'bxs-check-circle' :
                    type === 'error' ? 'bxs-error-circle' :
                    type === 'warning' ? 'bxs-time' :
                    'bxs-info-circle'
                } mr-2'></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(messageDiv);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            if (messageDiv.parentNode) {
                messageDiv.remove();
            }
        }, 3000);
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
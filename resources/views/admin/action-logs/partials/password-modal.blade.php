<!-- Password Protection Modal for Action Logs Table -->
<div id="passwordModal" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop with blur effect -->
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

    <!-- Modal -->
    <div class="relative flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 transform transition-all">
            <!-- Header -->
            <div class="text-center mb-6">
                <div class="mx-auto w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-lock text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Admin Authentication Required</h3>
                <p class="text-gray-600 text-sm">Please enter your admin password to view action logs</p>
            </div>

            <!-- Password Form -->
            <form id="passwordForm" class="space-y-4">
                @csrf
                <div>
                    <label for="adminPassword" class="block text-sm font-medium text-gray-700 mb-2">
                        Admin Password
                    </label>
                    <div class="relative">
                        <input type="password" id="adminPassword" name="admin_password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent pr-12"
                            placeholder="Enter your password" required autocomplete="current-password">
                        <button type="button" id="togglePassword"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Error Message -->
                <div id="passwordError" class="hidden">
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                        <div class="flex">
                            <i class="fas fa-exclamation-circle text-red-400 mr-2 mt-0.5"></i>
                            <p class="text-sm text-red-700" id="errorMessage">Invalid password. Please try again.</p>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="pt-2 space-y-3">
                    <button type="submit"
                        class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-lg font-medium transition duration-200 flex items-center justify-center">
                        <i class="fas fa-unlock mr-2"></i>
                        Unlock
                    </button>

                    <button type="button" onclick="goBackSafely()"
                        class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-3 rounded-lg font-medium transition duration-200 flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Go Back
                    </button>
                </div>
            </form>

            <!-- Security Notice -->
            <div class="mt-6 p-3 bg-blue-50 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-shield-alt text-blue-400 mr-2 mt-0.5 text-sm"></i>
                    <p class="text-xs text-blue-700">
                        <strong>Security Notice:</strong> Action logs contain sensitive system information.
                        Access is restricted to authenticated administrators only. Password is required to proceed.
                    </p>
                </div>
            </div>

            <!-- No Bypass Notice -->
            <div class="mt-3 p-3 bg-red-50 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-red-400 mr-2 mt-0.5 text-sm"></i>
                    <p class="text-xs text-red-700">
                        <strong>Important:</strong> This page cannot be accessed without proper authentication.
                        All security bypass attempts are blocked.
                    </p>
                </div>
            </div>

            <!-- Go Back Notice -->
            <div class="mt-3 p-3 bg-green-50 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-green-400 mr-2 mt-0.5 text-sm"></i>
                    <p class="text-xs text-green-700">
                        <strong>Need to go back?</strong> If you accidentally navigated here,
                        you can use the "Go Back" button below to return to the previous page.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Blurred Content Overlay -->
<div id="blurredOverlay" class="fixed inset-0 z-40 hidden">
    <div class="absolute inset-0 bg-black/30 backdrop-blur-md"></div>
    <div class="absolute inset-0 flex items-center justify-center">
        <div class="text-center text-white">
            <div
                class="mx-auto w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mb-4 backdrop-blur-sm">
                <i class="fas fa-lock text-3xl"></i>
            </div>
            <h3 class="text-2xl font-bold mb-2">Content Locked</h3>
            <p class="text-lg opacity-90">Enter admin password to continue</p>
        </div>
    </div>
</div>

<script>
    // ============================================================================
    // ACTION LOGS PASSWORD PROTECTION SYSTEM
    // ============================================================================

    class ActionLogsPasswordProtection {
        constructor() {
            this.init();
        }

        init() {
            this.setupEventListeners();
            this.checkAuthenticationStatus();
        }

        setupEventListeners() {
            // Password visibility toggle
            document.getElementById('togglePassword').addEventListener('click', () => {
                this.togglePasswordVisibility();
            });

            // Password form submission
            document.getElementById('passwordForm').addEventListener('submit', (e) => {
                e.preventDefault();
                this.handlePasswordSubmission();
            });

            // Page visibility changes
            document.addEventListener('visibilitychange', () => {
                this.handleVisibilityChange();
            });
        }

        checkAuthenticationStatus() {
            const isUnlocked = sessionStorage.getItem('actionLogsUnlocked');
            const unlockTime = sessionStorage.getItem('actionLogsUnlockTime');

            if (isUnlocked && unlockTime) {
                const now = Date.now();
                const unlockTimestamp = parseInt(unlockTime);
                const validDuration = 24 * 60 * 60 * 1000; // 24 hours

                if (now - unlockTimestamp < validDuration) {
                    this.unlockContent();
                    return true;
                } else {
                    this.clearAuthentication();
                }
            }

            this.showPasswordProtection();
            return false;
        }

        showPasswordProtection() {
            document.getElementById('passwordModal').classList.remove('hidden');
            document.getElementById('blurredOverlay').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            this.addSecurityMeasures();
        }

        async handlePasswordSubmission() {
            const password = document.getElementById('adminPassword').value;
            const submitBtn = document.querySelector('#passwordForm button[type="submit"]');

            try {
                this.setLoadingState(submitBtn, true);
                const response = await this.verifyPassword(password);

                if (response.success) {
                    this.unlockContent();
                } else {
                    this.showError(response.message);
                }
            } catch (error) {
                this.showError('An error occurred. Please try again.');
            } finally {
                this.setLoadingState(submitBtn, false);
            }
        }

        async verifyPassword(password) {
            const response = await fetch('{{ route('admin.action-logs.verify-password') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content')
                },
                body: JSON.stringify({
                    password: password
                })
            });

            return await response.json();
        }

        setLoadingState(button, isLoading) {
            if (isLoading) {
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Verifying...';
                button.disabled = true;
            } else {
                button.innerHTML = '<i class="fas fa-unlock mr-2"></i>Unlock';
                button.disabled = false;
            }
        }

        showError(message) {
            const errorDiv = document.getElementById('passwordError');
            const errorMessage = document.getElementById('errorMessage');

            errorMessage.textContent = message;
            errorDiv.classList.remove('hidden');

            document.getElementById('adminPassword').value = '';
            document.getElementById('adminPassword').focus();
        }

        unlockContent() {
            document.getElementById('passwordModal').classList.add('hidden');
            document.getElementById('blurredOverlay').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');

            sessionStorage.setItem('actionLogsUnlocked', 'true');
            sessionStorage.setItem('actionLogsUnlockTime', Date.now());

            if (!sessionStorage.getItem('actionLogsMessageShown')) {
                this.showSuccessMessage('Access granted! Action logs are now visible.');
                sessionStorage.setItem('actionLogsMessageShown', 'true');
            }
        }

        showSuccessMessage(message) {
            const notification = document.createElement('div');
            notification.className =
                'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-x-full';
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>${message}</span>
                </div>
            `;

            document.body.appendChild(notification);

            setTimeout(() => notification.classList.remove('translate-x-full'), 100);
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        handleVisibilityChange() {
            if (!document.hidden && sessionStorage.getItem('actionLogsUnlocked')) {
                document.getElementById('passwordModal').classList.add('hidden');
                document.getElementById('blurredOverlay').classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }

        clearAuthentication() {
            sessionStorage.removeItem('actionLogsUnlocked');
            sessionStorage.removeItem('actionLogsUnlockTime');
            sessionStorage.removeItem('actionLogsMessageShown');
        }

        addSecurityMeasures() {
            // Prevent right-click context menu
            document.addEventListener('contextmenu', (e) => {
                e.preventDefault();
                return false;
            });

            // Prevent developer tools and shortcuts
            document.addEventListener('keydown', (e) => {
                if (e.key === 'F12' ||
                    (e.ctrlKey && e.shiftKey && e.key === 'I') ||
                    (e.ctrlKey && e.key === 'u') ||
                    (e.ctrlKey && e.key === 'U') ||
                    (e.ctrlKey && e.key === 's') ||
                    e.key === 'F5' ||
                    (e.ctrlKey && e.key === 'r')) {
                    e.preventDefault();
                    return false;
                }
            });

            // Prevent navigation away
            window.beforeUnloadHandler = (e) => {
                if (window.intentionalNavigation) return;

                if (!sessionStorage.getItem('actionLogsUnlocked')) {
                    e.preventDefault();
                    e.returnValue =
                        'You must enter the password to access this page. Are you sure you want to leave?';
                    return 'You must enter the password to access this page. Are you sure you want to leave?';
                }
            };
            window.addEventListener('beforeunload', window.beforeUnloadHandler);

            // Prevent drag and drop, copy/paste
            ['dragstart', 'copy', 'paste'].forEach(event => {
                document.addEventListener(event, (e) => {
                    e.preventDefault();
                    return false;
                });
            });

            // Disable text selection on overlay
            const overlay = document.getElementById('blurredOverlay');
            ['userSelect', 'webkitUserSelect', 'mozUserSelect', 'msUserSelect'].forEach(prop => {
                overlay.style[prop] = 'none';
            });

            // Continuous monitoring
            this.startSecurityMonitoring();
        }

        startSecurityMonitoring() {
            setInterval(() => {
                if (!sessionStorage.getItem('actionLogsUnlocked')) {
                    const modal = document.getElementById('passwordModal');
                    const overlay = document.getElementById('blurredOverlay');

                    if (modal.classList.contains('hidden')) modal.classList.remove('hidden');
                    if (overlay.classList.contains('hidden')) overlay.classList.remove('hidden');
                    if (!document.body.classList.contains('overflow-hidden')) document.body.classList.add(
                        'overflow-hidden');
                }
            }, 100);
        }

        togglePasswordVisibility() {
            const passwordInput = document.getElementById('adminPassword');
            const icon = document.querySelector('#togglePassword i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    }

    // ============================================================================
    // UTILITY FUNCTIONS
    // ============================================================================

    function goBackSafely() {
        window.intentionalNavigation = true;

        if (window.history.length > 1) {
            window.history.back();
        } else {
            window.location.href = '{{ route('admin.dashboard') }}';
        }
    }

    // ============================================================================
    // INITIALIZATION
    // ============================================================================

    document.addEventListener('DOMContentLoaded', () => {
        window.intentionalNavigation = false;

        if (!sessionStorage.getItem('actionLogsUnlocked')) {
            sessionStorage.removeItem('actionLogsMessageShown');
        }

        new ActionLogsPasswordProtection();
    });
</script>

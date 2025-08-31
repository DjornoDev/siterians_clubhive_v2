<!-- Password Protection Modal for Archives Download -->
<div id="archivesPasswordModal" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop with blur effect -->
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

    <!-- Modal -->
    <div class="relative flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 transform transition-all">
            <!-- Header -->
            <div class="text-center mb-6">
                <div class="mx-auto w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-download text-purple-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Download Authorization Required</h3>
                <p class="text-gray-600 text-sm">Please enter your admin password to download archive files</p>
            </div>

            <!-- Password Form -->
            <form id="archivesPasswordForm" class="space-y-4">
                @csrf
                <div>
                    <label for="archivesPassword" class="block text-sm font-medium text-gray-700 mb-2">
                        Admin Password
                    </label>
                    <div class="relative">
                        <input type="password" id="archivesPassword" name="admin_password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent pr-12"
                            placeholder="Enter your password" required autocomplete="current-password">
                        <button type="button" id="toggleArchivesPassword"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Error Message -->
                <div id="archivesPasswordError" class="hidden">
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                        <div class="flex">
                            <i class="fas fa-exclamation-circle text-red-400 mr-2 mt-0.5"></i>
                            <p class="text-sm text-red-700" id="archivesErrorMessage">Invalid password. Please try
                                again.</p>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="pt-2 space-y-3">
                    <button type="submit"
                        class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-3 rounded-lg font-medium transition duration-200 flex items-center justify-center">
                        <i class="fas fa-check mr-2"></i>
                        Authorize Download
                    </button>

                    <button type="button" onclick="goBackSafely()"
                        class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-3 rounded-lg font-medium transition duration-200 flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Go Back
                    </button>
                </div>
            </form>

            <!-- Security Notice -->
            <div class="mt-6 p-3 bg-purple-50 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-shield-alt text-purple-400 mr-2 mt-0.5 text-sm"></i>
                    <p class="text-xs text-purple-700">
                        <strong>Security Notice:</strong> Archive files contain sensitive historical data.
                        Download access is restricted to authenticated administrators only. Password is required to
                        proceed.
                    </p>
                </div>
            </div>

            <!-- No Bypass Notice -->
            <div class="mt-3 p-3 bg-red-50 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-red-400 mr-2 mt-0.5 text-sm"></i>
                    <p class="text-xs text-red-700">
                        <strong>Important:</strong> Downloads cannot be accessed without proper authentication.
                        All security bypass attempts are blocked.
                    </p>
                </div>
            </div>

            <!-- Go Back Notice -->
            <div class="mt-3 p-3 bg-green-50 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-green-400 mr-2 mt-0.5 text-sm"></i>
                    <p class="text-xs text-green-700">
                        <strong>Need to go back?</strong> If you accidentally clicked a download link,
                        you can use the "Go Back" button below to return to the previous page.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // ============================================================================
    // ARCHIVES PASSWORD PROTECTION SYSTEM
    // ============================================================================

    class ArchivesPasswordProtection {
        constructor() {
            this.currentDownloadUrl = null;
            this.init();
        }

        init() {
            this.setupEventListeners();
            this.setupPasswordProtection();
        }

        setupEventListeners() {
            // Password visibility toggle
            document.getElementById('toggleArchivesPassword').addEventListener('click', () => {
                this.togglePasswordVisibility();
            });

            // Password form submission
            document.getElementById('archivesPasswordForm').addEventListener('submit', (e) => {
                e.preventDefault();
                this.handlePasswordSubmission();
            });
        }

        setupPasswordProtection() {
            const downloadLinks = this.getDownloadLinks();
            this.protectDownloadLinks(downloadLinks);
            this.setupMutationObserver();
        }

        getDownloadLinks() {
            return document.querySelectorAll(
                'a[href*="download-archive"], a[href*="action-logs/download-archive"], a[href*="archives/download"], a[href*="/download/"]'
            );
        }

        protectDownloadLinks(links) {
            links.forEach(link => {
                if (!link.hasAttribute('data-password-protected')) {
                    link.setAttribute('data-password-protected', 'true');
                    link.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        this.showPasswordModal(link.getAttribute('href'));
                    });
                }
            });
        }

        setupMutationObserver() {
            const observer = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    if (mutation.type === 'childList') {
                        const newLinks = this.getDownloadLinks();
                        this.protectDownloadLinks(newLinks);
                    }
                });
            });

            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        }

        togglePasswordVisibility() {
            const passwordInput = document.getElementById('archivesPassword');
            const icon = document.querySelector('#toggleArchivesPassword i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        async handlePasswordSubmission() {
            const password = document.getElementById('archivesPassword').value;
            const submitBtn = document.querySelector('#archivesPasswordForm button[type="submit"]');

            try {
                this.setLoadingState(submitBtn, true);
                const response = await this.verifyPassword(password);

                if (response.success) {
                    this.proceedWithDownload(response.downloadUrl);
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
            const response = await fetch('{{ route('admin.action-logs.verify-archives-password') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content')
                },
                body: JSON.stringify({
                    password: password,
                    downloadUrl: this.currentDownloadUrl
                })
            });

            return await response.json();
        }

        setLoadingState(button, isLoading) {
            if (isLoading) {
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Verifying...';
                button.disabled = true;
            } else {
                button.innerHTML = '<i class="fas fa-check mr-2"></i>Authorize Download';
                button.disabled = false;
            }
        }

        showError(message) {
            const errorDiv = document.getElementById('archivesPasswordError');
            const errorMessage = document.getElementById('archivesErrorMessage');

            errorMessage.textContent = message;
            errorDiv.classList.remove('hidden');

            document.getElementById('archivesPassword').value = '';
            document.getElementById('archivesPassword').focus();
        }

        showPasswordModal(downloadUrl) {
            this.currentDownloadUrl = downloadUrl;
            document.getElementById('archivesPasswordModal').classList.remove('hidden');

            setTimeout(() => {
                document.getElementById('archivesPassword').focus();
            }, 100);
        }

        proceedWithDownload(downloadUrl) {
            document.getElementById('archivesPasswordModal').classList.add('hidden');
            this.showSuccessMessage('Download authorized! Starting download...');

            setTimeout(() => {
                if (downloadUrl) {
                    window.location.href = downloadUrl;
                }
            }, 1000);
        }

        showSuccessMessage(message) {
            const notification = document.createElement('div');
            notification.className =
                'fixed top-4 right-4 bg-purple-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-x-full';
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>${message}</span>
                </div>
            `;

            document.body.appendChild(notification);

            // Animate in
            setTimeout(() => notification.classList.remove('translate-x-full'), 100);

            // Remove after 3 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => notification.remove(), 300);
            }, 3000);
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
        new ArchivesPasswordProtection();
    });
</script>

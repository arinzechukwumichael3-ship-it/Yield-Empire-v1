<nav class="navbar-wrapper d-none d-lg-flex">
    <div class="navbar-container">
        <div class="navbar-content">
            <!-- Navigation Left: Brand & Hierarchy -->
            <div class="nav-left">
                <button class="sidebar-menu-bar" id="sidebarToggle" aria-label="Toggle sidebar" aria-expanded="false">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="breadcrumb-area d-none d-sm-flex">
                    <span class="main-path"><a href="{{ setRoute('user.dashboard') }}">{{ __('Dashboard') }}</a></span>
                    <i class="las la-angle-right"></i>
                    <span class="active-path">{{ __($page_title) ?? 'Dashboard' }}</span>
                </div>
            </div>

            <!-- Navigation Right: Functional Info & User Controls -->
            <div class="nav-right">
                <!-- Account Info Pill -->
                <div class="account-pill-v2" aria-label="Account Number" data-account-number="{{ auth()->user()->account_no }}">
                    <div class="pill-label">{{ __('ACCOUNT NUMBER') }}</div>
                    <div class="pill-value-group">
                        <span class="pill-number" title="{{ auth()->user()->account_no }}">
                            <span class="full-number d-none d-md-inline">{{ auth()->user()->account_no }}</span>
                            <span class="truncated-number d-inline d-md-none">***{{ substr(auth()->user()->account_no, -4) }}</span>
                        </span>
                        <button class="copy-trigger" onclick="copyAccountNo()" aria-label="Copy Account Number">
                            <i class="las la-copy"></i>
                        </button>
                    </div>
                </div>

                <!-- User Controls Group -->
                <div class="user-controls-group">
                    <div class="control-item theme-item">
                        <label class="theme-switch-v2" for="checkbox" aria-label="Toggle Dark Mode">
                            <input type="checkbox" id="checkbox" />
                            <div class="switch-slider">
                                <i class="las la-sun sun-icon"></i>
                                <i class="las la-moon moon-icon"></i>
                            </div>
                        </label>
                    </div>

                    <!-- More Actions Dropdown -->
                    <div class="control-item more-item">
                        <button class="nav-icon-v2" id="moreToggle" aria-label="More Actions" aria-haspopup="true" aria-expanded="false">
                            <i class="las la-ellipsis-v"></i>
                        </button>
                        <div class="enzo-dropdown enzo-dropdown-more" id="moreDropdown" style="display: none;">
                            <div class="dropdown-header">
                                <h6>{{ __('Quick Links') }}</h6>
                                <button class="dropdown-close" id="moreDropdownClose" aria-label="Close">
                                    <i class="las la-times"></i>
                                </button>
                            </div>
                            <div class="dropdown-body">
                                <a href="{{ setRoute('user.profile.index') }}" class="enzo-dropdown-item">
                                    <i class="las la-cog"></i>
                                    <span>{{ __('Settings') }}</span>
                                </a>
                                <a href="{{ setRoute('frontend.contact') }}" class="enzo-dropdown-item">
                                    <i class="las la-headset"></i>
                                    <span>{{ __('Help & Support') }}</span>
                                </a>
                                <a href="#" class="enzo-dropdown-item">
                                    <i class="las la-share-alt"></i>
                                    <span>{{ __('Refer a Friend') }}</span>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a href="{{ setRoute('frontend.index') }}" class="enzo-dropdown-item">
                                    <i class="las la-file-alt"></i>
                                    <span>{{ __('Privacy Policy') }}</span>
                                </a>
                                <a href="#" class="enzo-dropdown-item">
                                    <i class="las la-file-contract"></i>
                                    <span>{{ __('Terms of Service') }}</span>
                                </a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('user.logout') }}" style="display:contents;">
                                    @csrf
                                    <button type="submit" class="enzo-dropdown-item enzo-dropdown-item-danger" style="border:none;background:none;width:100%;cursor:pointer;">
                                        <i class="las la-sign-out-alt"></i>
                                        <span>{{ __('Log Out') }}</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="control-item notification-item">
                        @php
                            $user_notifications = get_user_notifications();
                            $unread_count = $user_notifications->where('seen', 0)->count();
                        @endphp
                        <button class="nav-icon-v2" id="notificationToggle" aria-label="View Notifications" aria-haspopup="true" aria-expanded="false">
                            <i class="las la-bell"></i>
                            @if($unread_count > 0)
                                <span class="notification-badge">{{ $unread_count > 99 ? '99+' : $unread_count }}</span>
                            @endif
                        </button>
                        <div class="notification-dropdown-v2" id="notificationDropdown" style="display: none;">
                            <div class="dropdown-header">
                                <h6>{{ __('Notifications') }}</h6>
                                <div class="dropdown-header-actions">
                                    @if($unread_count > 0)
                                        <button class="mark-all-read" id="markAllRead">{{ __('Mark all read') }}</button>
                                    @endif
                                </div>
                            </div>
                            <div class="dropdown-body">
                                <ul class="notification-list-v2">
                                    @forelse ($user_notifications->take(10) as $item)
                                        <li class="{{ $item->seen == 0 ? 'unread' : '' }}">
                                            <div class="notification-item-content">
                                                <div class="icon-box">
                                                    <i class="las la-info-circle"></i>
                                                </div>
                                                <div class="text-box">
                                                    <p class="message">{{ __($item->message->title ?? 'Notification') }}</p>
                                                    <span class="time">{{ $item->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        </li>
                                    @empty
                                        <li>
                                            <div class="notif-empty">
                                                <i class="las la-bell-slash"></i>
                                                <p>{{ __('No notifications yet') }}</p>
                                            </div>
                                        </li>
                                    @endforelse
                                </ul>
                            </div>
                            <div class="dropdown-footer">
                                <a href="{{ setRoute('user.transactions.index') }}">{{ __('View All Transactions') }}</a>
                            </div>
                        </div>
                    </div>

                    <div class="control-item user-item">
                        <a href="{{ setRoute('user.profile.index') }}" class="user-avatar-v2" aria-label="User Profile">
                            <img src="{{ auth()->user()->userImage }}" alt="{{ auth()->user()->username }}">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

@push('script')
<script>
    (function(){
        // --- Sidebar Logic ---
        const mqDesktop = window.matchMedia('(min-width: 992px)');
        const storageKey = 'sidebarVisibleDesktop';
        const body = document.body;
        const toggleBtn = document.getElementById('sidebarToggle');
        const overlay = document.getElementById('body-overlay') || document.querySelector('.body-overlay');

        function applyInitialState() {
            if (mqDesktop.matches) {
                const saved = localStorage.getItem(storageKey);
                const visible = saved === null ? false : saved === 'true';
                body.classList.toggle('sidebar-visible', visible);
                if (toggleBtn) toggleBtn.setAttribute('aria-expanded', String(visible));
            } else {
                body.classList.remove('sidebar-visible');
                if (toggleBtn) toggleBtn.setAttribute('aria-expanded', 'false');
            }
        }

        function setVisible(visible) {
            body.classList.toggle('sidebar-visible', visible);
            if (toggleBtn) toggleBtn.setAttribute('aria-expanded', String(visible));
            if (mqDesktop.matches) {
                localStorage.setItem(storageKey, String(visible));
            } else {
                if (overlay) {
                    overlay.classList.toggle('active', visible);
                }
            }
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function(e){
                e.preventDefault();
                const nowVisible = !body.classList.contains('sidebar-visible');
                setVisible(nowVisible);
            });
        }
        if (overlay) {
            overlay.addEventListener('click', function(e){
                setVisible(false);
            });
        }
        mqDesktop.addEventListener('change', applyInitialState);
        applyInitialState();

        // --- Theme Toggle Logic (Desktop) ---
        const themeToggle = document.getElementById('checkbox');
        const themeStorageKey = 'theme';

        if (themeToggle) {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            themeToggle.checked = (currentTheme === 'dark');

            themeToggle.addEventListener('change', function() {
                const newTheme = this.checked ? 'dark' : 'light';
                document.documentElement.classList.add('no-transitions');
                document.documentElement.setAttribute('data-theme', newTheme);
                localStorage.setItem(themeStorageKey, newTheme);
                setTimeout(() => {
                    document.documentElement.classList.remove('no-transitions');
                }, 300);
            });
        }

        // --- Theme Toggle Logic (Mobile) ---
        const mobileThemeToggle = document.getElementById('mobile-theme-checkbox');
        if (mobileThemeToggle) {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            mobileThemeToggle.checked = (currentTheme === 'dark');

            mobileThemeToggle.addEventListener('change', function() {
                const newTheme = this.checked ? 'dark' : 'light';
                document.documentElement.classList.add('no-transitions');
                document.documentElement.setAttribute('data-theme', newTheme);
                localStorage.setItem('theme', newTheme);
                setTimeout(() => {
                    document.documentElement.classList.remove('no-transitions');
                }, 300);
            });
        }

        // --- Dropdown Logic ---
        function closeAllDropdowns() {
            document.querySelectorAll('.enzo-dropdown.show, .notification-dropdown-v2.show').forEach(el => el.classList.remove('show'));
            document.querySelectorAll('[aria-expanded="true"]').forEach(el => el.setAttribute('aria-expanded', 'false'));
        }

        // Notification Dropdown
        const notifyToggle = document.getElementById('notificationToggle');
        const notifyDropdown = document.getElementById('notificationDropdown');

        if (notifyToggle && notifyDropdown) {
            notifyToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                const isOpen = notifyDropdown.classList.contains('show');
                closeAllDropdowns();
                if (!isOpen) {
                    notifyDropdown.classList.add('show');
                    this.setAttribute('aria-expanded', 'true');
                }
            });
        }

        // More Dropdown
        const moreToggle = document.getElementById('moreToggle');
        const moreDropdown = document.getElementById('moreDropdown');
        const moreClose = document.getElementById('moreDropdownClose');

        if (moreToggle && moreDropdown) {
            moreToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                const isOpen = moreDropdown.classList.contains('show');
                closeAllDropdowns();
                if (!isOpen) {
                    moreDropdown.classList.add('show');
                    this.setAttribute('aria-expanded', 'true');
                }
            });

            if (moreClose) {
                moreClose.addEventListener('click', function(e) {
                    e.stopPropagation();
                    moreDropdown.classList.remove('show');
                    moreToggle.setAttribute('aria-expanded', 'false');
                    moreToggle.focus();
                });
            }
        }

        // Shared outside click handler
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.enzo-dropdown, .notification-dropdown-v2') && !e.target.closest('.nav-icon-v2')) {
                closeAllDropdowns();
            }
        });

        // Shared escape key handler
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const open = document.querySelector('.enzo-dropdown.show, .notification-dropdown-v2.show');
                if (open) {
                    closeAllDropdowns();
                }
            }
        });

        // --- EnzoBank Click-to-Copy Logic ---
        window.copyAccountNo = async function(customValue = null, element = null) {
            const container = document.querySelector('.account-pill-v2');
            const accountNo = customValue || (container ? container.getAttribute('data-account-number') : '');
            const copyBtn = element || document.querySelector('.copy-trigger');
            const icon = copyBtn ? copyBtn.querySelector('i') : null;

            if (!accountNo) {
                showEnzoToast('error', 'Account number not found.');
                return;
            }

            try {
                if (navigator.clipboard && window.isSecureContext) {
                    await navigator.clipboard.writeText(accountNo.trim());
                } else {
                    const textArea = document.createElement("textarea");
                    textArea.value = accountNo.trim();
                    textArea.style.position = "fixed";
                    textArea.style.left = "-9999px";
                    textArea.style.top = "-9999px";
                    textArea.setAttribute('readonly', '');
                    document.body.appendChild(textArea);
                    textArea.focus();
                    textArea.select();
                    const successful = document.execCommand('copy');
                    document.body.removeChild(textArea);
                    if (!successful) throw new Error('Fallback copy failed');
                }

                if (icon) {
                    const originalClass = icon.className;
                    icon.className = 'las la-check-circle';
                    if (copyBtn) {
                        copyBtn.classList.add('copy-success');
                        copyBtn.setAttribute('aria-label', 'Account Number Copied');
                    }
                    setTimeout(() => {
                        icon.className = originalClass;
                        if (copyBtn) {
                            copyBtn.classList.remove('copy-success');
                            copyBtn.setAttribute('aria-label', 'Copy Account Number');
                        }
                    }, 2000);
                }

                showEnzoToast('success', 'Your EnzoBank account number has been copied!');
            } catch (err) {
                showEnzoToast('error', 'Unable to copy. Please try manually.');
            }
        };

        function showEnzoToast(type, message) {
            let toastContainer = document.getElementById('enzo-toast-container');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.id = 'enzo-toast-container';
                toastContainer.setAttribute('aria-live', 'polite');
                toastContainer.setAttribute('role', 'status');
                document.body.appendChild(toastContainer);
            }

            const toast = document.createElement('div');
            toast.className = `enzo-toast ${type}`;
            toast.innerHTML = `
                <div class="toast-icon">
                    <i class="las ${type === 'success' ? 'la-check-circle' : 'la-exclamation-triangle'}"></i>
                </div>
                <div class="toast-content">${message}</div>
            `;

            toastContainer.appendChild(toast);
            setTimeout(() => toast.classList.add('show'), 10);
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }

        let lastScrollTop = 0;
        const navbar = document.querySelector('.navbar-wrapper');
        const scrollThreshold = 100;

        window.addEventListener('scroll', function() {
            let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            if (scrollTop > lastScrollTop && scrollTop > scrollThreshold) {
                if (navbar) navbar.classList.add('nav-hidden');
            } else {
                if (navbar) navbar.classList.remove('nav-hidden');
            }
            lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
        }, { passive: true });

    })();
</script>
@endpush

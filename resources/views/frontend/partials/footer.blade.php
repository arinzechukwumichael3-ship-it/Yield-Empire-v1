<!-- ====== ENZOBANK FOOTER ====== -->
<footer class="enzo-footer" id="footer">
    <div class="enzo-container">
        <!-- Newsletter -->
        <div class="enzo-footer-newsletter">
            <span class="enzo-footer-newsletter-label">Stay updated</span>
            <form class="enzo-footer-newsletter-form" onsubmit="event.preventDefault(); alert('Subscribed!');">
                <input type="email" class="enzo-footer-newsletter-input" placeholder="Enter your email" required>
                <button type="submit" class="enzo-footer-newsletter-btn">Subscribe</button>
            </form>
        </div>

        <div class="enzo-footer-grid">
            <!-- Column 1: Brand -->
            <div class="enzo-footer-col enzo-footer-brand">
                <a href="{{ setRoute("frontend.index") }}" class="enzo-footer-logo">
                    <span class="enzonav-logo-icon">
                        <svg width="24" height="24" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="2" y="2" width="24" height="24" rx="6" fill="url(#fLogoGrad)"/>
                            <rect x="7" y="7" width="14" height="14" rx="3" fill="#060B14" opacity="0.9"/>
                            <path d="M14 10L18 14L14 18L10 14Z" fill="url(#fLogoGrad)"/>
                        </svg>
                    </span>
                    <span class="enzonav-logo-text">Enzo<span class="enzonav-logo-accent">Bank</span></span>
                </a>
                <p class="enzo-footer-desc">The future of digital banking. Secure, fast, and accessible from anywhere in the world.</p>
                <div class="enzo-footer-social">
                    <a href="#" class="enzo-footer-social-link" aria-label="Twitter/X">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    <a href="#" class="enzo-footer-social-link" aria-label="Instagram">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/></svg>
                    </a>
                    <a href="#" class="enzo-footer-social-link" aria-label="LinkedIn">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    </a>
                    <a href="#" class="enzo-footer-social-link" aria-label="Facebook">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="#" class="enzo-footer-social-link" aria-label="Telegram">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
                    </a>
                </div>
            </div>

            <!-- Column 2: Company -->
            <div class="enzo-footer-col">
                <h4 class="enzo-footer-heading">Company</h4>
                <ul class="enzo-footer-links">
                    <li><a href="{{ setRoute("frontend.about") }}">About Us</a></li>
                    <li><a href="{{ setRoute("frontend.services") }}">Services</a></li>
                    <li><a href="{{ setRoute("frontend.contact") }}">Contact</a></li>
                    <li><a href="{{ setRoute("frontend.web.journals") }}">Blog</a></li>
                </ul>
            </div>

            <!-- Column 3: Legal -->
            <div class="enzo-footer-col">
                <h4 class="enzo-footer-heading">Legal</h4>
                <ul class="enzo-footer-links">
                    <li><a href="{{ setRoute("frontend.useful.links", "privacy-policy") }}">Privacy Policy</a></li>
                    <li><a href="{{ setRoute("frontend.useful.links", "terms-of-service") }}">Terms of Service</a></li>
                    <li><a href="#">Cookie Policy</a></li>
                    <li><a href="#">GDPR Compliance</a></li>
                </ul>
            </div>

            <!-- Column 4: Support -->
            <div class="enzo-footer-col">
                <h4 class="enzo-footer-heading">Support</h4>
                <ul class="enzo-footer-links">
                    <li><a href="#">Help Center</a></li>
                    <li><a href="{{ setRoute("frontend.contact") }}">Contact Support</a></li>
                    <li><a href="#">API Documentation</a></li>
                    <li><a href="#">System Status</a></li>
                </ul>
            </div>

            <!-- Column 5: Regulatory -->
            <div class="enzo-footer-col">
                <h4 class="enzo-footer-heading">Regulation</h4>
                <ul class="enzo-footer-links">
                    <li><a href="#">FDIC Insurance</a></li>
                    <li><a href="#">FinCEN Registration</a></li>
                    <li><a href="#">State Licenses</a></li>
                    <li><a href="#">Compliance</a></li>
                </ul>
            </div>
        </div>

        <div class="enzo-footer-divider"></div>

        <div class="enzo-footer-address-row">
            <div class="enzo-footer-address-item">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <span>123 Financial Plaza, Suite 400, New York, NY 10001, USA</span>
            </div>
            <div class="enzo-footer-address-item">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                <span>+1 (800) 123-4567</span>
            </div>
        </div>

        <div class="enzo-footer-regulatory">
            <p><strong>Regulatory Disclosure:</strong> EnzoBank is a financial technology company headquartered in New York, USA. Banking services are provided through our regulated partner financial institutions. EnzoBank is registered as a Money Services Business (MSB) with FinCEN and complies with all applicable US federal and state banking regulations. Deposit accounts are FDIC insured up to $250,000 through our partner banks. EnzoBank is licensed and regulated in the United States.</p>
        </div>

        <div class="enzo-footer-bottom">
            <span class="enzo-footer-copy">&copy; {{ date("Y") }} EnzoBank. All rights reserved.</span>
            <div class="enzo-footer-legal">
                <a href="#">Privacy</a>
                <a href="#">Terms</a>
                <a href="#">Cookies</a>
            </div>
        </div>

        <div class="enzo-footer-made-with">Made with ❤️ for the future of banking</div>
    </div>
</footer>

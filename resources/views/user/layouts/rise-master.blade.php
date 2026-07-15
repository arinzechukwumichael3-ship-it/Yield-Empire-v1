<!DOCTYPE html>
<html lang="{{ get_default_language_code() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('frontend/css/baking-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/rise-theme.css') }}?v=2">
    <link rel="stylesheet" href="{{ asset('frontend/css/enzo-theme.css') }}">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    @include("partials.header-asset")
    <script>
        (function() {
            var saved = localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-theme', saved);
        })();
    </script>
    <title>{{ (isset($page_title) ? __($page_title) : __("Dashboard")) }}</title>
    @stack("css")
    <style>
    /* ── Notification System ── */
    .notif-bell {
        position: fixed; top: 14px; right: 16px; z-index: 100;
        width: 40px; height: 40px; border-radius: 50%;
        background: #1E293B; border: 1.5px solid #334155;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all 0.2s;
        -webkit-tap-highlight-color: transparent;
    }
    .notif-bell:active { transform: scale(0.92); }
    .notif-bell svg { color: #94A3B8; width: 20px; height: 20px; }
    .notif-badge {
        position: absolute; top: -4px; right: -4px;
        min-width: 18px; height: 18px; border-radius: 50%;
        background: #EF4444; color: #fff; font-size: 10px; font-weight: 700;
        display: flex; align-items: center; justify-content: center;
        padding: 0 4px; border: 2px solid #0B1121;
        transform: scale(0); transition: transform 0.25s ease;
    }
    .notif-badge.show { transform: scale(1); }
    .notif-badge.pulse { animation: notifPulse 1.5s infinite; }
    @keyframes notifPulse { 0%,100% { transform: scale(1); } 50% { transform: scale(1.15); } }

    /* Notification Panel */
    .notif-panel {
        position: fixed; top: 62px; right: 16px; z-index: 100;
        width: 320px; max-width: calc(100vw - 32px);
        background: #111827; border: 1px solid #1E293B;
        border-radius: 16px; box-shadow: 0 16px 48px rgba(0,0,0,0.5);
        opacity: 0; transform: translateY(-8px) scale(0.96);
        pointer-events: none; transition: all 0.25s ease;
        max-height: 70vh; overflow-y: auto;
    }
    .notif-panel.open { opacity: 1; transform: translateY(0) scale(1); pointer-events: auto; }
    .notif-panel-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 14px 16px; border-bottom: 1px solid #1E293B;
    }
    .notif-panel-title { font-size: 15px; font-weight: 700; color: #fff; }
    .notif-panel-clear {
        font-size: 12px; font-weight: 600; color: #64748B;
        cursor: pointer; background: none; border: none; padding: 4px 8px;
        border-radius: 6px; transition: color 0.15s;
    }
    .notif-panel-clear:hover { color: #EF4444; }
    .notif-item {
        display: flex; align-items: center; gap: 10px;
        padding: 12px 16px; border-bottom: 1px solid rgba(30,41,59,0.5);
        cursor: pointer; transition: background 0.15s;
        text-decoration: none;
    }
    .notif-item:last-child { border-bottom: none; }
    .notif-item:hover { background: rgba(59,130,246,0.04); }
    .notif-item.unread { background: rgba(59,130,246,0.06); }
    .notif-dot {
        width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0;
    }
    .notif-dot.credit { background: #22C55E; }
    .notif-dot.debit { background: #EF4444; }
    .notif-dot.bonus { background: #A855F7; }
    .notif-dot.transfer { background: #F59E0B; }
    .notif-item-info { flex: 1; min-width: 0; }
    .notif-item-title { font-size: 13px; font-weight: 600; color: #F1F5F9; display: block; }
    .notif-item-sub { font-size: 11px; color: #64748B; display: block; margin-top: 1px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .notif-item-amt { font-size: 13px; font-weight: 700; flex-shrink: 0; }
    .notif-item-amt.positive { color: #22C55E; }
    .notif-item-amt.negative { color: #EF4444; }
    .notif-empty {
        padding: 40px 20px; text-align: center; color: #64748B; font-size: 13px;
    }
    .notif-overlay {
        position: fixed; inset: 0; z-index: 99;
        background: rgba(0,0,0,0.3);
        opacity: 0; pointer-events: none; transition: opacity 0.25s;
    }
    .notif-overlay.open { opacity: 1; pointer-events: auto; }

    /* Toast notification */
    .notif-toast {
        position: fixed; bottom: 90px; left: 50%; transform: translateX(-50%) translateY(100px);
        background: #1E293B; border: 1px solid #334155;
        border-radius: 14px; padding: 14px 18px; z-index: 200;
        box-shadow: 0 12px 40px rgba(0,0,0,0.5);
        transition: transform 0.4s ease, opacity 0.4s ease;
        opacity: 0; pointer-events: none;
        max-width: calc(100vw - 32px); width: 360px;
    }
    .notif-toast.show { transform: translateX(-50%) translateY(0); opacity: 1; pointer-events: auto; }
    .notif-toast-row { display: flex; align-items: center; gap: 10px; }
    .notif-toast-icon {
        width: 36px; height: 36px; border-radius: 50%; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
    }
    .notif-toast-icon.credit { background: rgba(34,197,94,0.15); color: #22C55E; }
    .notif-toast-icon.debit { background: rgba(239,68,68,0.15); color: #EF4444; }
    .notif-toast-info { flex: 1; min-width: 0; }
    .notif-toast-title { font-size: 13px; font-weight: 600; color: #F1F5F9; }
    .notif-toast-sub { font-size: 11px; color: #94A3B8; }
    .notif-toast-close {
        background: none; border: none; color: #64748B; padding: 4px; cursor: pointer;
        display: flex; transition: color 0.15s;
    }
    .notif-toast-close:hover { color: #fff; }
    .notif-toast-progress {
        position: absolute; bottom: 0; left: 0; height: 3px;
        border-radius: 0 0 14px 14px; background: #3B82F6;
        transition: width 4s linear;
    }
    </style>

</head>
<body>
    @include('frontend.partials.body-overlay')
    @include('partials.global-nav')

    <div class="rise-app">
        <div class="rise-screen">
            @yield('content')
        </div>
        @include('user.partials.glass-bottom-nav')
    </div>

    @include('partials.footer-asset')
    @include('user.partials.push-notification')


    <!-- WhatsApp Floating Widget -->
    <div class="whatsapp-widget" data-aos="zoom-in" data-aos-delay="500" style="bottom: 120px; right: 20px;">
        <a href="https://wa.me/message/ZW7EJRXHGL3GG1" target="_blank" class="whatsapp-btn" rel="noopener noreferrer" aria-label="Contact Support on WhatsApp">
            <div class="whatsapp-icon">
                <i class="lab la-whatsapp"></i>
                <span class="online-dot"></span>
            </div>
            <div class="whatsapp-text d-none d-lg-flex">
                <span>{{ __('Chat with us') }}</span>
            </div>
        </a>
    </div>

    @stack("script")
    <script>
        AOS.init({ duration: 1000, once: true });
    </script>
    <script>
    (function(){
        document.addEventListener('DOMContentLoaded', function(){ document.documentElement.classList.remove('no-transitions'); }, { once: true });
    })();
    </script>
    <script>
    // --- Site-wide currency preference ---
    (function(){
        var currencySymbols = { usd: '$', eur: '€', gbp: '£' };
        var saved = localStorage.getItem('dash_currency');
        window.__preferredCurrency = saved || 'usd';

        // Update any element with data-currency-amount attribute
        var els = document.querySelectorAll('[data-currency-amount]');
        if (els.length) {
            var sym = currencySymbols[window.__preferredCurrency] || '$';
            els.forEach(function(el) {
                var val = el.getAttribute('data-currency-amount');
                if (val) el.textContent = sym + val;
            });
        }
    })();
    </script>
    @auth
    @php
        $typeLabels = [
            "ADD-MONEY" => "Deposit", "MONEY-OUT" => "Withdrawal", "WITHDRAW" => "Withdrawal",
            "BONUS" => "Referral Bonus", "COMMISSION" => "Commission",
            "OWN-BANK-TRANSFER" => "Own Transfer", "OTHER-BANK-TRANSFER" => "Bank Transfer",
            "TRANSFER-MONEY" => "Transfer", "MONEY-EXCHANGE" => "Currency Exchange",
            "ADD-SUBTRACT-BALANCE" => "Adjustment", "MAKE-PAYMENT" => "Payment",
            "CAPITAL-RETURN" => "Capital Return", "VIRTUAL-CARD" => "Virtual Card",
            "MOBILE-WALLET-TRANSFER" => "Mobile Wallet", "Salary Disbursement" => "Salary",
        ];
        $recentNotifs = \App\Models\Transaction::where("user_id", auth()->id())
            ->where("status", 1)
            ->latest()
            ->take(5)
            ->get()
            ->map(function($t) use ($typeLabels) {
                $det = is_string($t->details) ? json_decode($t->details) : $t->details;
                $isCredit = in_array($t->type, ["ADD-MONEY","BONUS","COMMISSION","CAPITAL-RETURN","Salary Disbursement"])
                    && (!in_array($t->type, ["TRANSFER-MONEY","OWN-BANK-TRANSFER","OTHER-BANK-TRANSFER"]) || ($t->receiver_id ?? null) == auth()->id());
                return [
                    "id" => $t->id,
                    "trx" => $t->trx_id,
                    "type" => $t->type,
                    "label" => $typeLabels[$t->type] ?? ucwords(str_replace(["-","_"], " ", strtolower($t->type))),
                    "amount" => (float)$t->request_amount,
                    "is_credit" => $isCredit,
                    "desc" => $det->description ?? "",
                    "date" => $t->created_at ? $t->created_at->format("M d, Y h:i A") : "",
                    "created" => $t->created_at ? $t->created_at->timestamp : 0,
                ];
            })->values();
    @endphp
    <script>
    // ── Notification System ──
    (function(){
        var recentNotifs = @json($recentNotifs);
        var notifKey = "enzobank_notif_seen";
        var stored = localStorage.getItem(notifKey);
        var seenIds = stored ? JSON.parse(stored) : [];
        var newNotifs = recentNotifs.filter(function(n) { return seenIds.indexOf(n.id) === -1; });

        // Update badge
        var badge = document.getElementById("notifBadge");
        if (badge && newNotifs.length > 0) {
            badge.textContent = newNotifs.length;
            badge.classList.add("show", "pulse");
        }

        // Render notification list
        function renderNotifList(notifs) {
            var list = document.getElementById("notifList");
            if (!list) return;
            if (!notifs || notifs.length === 0) {
                list.innerHTML = "<div class=\"notif-empty\">All caught up 🎉</div>";
                return;
            }
            list.innerHTML = notifs.map(function(n) {
                var isNew = newNotifs.some(function(nn) { return nn.id === n.id; });
                var dotClass = n.is_credit ? "credit" : (n.type === "BONUS" ? "bonus" : "debit");
                return "<a href=\"/user/transactions\" class=\"notif-item" + (isNew ? " unread" : "") + "\">" +
                    "<span class=\"notif-dot " + dotClass + "\"></span>" +
                    "<div class=\"notif-item-info\">" +
                        "<span class=\"notif-item-title\">" + (n.is_credit ? "+" + n.amount.toLocaleString("en-US", {style:"currency",currency:"USD"}) : "-" + n.amount.toLocaleString("en-US", {style:"currency",currency:"USD"})) + " " + n.label + "</span>" +
                        "<span class=\"notif-item-sub\">" + (n.desc || n.label) + " · " + n.date + "</span>" +
                    "</div>" +
                    "<span class=\"notif-item-amt " + (n.is_credit ? "positive" : "negative") + "\">" + (n.is_credit ? "+" : "-") + "$" + n.amount.toLocaleString("en-US", {minimumFractionDigits:2}) + "</span>" +
                "</a>";
            }).join("");
        }
        renderNotifList(recentNotifs);

        // Show toast for new notifications
        if (newNotifs.length > 0) {
            var toast = document.getElementById("notifToast");
            var toastTitle = document.getElementById("toastTitle");
            var toastSub = document.getElementById("toastSub");
            var toastIcon = document.getElementById("toastIcon");
            var toastProgress = document.getElementById("toastProgress");
            if (toast && newNotifs[0]) {
                var n = newNotifs[0];
                toastTitle.textContent = (n.is_credit ? "Received " : "Sent ") + n.label;
                toastSub.textContent = (n.desc || n.label) + " · " + n.date;
                toastIcon.className = "notif-toast-icon " + (n.is_credit ? "credit" : "debit");
                if (n.type === "BONUS") toastIcon.className = "notif-toast-icon credit";
                // Show toast
                setTimeout(function() {
                    toast.classList.add("show");
                    toastProgress.style.width = "100%";
                    setTimeout(function() { toastProgress.style.width = "0%"; }, 50);
                    setTimeout(function() { toast.classList.remove("show"); }, 4500);
                }, 1000);
            }
        }

        // Store seen IDs
        var allIds = recentNotifs.map(function(n) { return n.id; });
        localStorage.setItem(notifKey, JSON.stringify(allIds));

        // Expose functions globally
        window.toggleNotifPanel = function() {
            document.getElementById("notifPanel").classList.toggle("open");
            document.getElementById("notifOverlay").classList.toggle("open");
        };
        window.closeNotifPanel = function() {
            document.getElementById("notifPanel").classList.remove("open");
            document.getElementById("notifOverlay").classList.remove("open");
        };
        window.clearNotifBadge = function() {
            if (badge) { badge.classList.remove("show", "pulse"); badge.textContent = "0"; }
        };
        window.closeToast = function() {
            document.getElementById("notifToast").classList.remove("show");
        };
    })();
    </script>
    @endauth

</body>
</html>

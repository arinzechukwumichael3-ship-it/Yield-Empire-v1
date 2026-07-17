@extends('frontend.layouts.master')

@section('content')
<section class="verification-otp ptb-80">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-7 col-md-9">
                <div class="otp-card animate-in @if(session('error')) is-error @endif">
                    <div class="otp-card-head text-center">
                        <div class="otp-shield">
                            <i class="las la-shield-alt"></i>
                        </div>
                        <h3 class="title">{{ __("Two Factor Authorization") }}</h3>
                        <p class="subtitle">{{ __("Enter the 6-digit code from your authenticator app to continue.") }}</p>
                    </div>

                    @if(session('error'))
                        <div class="otp-alert">
                            <i class="las la-exclamation-circle"></i>
                            <span>{{ __(is_array(session('error')) ? session('error')[0] : session('error')) }}</span>
                        </div>
                    @endif

                    <form method="POST" class="otp-form" action="{{ setRoute('user.authorize.google.2fa.submit') }}" id="otpForm" autocomplete="off">
                        @csrf
                        <div class="otp-inputs" id="otpInputs">
                            <input class="otp-input" type="text" name="code[]" inputmode="numeric" autocomplete="one-time-code" maxlength="1" required autofocus>
                            <input class="otp-input" type="text" name="code[]" inputmode="numeric" autocomplete="one-time-code" maxlength="1" required>
                            <input class="otp-input" type="text" name="code[]" inputmode="numeric" autocomplete="one-time-code" maxlength="1" required>
                            <input class="otp-input" type="text" name="code[]" inputmode="numeric" autocomplete="one-time-code" maxlength="1" required>
                            <input class="otp-input" type="text" name="code[]" inputmode="numeric" autocomplete="one-time-code" maxlength="1" required>
                            <input class="otp-input" type="text" name="code[]" inputmode="numeric" autocomplete="one-time-code" maxlength="1" required>
                        </div>
                        <button type="submit" class="btn--base w-100 btn-loading otp-submit">
                            <i class="las la-fingerprint"></i> {{ __("Verify & Continue") }}
                        </button>
                    </form>

                    <div class="otp-footer">
                        <a href="{{ setRoute('user.login') }}" class="otp-link">
                            <i class="las la-sign-out-alt"></i> {{ __("Back to Login") }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('style')
<style>
    .otp-card {
        background: #fff;
        border-radius: 20px;
        padding: 38px 32px;
        box-shadow: 0 20px 60px rgba(20,30,60,.12);
        border: 1px solid #eef1f7;
        opacity: 0;
        transform: translateY(18px) scale(.98);
        animation: otpIn .5s cubic-bezier(.2,.8,.3,1.1) forwards;
    }
    @keyframes otpIn { to { opacity:1; transform:none; } }

    .otp-shield {
        width: 70px; height: 70px; margin: 0 auto 14px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 30px; color: #fff;
        background: linear-gradient(135deg, #2a7de1, #5b8def);
        box-shadow: 0 10px 24px rgba(42,125,225,.35);
        animation: shieldPulse 2.4s ease-in-out infinite;
    }
    @keyframes shieldPulse {
        0%,100% { transform: translateY(0); box-shadow: 0 10px 24px rgba(42,125,225,.30); }
        50% { transform: translateY(-4px); box-shadow: 0 16px 30px rgba(42,125,225,.45); }
    }
    .otp-card .title { font-size: 22px; font-weight: 700; margin-bottom: 6px; }
    .otp-card .subtitle { color: #7a8295; font-size: 14px; margin-bottom: 22px; }

    .otp-alert {
        display: flex; align-items: center; gap: 8px;
        background: #fff1f0; border: 1px solid #ffccc7; color: #cf1322;
        padding: 10px 14px; border-radius: 12px; font-size: 13px; margin-bottom: 18px;
    }

    .otp-inputs { display: flex; gap: 10px; justify-content: center; margin-bottom: 22px; }
    .otp-input {
        width: 52px; height: 60px; text-align: center;
        font-size: 24px; font-weight: 700; color: #1f2d3d;
        border: 2px solid #e3e8f0; border-radius: 14px;
        background: #f8fafc; outline: none;
        transition: border-color .2s ease, box-shadow .2s ease, transform .15s ease;
    }
    .otp-input:focus {
        border-color: #2a7de1; background: #fff;
        box-shadow: 0 0 0 4px rgba(42,125,225,.15);
        transform: translateY(-2px);
    }
    .otp-input.filled { border-color: #2a7de1; }

    .otp-submit {
        display: flex; align-items: center; justify-content: center; gap: 8px;
        font-size: 15px; padding: 13px; border-radius: 12px;
    }

    .otp-footer { text-align: center; margin-top: 18px; }
    .otp-link { color: #6b7280; font-size: 13px; text-decoration: none; transition: color .2s; }
    .otp-link:hover { color: #2a7de1; }

    .otp-card.is-error { animation: otpShake .4s ease; border-color: #ffccc7; }
    @keyframes otpShake {
        10%,90% { transform: translateX(-2px); }
        20%,80% { transform: translateX(4px); }
        30%,50%,70% { transform: translateX(-8px); }
        40%,60% { transform: translateX(8px); }
    }
    .otp-card.is-error .otp-input { border-color: #ffa39e; }
</style>
@endpush

@push('script')
    <script>
    (function () {
        const form   = document.getElementById('otpForm');
        const inputs = Array.from(document.querySelectorAll('.otp-input'));

        function focusFirstEmpty() {
            const first = inputs.find(i => !i.value) || inputs[inputs.length - 1];
            first.focus();
        }

        inputs.forEach((input, i) => {
            input.addEventListener('input', (e) => {
                e.target.value = e.target.value.replace(/\D/g, '').slice(0, 1);
                if (e.target.value) input.classList.add('filled'); else input.classList.remove('filled');
                if (e.target.value && i < inputs.length - 1) inputs[i + 1].focus();
                maybeSubmit();
            });
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !e.target.value && i > 0) inputs[i - 1].focus();
                if (e.key === 'ArrowLeft' && i > 0) inputs[i - 1].focus();
                if (e.key === 'ArrowRight' && i < inputs.length - 1) inputs[i + 1].focus();
            });
            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const text = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, inputs.length);
                text.split('').forEach((ch, idx) => { if (inputs[idx]) { inputs[idx].value = ch; inputs[idx].classList.add('filled'); } });
                inputs[Math.min(text.length, inputs.length - 1)].focus();
                maybeSubmit();
            });
        });

        function maybeSubmit() {
            if (inputs.every(i => i.value)) {
                setTimeout(() => form.requestSubmit ? form.requestSubmit() : form.submit(), 180);
            }
        }

        focusFirstEmpty();
    })();
    </script>
@endpush

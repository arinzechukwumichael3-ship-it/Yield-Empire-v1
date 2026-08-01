@extends("user.layouts.rise-master")

@push("css")
<style>
.cd-cfm-page { background: #F3F4F6; min-height: calc(100vh - 72px); padding-bottom: 24px; }
.cd-cfm-header { display: flex; align-items: center; padding: 20px 16px; position: sticky; top: 0; background: #F3F4F6; z-index: 10; }
.cd-cfm-header-left { display: flex; align-items: center; gap: 12px; }
.cd-cfm-back { width: 36px; height: 36px; border-radius: 50%; background: white; display: flex; align-items: center; justify-content: center; color: #374151; box-shadow: 0 1px 3px rgba(0,0,0,0.06); text-decoration: none; }
.cd-cfm-title { font-size: 18px; font-weight: 700; color: #111827; }
.cd-cfm-body { padding: 0 16px; display: flex; flex-direction: column; gap: 16px; }
.cd-upload-card { background: white; border-radius: 20px; padding: 24px 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
.cd-upload-title { font-size: 16px; font-weight: 700; color: #111827; margin-bottom: 4px; }
.cd-upload-sub { font-size: 13px; color: #9CA3AF; margin-bottom: 16px; }
.cd-dropzone { border: 2px dashed #D1D5DB; border-radius: 14px; padding: 32px 20px; text-align: center; cursor: pointer; transition: all 0.15s; }
.cd-dropzone:hover { border-color: #3B82F6; background: rgba(59,130,246,0.08); }
.cd-dropzone.has-file { border-color: #3B82F6; background: rgba(59,130,246,0.08); }
.cd-dropzone-icon { width: 48px; height: 48px; border-radius: 50%; background: #F3F4F6; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; color: #6B7280; }
.cd-dropzone-text { font-size: 15px; font-weight: 600; color: #374151; }
.cd-dropzone-hint { font-size: 12px; color: #9CA3AF; margin-top: 4px; }
.cd-dropzone input[type="file"] { display: none; }
.cd-divider { display: flex; align-items: center; gap: 12px; margin: 16px 0; color: #9CA3AF; font-size: 13px; }
.cd-divider-line { flex: 1; height: 1px; background: #E5E7EB; }
.cd-input-group { margin-bottom: 4px; }
.cd-input-group label { font-size: 13px; font-weight: 600; color: #374151; display: block; margin-bottom: 6px; }
.cd-input-group input { width: 100%; padding: 14px 16px; border: 1.5px solid #E5E7EB; border-radius: 12px; font-size: 14px; color: #111827; outline: none; transition: border-color 0.15s; }
.cd-input-group input:focus { border-color: #3B82F6; }
.cd-input-group .cd-hint { font-size: 12px; color: #9CA3AF; margin-top: 4px; }
.cd-summary-card { background: white; border-radius: 20px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
.cd-summary-title { font-size: 16px; font-weight: 700; color: #111827; margin-bottom: 14px; }
.cd-summary-row { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; font-size: 14px; }
.cd-summary-row + .cd-summary-row { border-top: 1px solid #F3F4F6; }
.cd-summary-label { color: #6B7280; }
.cd-summary-value { color: #111827; font-weight: 600; text-align: right; max-width: 60%; }
.cd-summary-value .cd-truncate { display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 180px; }
.cd-status-badge { font-size: 12px; font-weight: 600; padding: 4px 12px; border-radius: 999px; background: #FEF3C7; color: #D97706; }
.cd-submit-btn { width: 100%; padding: 16px; background: #3B82F6; color: white; border: none; border-radius: 999px; font-size: 16px; font-weight: 700; cursor: pointer; transition: all 0.15s; }
.cd-submit-btn:hover { background: #1D4ED8; }
.cd-error { font-size: 12px; color: #EF4444; margin-top: 4px; }
.cd-help-card { background: white; border: 1px solid #E5E7EB; border-left: 4px solid #25D366; border-radius: 16px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
.cd-help-head { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 14px; }
.cd-help-icon { width: 42px; height: 42px; border-radius: 12px; background: rgba(37,211,102,0.12); color: #128C7E; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.cd-help-text { flex: 1; }
.cd-help-title { font-size: 15px; font-weight: 700; color: #111827; }
.cd-help-sub { font-size: 12.5px; color: #6B7280; line-height: 1.5; margin-top: 3px; }
.cd-help-cta { display: flex; align-items: center; gap: 10px; width: 100%; padding: 14px 16px; background: #25D366; color: white; border: none; border-radius: 12px; font-size: 14px; font-weight: 700; text-decoration: none; transition: background 0.15s; }
.cd-help-cta:hover { background: #1EBE5D; color: white; }
.cd-help-cta i { font-size: 20px; }
.cd-help-cta-arrow { margin-left: auto; }
.cd-help-accordion { display: flex; align-items: center; justify-content: space-between; gap: 10px; width: 100%; margin-top: 12px; padding: 12px 14px; background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 12px; font-size: 13px; font-weight: 600; color: #374151; cursor: pointer; text-align: left; transition: all 0.15s; }
.cd-help-accordion:hover { border-color: #D1D5DB; background: #F3F4F6; }
.cd-help-chevron { color: #9CA3AF; transition: transform 0.2s; }
.cd-help-steps { display: none; margin-top: 12px; }
.cd-help-step { display: flex; gap: 12px; padding: 10px 0; }
.cd-help-step + .cd-help-step { border-top: 1px solid #F3F4F6; }
.cd-help-step-num { width: 24px; height: 24px; border-radius: 50%; background: #3B82F6; color: white; font-size: 12px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px; }
.cd-help-step-body { flex: 1; }
.cd-help-step-title { font-size: 13.5px; font-weight: 700; color: #111827; }
.cd-help-step-desc { font-size: 12.5px; color: #6B7280; line-height: 1.5; margin-top: 3px; }
.cd-help-note { margin-top: 10px; padding: 10px 14px; background: #F0FDF4; border: 1px solid #BBF7D0; border-radius: 10px; font-size: 12.5px; color: #166534; line-height: 1.5; }
</style>
@endpush

@section("content")
<div class="cd-cfm-page">
    <div class="cd-cfm-header">
        <div class="cd-cfm-header-left">
            <a href="{{ setRoute("user.crypto.deposit.address", ["coin_key" => $coinKey, "amount" => $amount]) }}" class="cd-cfm-back">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            </a>
            <span class="cd-cfm-title">Confirm Payment</span>
        </div>
    </div>

    <form method="POST" action="{{ setRoute("user.crypto.deposit.submit") }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="coin_key" value="{{ $coinKey }}">
        <input type="hidden" name="amount" value="{{ $amount }}">

        <div class="cd-cfm-body">
            <!-- Upload Proof -->
            <div class="cd-upload-card">
                <div class="cd-upload-title">Upload Proof</div>
                <div class="cd-upload-sub">Upload a screenshot or enter your transaction hash</div>

                <div class="cd-dropzone" id="dropzone" onclick="document.getElementById('proofInput').click()">
                    <div class="cd-dropzone-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    </div>
                    <div class="cd-dropzone-text" id="dropzoneText">Tap to upload screenshot</div>
                    <div class="cd-dropzone-hint">JPG or PNG, up to 5MB</div>
                    <input type="file" name="proof" id="proofInput" accept=".jpg,.jpeg,.png">
                </div>
                @error("proof")<div class="cd-error">{{ $message }}</div>@enderror

                <div class="cd-divider">
                    <span class="cd-divider-line"></span>
                    <span>OR</span>
                    <span class="cd-divider-line"></span>
                </div>

                <div class="cd-input-group">
                    <label for="txHash">Transaction Hash / ID</label>
                    <input type="text" name="tx_hash" id="txHash" placeholder="Enter TX hash..." value="{{ old("tx_hash") }}">
                    <div class="cd-hint">Find this in your crypto wallet&rsquo;s transaction history</div>
                </div>
                @error("tx_hash")<div class="cd-error">{{ $message }}</div>@enderror
            </div>

            <!-- Payment Summary -->
            <div class="cd-summary-card">
                <div class="cd-summary-title">Payment Summary</div>
                <div class="cd-summary-row">
                    <span class="cd-summary-label">Coin</span>
                    <span class="cd-summary-value">{{ $coin["name"] }}</span>
                </div>
                <div class="cd-summary-row">
                    <span class="cd-summary-label">Network</span>
                    <span class="cd-summary-value">{{ $coin["network"] }}</span>
                </div>
                <div class="cd-summary-row">
                    <span class="cd-summary-label">Amount</span>
                    <span class="cd-summary-value">${{ number_format($amount, 2) }}</span>
                </div>
                <div class="cd-summary-row">
                    <span class="cd-summary-label">Wallet Address</span>
                    <span class="cd-summary-value"><span class="cd-truncate">{{ $coin["address"] }}</span></span>
                </div>
                <div class="cd-summary-row">
                    <span class="cd-summary-label">Status</span>
                    <span class="cd-summary-value"><span class="cd-status-badge">&#9679; Pending Confirmation</span></span>
                </div>
            </div>

            <button type="submit" class="cd-submit-btn">Submit Payment</button>
        </div>
    </form>

    @include("user.partials.crypto-support-help", [
        "coin" => $coin,
        "amount" => $amount,
        "walletAddress" => $coin["address"],
        "helpTitle" => __("Something not right with your payment?"),
        "helpIntro" => __("Our support team can verify your transfer and help you complete your deposit on WhatsApp."),
    ])
</div>

@push("script")
<script>
document.addEventListener("DOMContentLoaded", function() {
    var dropzone = document.getElementById("dropzone");
    var proofInput = document.getElementById("proofInput");
    var dropzoneText = document.getElementById("dropzoneText");

    // Click dropzone to trigger file input
    dropzone.addEventListener("click", function() {
        proofInput.click();
    });

    // Prevent click on the input from bubbling back to dropzone
    proofInput.addEventListener("click", function(e) {
        e.stopPropagation();
    });

    // Update dropzone when file selected
    proofInput.addEventListener("change", function() {
        if (this.files && this.files[0]) {
            dropzone.classList.add("has-file");
            dropzoneText.textContent = this.files[0].name;
        } else {
            dropzone.classList.remove("has-file");
            dropzoneText.textContent = "Tap to upload screenshot";
        }
    });
});
</script>
@endpush
@endsection

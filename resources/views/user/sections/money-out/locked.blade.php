@extends('user.layouts.master')

@push('css')
<style>
.mo-locked { max-width: 520px; margin: 60px auto; padding: 40px 24px; text-align: center; }
.mo-locked-icon { font-size: 56px; margin-bottom: 20px; }
.mo-locked h2 { font-size: 24px; font-weight: 700; color: #fff; margin-bottom: 16px; }
.mo-locked h2 span { color: #F59E0B; }
.mo-locked-divider { width: 40px; height: 3px; background: linear-gradient(135deg, #3B82F6, #06B6D4); border-radius: 2px; margin: 0 auto 24px; }
.mo-locked .reason-title { font-size: 16px; font-weight: 600; color: #fff; margin-bottom: 16px; }
.mo-locked ul { list-style: none; padding: 0; margin: 0 0 24px; text-align: left; display: inline-block; }
.mo-locked ul li { display: flex; align-items: center; gap: 10px; font-size: 14px; color: #94A3B8; padding: 6px 0; }
.mo-locked ul li .check { color: #10B981; font-weight: 700; }
.mo-locked ul li .cross { color: #EF4444; font-weight: 700; }
.mo-locked .warning-box { background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.2); border-radius: 12px; padding: 14px 18px; margin-bottom: 28px; font-size: 13px; color: #FCA5A5; display: flex; align-items: center; gap: 10px; }
.mo-locked-actions { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
.mo-locked-btn { display: inline-flex; align-items: center; gap: 8px; padding: 14px 28px; background: linear-gradient(135deg, #3B82F6, #2563EB); color: #fff; font-size: 15px; font-weight: 600; border-radius: 999px; text-decoration: none; transition: all 0.2s; }
.mo-locked-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(59,130,246,0.3); }
.mo-locked-btn-ghost { background: transparent; border: 1px solid rgba(255,255,255,0.2); color: #fff; }
.mo-locked-btn-ghost:hover { border-color: rgba(255,255,255,0.4); background: rgba(255,255,255,0.05); }
</style>
@endpush

@section('content')
<div class="mo-locked">
    <div class="mo-locked-icon">🔒</div>
    <h2>Withdrawal <span>Locked</span></h2>
    <div class="mo-locked-divider"></div>

    <div class="reason-title">Why is withdrawal locked?</div>
    <p style="font-size:14px;color:#94A3B8;line-height:1.7;margin-bottom:20px;">
        For security and compliance, withdrawals require at least one personal crypto deposit before funds can be withdrawn.
    </p>

    <ul>
        <li><span class="check">✓</span> Account verification</li>
        <li><span class="check">✓</span> Anti-money laundering compliance</li>
        <li><span class="check">✓</span> Platform security</li>
        <li><span class="cross">✗</span> Received funds from transfers do not qualify.</li>
    </ul>

    <div class="warning-box">
        <span>ℹ️</span>
        <span>Make a crypto deposit of $10+ to unlock withdrawals.</span>
    </div>

    <div class="mo-locked-actions">
        <a href="{{ route('user.crypto.deposit.index') }}" class="mo-locked-btn">Make a Deposit &rarr;</a>
        <a href="#" class="mo-locked-btn mo-locked-btn-ghost">Learn More</a>
    </div>
</div>
@endsection

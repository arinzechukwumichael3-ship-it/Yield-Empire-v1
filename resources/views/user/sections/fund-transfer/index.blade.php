@extends('user.layouts.rise-master')

@push('css')
<style>
.ft-search-wrap { position: relative; flex: 1; }
.ft-search-input { width:100%; padding:10px 14px 10px 36px; border:1px solid #334155; border-radius:10px; font-size:14px; background:#1E293B; outline:none; color:#fff; }
.ft-search-icon { position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#4B5563; }
.ft-add-btn { display:inline-flex; align-items:center; gap:6px; padding:10px 18px; background:#3B82F6; color:#fff; border-radius:10px; font-size:13px; font-weight:600; text-decoration:none; white-space:nowrap; }
.ft-add-btn:hover { background:#2563EB; }
[data-theme="light"] .ft-search-input { background:#F8FAFC; border-color:#E2E8F0; color:#0B1628; }
[data-theme="light"] .ft-search-icon { color:#94A3B8; }
</style>
@endpush

@section('content')
<div class="am-header"><h1 class="am-header-title">{{ __('Send Money') }}</h1></div>
<div class="am-body">
    <div class="am-card">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px;">
            <div class="ft-search-wrap">
                <input class="ft-search-input search" type="text" placeholder="{{ __('Search Beneficiary') }}">
                <span class="las la-search ft-search-icon"></span>
            </div>
            <a href="{{ setRoute('user.beneficiary.create') }}" class="ft-add-btn">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> {{ __('Add') }}
            </a>
        </div>
        <div class="items beneficiary-search">
            @include('user.components.beneficiary.items',compact("beneficiaries"))
        </div>
        {{ $beneficiaries->links() }}
    </div>
</div>
@endsection

@push('script')
    <script>

        $(".next-btn").click(function(){
            var selectedItem = $(".dashboard-list-item-wrapper.selected");
            if(selectedItem.length == 0) {
                throwMessage("warning",['Pelase select any beneficiary']);
                return false;
            }
            var target = selectedItem.attr("data-target");
            console.log(target);
            var actionURL = "{{ setRoute('user.fund-transfer.beneficiary.select') }}";
            postFormAndSubmit(actionURL,target);
        });

        $(".dashboard-list-item-wrapper .select-btn").click(function () {
           
            $(".dashboard-list-item-wrapper").removeClass("selected");
            $(".select-btn").text("Select");

            $(this).parents(".dashboard-list-item-wrapper").toggleClass("selected");

            if ($(this).parents(".dashboard-list-item-wrapper").hasClass("selected")) {
                $(this).text("Selected");
            } else {
                $(this).text("Select");
            }
        });

        itemSearch($(".search"),$(".beneficiary-search"),"{{ setRoute('user.beneficiary.search', $type) }}",2);
    </script>
@endpush

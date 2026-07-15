@if ($basic_settings->kyc_verification == true && isset($user_kyc) && $user_kyc != null && $user_kyc->fields != null)
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
        <div class="am-card-title" style="margin-bottom:0;">{{ __('KYC Information') }}</div>
        <span class="kyc-badge kyc-badge-{{ strtolower(auth()->user()->kycStringStatus->value) }}">{{ __(auth()->user()->kycStringStatus->value) }}</span>
    </div>

    @if (auth()->user()->kyc_verified == global_const()::PENDING)
        <div class="kyc-message kyc-message-pending">{{ __('Your KYC information is submitted. Please wait for admin confirmation. When you are KYC verified you will show your submitted information here.') }}</div>

    @elseif (auth()->user()->kyc_verified == global_const()::APPROVED)
        <div class="kyc-message kyc-message-approved">{{ __('Your KYC information is verified') }}</div>
        @if (count(auth()->user()->kyc->data ?? []) > 0)
            @foreach (auth()->user()->kyc->data ?? [] as $item)
                <div class="kyc-data-row">
                    <span class="kyc-data-label">{{ $item->label }}:</span>
                    @if ($item->type == "file")
                        @php $file_link = get_file_link("kyc-files",$item->value); @endphp
                        @if (its_image($item->value))
                            <img src="{{ $file_link }}" alt="{{ $item->label }}" class="kyc-data-image">
                        @else
                            @php $file_info = get_file_basename_ext_from_link($file_link); @endphp
                            <a href="{{ setRoute('file.download',["kyc-files",$item->value]) }}" class="kyc-data-link">{{ Str::substr($file_info->base_name ?? "", 0 , 20) . "..." . ($file_info->extension ?? "") }}</a>
                        @endif
                    @else
                        <span class="kyc-data-value">{{ $item->value }}</span>
                    @endif
                </div>
            @endforeach
        @endif

    @elseif (auth()->user()->kyc_verified == global_const()::REJECTED)
        <div class="kyc-message kyc-message-rejected">{{ __('Your KYC information is rejected.') }}</div>
        <div class="kyc-reject-reason">
            <strong>{{ __('Reject Reason') }}</strong>
            <p>{{ auth()->user()->kyc->reject_reason ?? '' }}</p>
        </div>
        <form action="{{ setRoute('user.kyc.submit') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @foreach ($kyc_fields as $item)
                @if ($item->type == "select")
                    <div class="ps-field">
                        <label class="ps-label">{{ $item->label }} {{ $item->required ? '*' : '' }}</label>
                        <select class="ps-select" name="{{ $item->name }}" {{ $item->required ? 'required' : '' }}>
                            <option selected disabled>{{ __('Choose One') }}</option>
                            @foreach ($item->validation->options as $innerItem)
                                <option value="{{ $innerItem }}">{{ $innerItem }}</option>
                            @endforeach
                        </select>
                        @error($item->name) <span class="kyc-error">{{ $message }}</span> @enderror
                    </div>
                @elseif ($item->type == "file")
                    <div class="ps-field">
                        <label class="ps-label">{{ $item->label }} {{ $item->required ? '*' : '' }}</label>
                        <input class="ps-input" type="file" name="{{ $item->name }}" {{ $item->required ? 'required' : '' }}>
                        @error($item->name) <span class="kyc-error">{{ $message }}</span> @enderror
                    </div>
                @elseif ($item->type == "textarea")
                    <div class="ps-field">
                        <label class="ps-label">{{ $item->label }} {{ $item->required ? '*' : '' }}</label>
                        <textarea class="ps-input" name="{{ $item->name }}" rows="3" {{ $item->required ? 'required' : '' }}>{{ old($item->name) }}</textarea>
                        @error($item->name) <span class="kyc-error">{{ $message }}</span> @enderror
                    </div>
                @else
                    <div class="ps-field">
                        <label class="ps-label">{{ $item->label }} {{ $item->required ? '*' : '' }}</label>
                        <input class="ps-input" type="text" name="{{ $item->name }}" value="{{ old($item->name) }}" {{ $item->required ? 'required' : '' }}>
                        @error($item->name) <span class="kyc-error">{{ $message }}</span> @enderror
                    </div>
                @endif
            @endforeach
            <button type="submit" class="ps-btn-blue" style="margin-top:4px;">{{ __('Submit') }}</button>
        </form>

    @else
        <p class="kyc-message">{{ __('Please submit your KYC information with valid data.') }}</p>
        <form action="{{ setRoute('user.kyc.submit') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @foreach ($kyc_fields as $item)
                @if ($item->type == "select")
                    <div class="ps-field">
                        <label class="ps-label">{{ $item->label }} {{ $item->required ? '*' : '' }}</label>
                        <select class="ps-select" name="{{ $item->name }}" {{ $item->required ? 'required' : '' }}>
                            <option selected disabled>{{ __('Choose One') }}</option>
                            @foreach ($item->validation->options as $innerItem)
                                <option value="{{ $innerItem }}">{{ $innerItem }}</option>
                            @endforeach
                        </select>
                        @error($item->name) <span class="kyc-error">{{ $message }}</span> @enderror
                    </div>
                @elseif ($item->type == "file")
                    <div class="ps-field">
                        <label class="ps-label">{{ $item->label }} {{ $item->required ? '*' : '' }}</label>
                        <input class="ps-input" type="file" name="{{ $item->name }}" {{ $item->required ? 'required' : '' }}>
                        @error($item->name) <span class="kyc-error">{{ $message }}</span> @enderror
                    </div>
                @elseif ($item->type == "textarea")
                    <div class="ps-field">
                        <label class="ps-label">{{ $item->label }} {{ $item->required ? '*' : '' }}</label>
                        <textarea class="ps-input" name="{{ $item->name }}" rows="3" {{ $item->required ? 'required' : '' }}>{{ old($item->name) }}</textarea>
                        @error($item->name) <span class="kyc-error">{{ $message }}</span> @enderror
                    </div>
                @else
                    <div class="ps-field">
                        <label class="ps-label">{{ $item->label }} {{ $item->required ? '*' : '' }}</label>
                        <input class="ps-input" type="text" name="{{ $item->name }}" value="{{ old($item->name) }}" {{ $item->required ? 'required' : '' }}>
                        @error($item->name) <span class="kyc-error">{{ $message }}</span> @enderror
                    </div>
                @endif
            @endforeach
            <div class="ps-field">
                <a href="{{ setRoute('user.dashboard') }}" class="kyc-back-link">{{ __('Back To Dashboard') }}</a>
            </div>
            <button type="submit" class="ps-btn-blue" style="margin-top:4px;">{{ __('Submit') }}</button>
        </form>
    @endif
@endif

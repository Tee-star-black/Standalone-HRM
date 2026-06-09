<div class="signature-block">
    @if($company->ceo_signature_path)
        <img src="{{ public_path('storage/' . $company->ceo_signature_path) }}"
             class="signature-image">
    @endif

    <div class="signature-line">
        <strong>{{ $company->ceo_name }}</strong><br>
        {{ $company->ceo_title }}<br>
        {{ $company->company_name }}
    </div>
</div>
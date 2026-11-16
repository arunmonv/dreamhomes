{{-- Shared enquiry form: property pages + popup. Flash/errors are shown by the layout toast. --}}
<form method="post" action="{{ route('enquire') }}" class="enq">
    @csrf
    <input type="hidden" name="property_id" value="{{ $property?->id }}">
    {{-- honeypot field, hidden from humans --}}
    <input name="website" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">
    <input name="name" placeholder="Your name *" value="{{ old('name') }}" required maxlength="100" autocomplete="name">
    <input name="phone" placeholder="Phone number *" value="{{ old('phone') }}" required inputmode="tel" autocomplete="tel">
    <input name="email" type="email" placeholder="Email (optional)" value="{{ old('email') }}" maxlength="150" autocomplete="email">
    <input name="city" placeholder="City *" value="{{ old('city') }}" required maxlength="100" autocomplete="address-level2">
    <input name="message" class="full" placeholder="Your requirements (optional)" value="{{ old('message') }}" maxlength="1000">
    <button>{{ $property ? 'Send Enquiry' : 'Request a Free Estimate' }} &rarr;</button>
</form>

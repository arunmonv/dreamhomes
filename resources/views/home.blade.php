@extends('layouts.site')

@section('title', ($s['site_name'] ?? 'Dream Homes') . ' | Land, New & Resale Houses and Home Construction in Tamil Nadu & Kerala')

{{-- Structured data: local business / real estate agent --}}
@section('schema')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'RealEstateAgent',
    'name' => $s['site_name'] ?? 'Dream Homes',
    'description' => $s['hero_text'] ?? '',
    'url' => url('/'),
    'telephone' => $s['phone'] ?? null,
    'image' => !empty($s['hero_image']) ? asset('storage/'.$s['hero_image']) : null,
    'areaServed' => ['Tamil Nadu', 'Kerala'],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endsection

@section('content')
{{-- Hero --}}
@if(empty($s['hero_image']))
{{-- Default hero: the supplied banners (headline + search bar are part of the picture).
     A real, working search form sits exactly over the drawn search bar, and the H1 is
     kept as hidden text so search engines and screen readers still get it. --}}
<section class="hero-img">
    <h1 class="sr">{{ $s['hero_title'] ?? '' }} {{ $s['hero_highlight'] ?? '' }} - {{ $s['hero_text'] ?? '' }}</h1>
    <img class="hd" src="{{ asset('img/01_desktop_hero_banner.jpg') }}" width="1163" height="393" alt="{{ $s['site_name'] ?? '' }}: {{ $s['hero_text'] ?? '' }}" fetchpriority="high">
    <img class="hm" src="{{ asset('img/02_mobile_hero_banner.jpg') }}" width="341" height="600" alt="{{ $s['site_name'] ?? '' }}: {{ $s['hero_text'] ?? '' }}" fetchpriority="high">
    <form action="{{ route('properties') }}" role="search">
        <input name="q" placeholder=" " aria-label="Search by location or property type" autocomplete="off">
        <button aria-label="Search"></button>
    </form>
</section>
@else
{{-- Admin-uploaded hero: live text + search drawn by the site --}}
<section class="hero" style="background-image:linear-gradient(90deg,rgba(0,20,10,.65),rgba(0,0,0,.1)),url('{{ asset('storage/'.$s['hero_image']) }}')">
    <div class="wrap">
        <h1>{{ $s['hero_title'] ?? '' }}<br><em>{{ $s['hero_highlight'] ?? '' }}</em></h1>
        <p>{{ $s['hero_text'] ?? '' }}</p>
        <form action="{{ route('properties') }}" class="search">
            <input name="q" placeholder="Search by location, property type..." aria-label="Search">
            <button>Search</button>
        </form>
    </div>
</section>
@endif

{{-- Quick category tiles --}}
<section class="wrap">
  <div class="tiles2">
    <a href="#enquiry"><img src="{{ asset('img/03_icon_house_construction.jpg') }}" width="288" height="144" alt="House Construction - build your dream home"></a>
    <a href="{{ route('properties.type','land') }}"><img src="{{ asset('img/04_icon_land_for_sale.jpg') }}" width="272" height="144" alt="Land for Sale - prime plots and lands"></a>
    <a href="{{ route('properties.type','new_house') }}"><img src="{{ asset('img/05_icon_new_houses.jpg') }}" width="272" height="144" alt="New Houses - modern homes"></a>
    <a href="{{ route('properties.type','resale') }}"><img src="{{ asset('img/06_icon_resale_houses.jpg') }}" width="278" height="144" alt="Resale Houses - quality homes"></a>
  </div>
</section>

{{-- Featured row (mixed types, chosen in admin) --}}
<section class="wrap sec">
    <div class="sec-h"><h2>Featured Properties</h2><a href="{{ route('properties') }}">See More &rarr;</a></div>
    <div class="grid">@foreach($featured as $p) @include('partials.card') @endforeach</div>
</section>

{{-- One section per property type --}}
@foreach(\App\Models\Property::TYPES as $type => $label)
    @if($sections[$type]->isNotEmpty())
    <section class="wrap sec">
        <div class="sec-h">
            <h2>{{ $label }}</h2>
            <a href="{{ route('properties.type', $type) }}">See More &rarr;</a>
        </div>
        <div class="grid">@foreach($sections[$type] as $p) @include('partials.card') @endforeach</div>
    </section>
    @endif
@endforeach

{{-- Main feature: animated banner; the whole thing opens the enquiry popup (see layouts/site) --}}
<section id="estimate" class="wrap sec">
    @if(empty($s['cta_image']))
    <a class="cta2" href="#enquiry">
        <svg class="hat" viewBox="0 0 64 48" aria-hidden="true"><path d="M6 36c0-14 12-24 26-24s26 10 26 24z" fill="#f5b400"/><rect x="2" y="36" width="60" height="6" rx="3" fill="#e0a000"/><rect x="28" y="12" width="8" height="24" fill="#e0a000"/></svg>
    @else
    <a class="cta2 has-img" href="#enquiry" style="background-image:linear-gradient(90deg,#0f2f73 50%,rgba(15,47,115,.15)),url('{{ asset('storage/'.$s['cta_image']) }}')">
    @endif
        <span class="cta2-t"><b class="h">{{ $s['cta_title'] ?? '' }}</b><span>{{ $s['cta_text'] ?? '' }}</span></span>
        <span class="btn navy pulse">Request a Free Estimate &rarr;</span>
    </a>
    @if(!empty($s['phone']))
    <p class="cta-call">Prefer to talk? <a href="tel:{{ preg_replace('/[^\d+]/', '', $s['phone']) }}">&#128222; Call {{ $s['phone'] }}</a></p>
    @endif
</section>
@endsection

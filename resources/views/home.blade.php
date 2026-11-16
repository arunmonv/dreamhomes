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
{{-- Hero: full-width photo, centred text, one main button. Default photo = right half of the supplied banner
     (no baked-in text); upload a larger photo in Admin > Site Settings for best quality. --}}
@php $heroBg = !empty($s['hero_image']) ? asset('storage/'.$s['hero_image']) : asset('img/01_desktop_hero_banner.jpg'); @endphp
<section class="hero3 {{ empty($s['hero_image']) ? 'dflt' : '' }}" style="background-image:url('{{ $heroBg }}')">
    @if(!empty($s['hero_video']))
    <video class="hero-vid" autoplay muted loop playsinline preload="auto" aria-hidden="true"><source src="{{ asset('storage/'.$s['hero_video']) }}" type="{{ str_ends_with($s['hero_video'], '.webm') ? 'video/webm' : 'video/mp4' }}"></video>
    @endif
    <div class="wrap">
        <h1>{{ $s['hero_title'] ?? '' }} <em>{{ $s['hero_highlight'] ?? '' }}</em></h1>
        <p>{{ $s['hero_text'] ?? '' }}</p>
        <a class="btn hero-btn" href="{{ route('properties') }}">View Properties</a>
        <form action="{{ route('properties') }}" class="search" role="search">
            <input name="q" placeholder="Search by location, property type..." aria-label="Search">
            <button>Search</button>
        </form>
    </div>
</section>

{{-- Quick category tiles --}}
<section class="wrap">
  <div class="tiles2">
    <a href="#enquiry"><img src="{{ asset('img/03_icon_house_construction.jpg') }}" width="288" height="144" alt="House Construction - build your dream home"></a>
    <a href="{{ route('properties.type','new_house') }}"><img src="{{ asset('img/05_icon_new_houses.jpg') }}" width="272" height="144" alt="New Houses - modern homes"></a>
    <a href="{{ route('properties.type','resale') }}"><img src="{{ asset('img/06_icon_resale_houses.jpg') }}" width="278" height="144" alt="Resale Houses - quality homes"></a>
    {{-- No supplied image for this service, so it is drawn in the same card style --}}
    <a href="#enquiry" class="tile-alt" aria-label="Old House Alteration - renovate and remodel">
        <svg viewBox="0 0 48 48" fill="none" stroke="#1d4ed8" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 24 24 6l20 18M9 20v22h30V20"/><rect x="19" y="28" width="10" height="14"/><path d="M33 8l7 7-9 9-7-7z" fill="#f5b400" stroke="#b07d00"/></svg>
        <b>Old House Alteration</b><small>Renovate &amp; remodel</small>
    </a>
    <a href="{{ route('properties.type','land') }}"><img src="{{ asset('img/04_icon_land_for_sale.jpg') }}" width="272" height="144" alt="Land for Sale - prime plots and lands"></a>
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

{{-- About + stats (hidden until filled in Admin > Site Settings) --}}
@php
    $stats = array_filter([
        [$s['stat1_num'] ?? '', $s['stat1_label'] ?? ''],
        [$s['stat2_num'] ?? '', $s['stat2_label'] ?? ''],
        [$s['stat3_num'] ?? '', $s['stat3_label'] ?? ''],
    ], fn ($x) => $x[0] !== '');
@endphp
@if(!empty($s['about_text']))
<section class="about">
    <div class="wrap about-in">
        @if(!empty($s['about_image']))<img src="{{ asset('storage/'.$s['about_image']) }}" alt="{{ $s['site_name'] ?? '' }}" width="380" height="440" loading="lazy">@endif
        <div>
            <h2>About {{ $s['site_name'] ?? '' }}</h2>
            <p class="sub">{{ $s['tagline'] ?? '' }}</p>
            <p>{{ \Illuminate\Support\Str::limit($s['about_text'], 420) }}</p>
            @if(count($stats))
            <div class="stats">@foreach($stats as [$num, $label])<div><b>{{ $num }}</b><span>{{ $label }}</span></div>@endforeach</div>
            @endif
            <a class="btn navy" href="{{ route('about') }}">Learn more</a>
        </div>
    </div>
</section>
@endif
@endsection

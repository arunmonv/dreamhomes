{{-- $s (site settings from admin) and $wa are supplied by AppServiceProvider --}}
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        // SEO defaults; each page overrides via @section('title'|'desc'|'robots'|'og_image'|'og_type')
        $seoTitle = html_entity_decode(trim($__env->yieldContent('title'))) ?: (($s['site_name'] ?? 'Dream Homes') . ' | ' . ($s['tagline'] ?? 'Real Estate & Builders'));
        $seoDesc  = html_entity_decode(trim($__env->yieldContent('desc'))) ?: ($s['hero_text'] ?? '');
        $seoImg   = trim($__env->yieldContent('og_image')) ?: (!empty($s['hero_image']) ? asset('storage/'.$s['hero_image']) : (!empty($s['logo']) ? asset('storage/'.$s['logo']) : ''));
        $seoUrl   = url()->current(); // no query string = clean canonical
    @endphp
    <title>{{ $seoTitle }}</title>
    <meta name="description" content="{{ $seoDesc }}">
    <meta name="robots" content="@yield('robots', 'index,follow,max-image-preview:large')">
    <link rel="canonical" href="{{ $seoUrl }}">
    @yield('head_links')
    <meta property="og:site_name" content="{{ $s['site_name'] ?? '' }}">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDesc }}">
    <meta property="og:url" content="{{ $seoUrl }}">
    @if($seoImg)<meta property="og:image" content="{{ $seoImg }}">@endif
    <meta name="twitter:card" content="summary_large_image">
    <meta name="theme-color" content="#0f5a2a">
    <link rel="icon" href="{{ !empty($s['logo']) ? asset('storage/'.$s['logo']) : asset('img/logo.svg') }}">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
    @yield('schema')
</head>
<body>
<header class="hdr">
    <div class="wrap hdr-in">
        <a href="{{ route('home') }}" class="brand">
            <img src="{{ !empty($s['logo']) ? asset('storage/'.$s['logo']) : asset('img/logo.svg') }}" alt="{{ $s['site_name'] ?? '' }} logo" width="52" height="44">
            <span><b>{{ $s['site_name'] ?? 'Dream Homes' }}</b><small>{{ $s['tagline'] ?? '' }}</small></span>
        </a>
        {{-- CSS-only mobile menu: no JavaScript needed --}}
        <input type="checkbox" id="mn" hidden>
        <label for="mn" class="burger" aria-label="Menu"><i></i><i></i><i></i></label>
        <nav class="nav">
            <a href="{{ route('home') }}" @class(['on' => request()->routeIs('home')])>Home</a>
            <a href="{{ route('about') }}" @class(['on' => request()->routeIs('about')])>About Us</a>
            <div class="dd">
                <a href="{{ route('home') }}#enquiry">Services &#9662;</a>
                <div class="dd-m">
                    <a href="{{ route('home') }}#enquiry">House Construction</a>
                    <a href="{{ route('home') }}#enquiry">Old House Alteration</a>
                    <a href="{{ route('properties.type', 'land') }}">Land for Sale</a>
                    <a href="{{ route('properties.type', 'new_house') }}">New Houses</a>
                    <a href="{{ route('properties.type', 'resale') }}">Resale Houses</a>
                </div>
            </div>
            <div class="dd">
                <a href="{{ route('properties') }}" @class(['on' => request()->routeIs('properties*')])>Properties &#9662;</a>
                <div class="dd-m">
                    <a href="{{ route('properties') }}">All Properties</a>
                    <a href="{{ route('properties.type', 'land') }}">Land</a>
                    <a href="{{ route('properties.type', 'new_house') }}">New Houses</a>
                    <a href="{{ route('properties.type', 'resale') }}">Resale</a>
                </div>
            </div>
            <a href="{{ route('properties.type', 'new_house') }}">Our Projects</a>
            <a href="#contact">Contact</a>
            @php $ph = $s['phone'] ?? ''; $phTel = preg_replace('/[^\d+]/', '', $ph); @endphp
            @if($ph)
            <div class="callbox">
                <a class="callnum" href="tel:{{ $phTel }}"><small>CALL US TODAY</small><b>{{ $ph }}</b></a>
                @if($wa)<a class="wa" href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener" aria-label="WhatsApp"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.6 15L2 22l5.1-1.3A10 10 0 1 0 12 2zm5.3 14.2c-.2.6-1.3 1.2-1.8 1.2-.5.1-1 .2-3.2-.7-2.7-1.1-4.4-3.8-4.6-4-.1-.2-1.1-1.4-1.1-2.7s.7-1.9.9-2.2c.2-.2.5-.3.7-.3h.5c.2 0 .4 0 .6.5l.8 2c.1.2.1.3 0 .5l-.3.4-.4.4c-.1.2-.3.3-.1.6.2.3.7 1.2 1.5 1.9 1 .9 1.9 1.2 2.2 1.3.3.1.4.1.6-.1l.8-1c.2-.2.3-.2.6-.1l1.9.9c.3.1.5.2.5.3.1.2.1.7-.1 1.4z"/></svg></a>@endif
            </div>
            @endif
        </nav>
    </div>
</header>

{{-- Flash / error toast (pure CSS fade-out, no JS) --}}
@if(session('ok'))<div class="toast ok-t" role="status">{{ session('ok') }}</div>@endif
@if($errors->any())<div class="toast er-t" role="alert">{{ $errors->first() }}</div>@endif

<main>@yield('content')</main>

<footer id="contact" class="ftr">
    <div class="wrap">
        <div class="f-contact">
            @if(!empty($s['address']))<span>&#128205; {{ $s['address'] }}</span>@endif
            @if(!empty($s['phone']))<a href="tel:{{ preg_replace('/[^\d+]/', '', $s['phone']) }}">&#128222; {{ $s['phone'] }}</a>@endif
            @if(!empty($s['email']))<a href="mailto:{{ $s['email'] }}">&#9993; {{ $s['email'] }}</a>@endif
        </div>
        <div class="f-main">
            <div>
                <h3>{{ $s['site_name'] ?? '' }}</h3>
                <p>{{ $s['tagline'] ?? '' }}. {{ $s['hero_text'] ?? '' }}</p>
            </div>
            <div>
                <h3>Explore</h3>
                <a href="{{ route('properties.type', 'land') }}">Land for Sale</a>
                <a href="{{ route('properties.type', 'new_house') }}">New Houses</a>
                <a href="{{ route('properties.type', 'resale') }}">Resale Houses</a>
                <a href="{{ route('about') }}">About Us</a>
            </div>
<div class="f-form">                <h3>Get in touch</h3>                <p>Leave your details and we will call you back.</p>                @include('partials.enquiry', ['property' => null])            </div>
            <div class="trust">
                <h3>Why choose us</h3>
                <span>{{ $s['trust_1'] ?? '' }}</span><span>{{ $s['trust_2'] ?? '' }}</span><span>{{ $s['trust_3'] ?? '' }}</span>
            </div>
        </div>
        <p class="copy">&copy; {{ date('Y') }} {{ $s['site_name'] ?? '' }}. All rights reserved.</p>
    </div>
</footer>

@php $tel = preg_replace('/[^\d+]/', '', $s['phone'] ?? ''); @endphp
{{-- Floating call-to-action: opens the popup from any page --}}
<a class="fab" href="#enquiry"><span class="ph" aria-hidden="true">&#128222;</span> Free Estimate</a>

{{-- Enquiry popup: CSS :target modal, so no JavaScript is needed (works with the strict CSP) --}}
<div id="enquiry" class="modal" role="dialog" aria-modal="true" aria-labelledby="enq-t">
    <a class="bd" href="#!" aria-label="Close"></a>
    <div class="mbox">
        <a class="x" href="#!" aria-label="Close">&times;</a>
        <h2 id="enq-t">{{ $s['cta_title'] ?? 'Request a Free Estimate' }}</h2>
        <p class="muted">Share your details and we will call you back.</p>
        <div class="quick">
            @if($tel)<a class="q-call" href="tel:{{ $tel }}">&#128222; Call Now</a>@endif
            @if($wa)<a class="q-wa" href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener">WhatsApp</a>@endif
        </div>
        <div class="or"><span>or leave your details</span></div>
        @include('partials.enquiry', ['property' => null])
    </div>
</div>
</body>
</html>

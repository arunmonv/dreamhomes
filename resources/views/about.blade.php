@extends('layouts.site')
@section('title', 'About Us | ' . ($s['site_name'] ?? ''))
@section('desc', \Illuminate\Support\Str::limit(strip_tags($s['about_text'] ?? ($s['hero_text'] ?? '')), 155))

@section('content')
<section class="wrap sec" style="max-width:820px">
    <h1>About {{ $s['site_name'] ?? '' }}</h1>
    <p class="muted">{{ $s['tagline'] ?? '' }}</p>
    <div style="margin-top:14px">{!! nl2br(e($s['about_text'] ?? $s['hero_text'] ?? '')) !!}</div>
    <p style="margin-top:18px"><a class="btn" href="{{ route('home') }}#estimate">Request a Free Estimate</a></p>
</section>
@endsection

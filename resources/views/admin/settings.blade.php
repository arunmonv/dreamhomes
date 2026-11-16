@extends('layouts.admin')
@section('title', 'Site Settings')
@section('content')
@php
    $text = [
        'site_name' => 'Site name', 'tagline' => 'Tagline', 'whatsapp' => 'WhatsApp number (with country code, e.g. 919876543210)', 'phone' => 'Phone (shown in footer)',
        'hero_title' => 'Hero title (white)', 'hero_highlight' => 'Hero title (yellow)', 'hero_text' => 'Hero sub text',
        'cta_title' => 'Construction banner title', 'cta_text' => 'Construction banner text',
        'trust_1' => 'Trust point 1', 'trust_2' => 'Trust point 2', 'trust_3' => 'Trust point 3', 'about_text' => 'About Us page text',
    ];
    $imgs = ['logo' => 'Logo', 'hero_image' => 'Hero background image', 'cta_image' => 'Construction banner image'];
@endphp
<form class="box" method="post" enctype="multipart/form-data" action="{{ route('admin.settings.save') }}">
    @csrf
    <h2>Site Settings</h2>
    <div class="row">
    @foreach($text as $k => $label)
        <div><label>{{ $label }}</label><input name="{{ $k }}" value="{{ old($k, $s[$k] ?? '') }}"></div>
    @endforeach
    </div>
    @foreach($imgs as $k => $label)
        <label>{{ $label }}</label>
        @if(!empty($s[$k]))<img class="th" src="{{ asset('storage/'.$s[$k]) }}" alt="">@endif
        <input type="file" name="{{ $k }}" accept="image/*">
    @endforeach
    <p><button class="btn">Save settings</button></p>
</form>
@endsection

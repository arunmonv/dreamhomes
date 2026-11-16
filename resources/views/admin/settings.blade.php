@extends('layouts.admin')
@section('title', 'Site Settings')
@section('content')
@php
    $text = [
        'site_name' => 'Site name', 'tagline' => 'Tagline', 'whatsapp' => 'WhatsApp number (with country code, e.g. 919876543210)', 'phone' => 'Phone (shown in footer)',
        'hero_title' => 'Hero title (white)', 'hero_highlight' => 'Hero title (yellow)', 'hero_text' => 'Hero sub text',
        'cta_title' => 'Construction banner title', 'cta_text' => 'Construction banner text',
        'trust_1' => 'Trust point 1', 'trust_2' => 'Trust point 2', 'trust_3' => 'Trust point 3', 'about_text' => 'About text (shows on home + About page)', 'address' => 'Office address (footer)', 'email' => 'Email (footer)',
        'stat1_num' => 'Stat 1 number (e.g. 10+)', 'stat1_label' => 'Stat 1 label (e.g. Years of Experience)', 'stat2_num' => 'Stat 2 number', 'stat2_label' => 'Stat 2 label', 'stat3_num' => 'Stat 3 number', 'stat3_label' => 'Stat 3 label',
    ];
    $imgs = ['logo' => 'Logo', 'hero_image' => 'Hero background image', 'cta_image' => 'Construction banner image', 'about_image' => 'About section photo (owner / team)'];
@endphp
<form class="box" method="post" enctype="multipart/form-data" action="{{ route('admin.settings.save') }}">
    @csrf
    <h2>Site Settings</h2>
    <div class="row">
    @foreach($text as $k => $label)
        <div @if($k === 'about_text') style="grid-column:1/-1" @endif><label>{{ $label }}</label>@if($k === 'about_text')<textarea name="about_text" rows="5">{{ old('about_text', $s['about_text'] ?? '') }}</textarea>@else<input name="{{ $k }}" value="{{ old($k, $s[$k] ?? '') }}">@endif</div>
    @endforeach
    </div>
    @foreach($imgs as $k => $label)
        <label>{{ $label }}</label>
        @if(!empty($s[$k]))<img class="th" src="{{ asset('storage/'.$s[$k]) }}" alt="">@endif
        <input type="file" name="{{ $k }}" accept="image/*">
    @endforeach
    <label>Hero background video (optional, MP4 or WebM, under 10 MB, about 10 seconds, 1280px wide; plays muted on a loop)</label>
    @if(!empty($s['hero_video']))<video src="{{ asset('storage/'.$s['hero_video']) }}" muted style="width:180px;border-radius:6px" controls></video><label style="font-weight:400"><input type="checkbox" name="remove_hero_video" value="1"> Remove current video</label>@endif
    <input type="file" name="hero_video" accept="video/mp4,video/webm">
    <p><button class="btn">Save settings</button></p>
</form>
@endsection

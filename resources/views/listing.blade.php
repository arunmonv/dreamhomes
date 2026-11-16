@extends('layouts.site')
@section('title', $heading . ' in Tamil Nadu & Kerala' . ($items->currentPage() > 1 ? ' - Page ' . $items->currentPage() : '') . ' | ' . ($s['site_name'] ?? ''))
@section('desc', 'Browse ' . strtolower($heading) . ' with prices, locations and photos from ' . ($s['site_name'] ?? '') . '.')
{{-- Internal search results are thin/duplicate content: keep them out of the index --}}
@if($q !== '')
    @section('robots', 'noindex,follow')
@endif
@section('head_links')
    @if($items->previousPageUrl())<link rel="prev" href="{{ $items->previousPageUrl() }}">@endif
    @if($items->nextPageUrl())<link rel="next" href="{{ $items->nextPageUrl() }}">@endif
@endsection

@section('content')
<section class="wrap sec">
    <div class="sec-h"><h2>{{ $heading }}</h2></div>
    <form class="search small" action="{{ url()->current() }}">
        <input name="q" value="{{ $q }}" placeholder="Search by location or name" aria-label="Search">
        <button>Search</button>
    </form>

    @if($items->isEmpty())
        <p class="muted">No properties found.</p>
    @else
        <div class="grid">@foreach($items as $p) @include('partials.card') @endforeach</div>
        <div class="pager">
            @if($items->previousPageUrl())<a href="{{ $items->previousPageUrl() }}">&larr; Previous</a>@endif
            @if($items->nextPageUrl())<a href="{{ $items->nextPageUrl() }}">Next &rarr;</a>@endif
        </div>
    @endif
</section>
@endsection

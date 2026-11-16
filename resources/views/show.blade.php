@extends('layouts.site')
@section('title', $property->title . ' in ' . $property->location . ' - ' . $property->price_formatted . ' | ' . ($s['site_name'] ?? ''))
@section('desc', \Illuminate\Support\Str::limit($property->description ?: ($property->title . ' for sale in ' . $property->location . ' at ' . $property->price_formatted), 155))
@section('og_image', $property->image_url)

{{-- Structured data for the listing + breadcrumb --}}
@section('schema')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@graph' => [[
        '@type' => 'RealEstateListing',
        'name' => $property->title,
        'description' => $property->description ?: $property->title,
        'url' => url()->current(),
        'image' => $property->image_url,
        'datePosted' => $property->created_at->toDateString(),
        'contentLocation' => ['@type' => 'Place', 'name' => $property->location],
        'offers' => ['@type' => 'Offer', 'price' => $property->price, 'priceCurrency' => 'INR', 'availability' => 'https://schema.org/InStock'],
    ], [
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => \App\Models\Property::TYPES[$property->type], 'item' => route('properties.type', $property->type)],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $property->title],
        ],
    ]],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endsection

@section('content')
<section class="wrap sec detail">
    <img src="{{ $property->image_url }}" alt="{{ $property->title }}" width="800" height="520">
    <div>
        <h1>{{ $property->title }}</h1>
        <p class="muted">&#128205; {{ $property->location }}
            @if($property->area_sqft) &middot; {{ number_format($property->area_sqft) }} Sq.Ft @endif
            @if($property->bedrooms) &middot; {{ $property->bedrooms }} BHK @endif</p>
        <strong class="price big">{{ $property->price_formatted }}</strong>
        @if($property->description)<p>{!! nl2br(e($property->description)) !!}</p>@endif

        @if($wa)
        <a class="btn" target="_blank" rel="noopener"
           href="https://wa.me/{{ $wa }}?text={{ rawurlencode('Hi, I am interested in: '.$property->title.' ('.$property->location.')') }}">Chat on WhatsApp</a>
        @endif
        <h3>Send an enquiry</h3>
        @include('partials.enquiry', ['property' => $property])
    </div>
</section>
@endsection

{{-- Property card: type badge, title, location, area, price; native lazy loading keeps first paint light --}}
@php $badge = ['land' => 'Land', 'new_house' => 'New', 'resale' => 'Resale'][$p->type] ?? ''; @endphp
<a class="card" href="{{ route('properties.show', $p) }}">
    <div class="imgw">
        <img src="{{ $p->image_url }}" alt="{{ $p->title }} - {{ $p->location }}" loading="lazy" width="400" height="260">
        <span class="badge b-{{ $p->type }}">{{ $badge }}</span>
    </div>
    <div class="card-b">
        <h3>{{ $p->title }}</h3>
        <div class="loc"><svg width="12" height="14" viewBox="0 0 12 16" fill="#1d4ed8" aria-hidden="true"><path d="M6 0a6 6 0 0 0-6 6c0 4.500 6 10 6 10s6-5.500 6-10a6 6 0 0 0-6-6zm0 8.500A2.500 2.500 0 1 1 6 3.500a2.500 2.500 0 0 1 0 5z"/></svg> {{ $p->location }}</div>
        <div class="cf">
            <span class="muted">@if($p->area_sqft){{ number_format($p->area_sqft) }} Sq.Ft @elseif($p->bedrooms){{ $p->bedrooms }} BHK @endif</span>
            <strong class="price">{{ $p->price_formatted }}</strong>
        </div>
    </div>
</a>

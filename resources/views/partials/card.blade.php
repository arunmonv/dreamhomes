{{-- Property card: photo, title, price + type chip, details row (location / BHK / area) --}}
@php $chip = ['land' => 'Land', 'new_house' => 'New', 'resale' => 'Resale'][$p->type] ?? ''; @endphp
<a class="card" href="{{ route('properties.show', $p) }}">
    <div class="imgw"><img src="{{ $p->image_url }}" alt="{{ $p->title }} - {{ $p->location }}" loading="lazy" width="400" height="260"></div>
    <div class="card-b">
        <h3>{{ $p->title }}</h3>
        <div class="pr"><strong class="price">{{ $p->price_formatted }}</strong><span class="chip c-{{ $p->type }}">{{ $chip }}</span></div>
        <div class="det">
            <span><svg width="13" height="15" viewBox="0 0 12 16" fill="currentColor" aria-hidden="true"><path d="M6 0a6 6 0 0 0-6 6c0 4.500 6 10 6 10s6-5.500 6-10a6 6 0 0 0-6-6zm0 8.500A2.500 2.500 0 1 1 6 3.500a2.500 2.500 0 0 1 0 5z"/></svg> {{ $p->location }}</span>
            @if($p->bedrooms)<span><svg width="15" height="13" viewBox="0 0 20 16" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M1 14V3m0 8h18v3M19 11V8a3 3 0 0 0-3-3H8v6"/></svg> <b>{{ $p->bedrooms }}</b> BHK</span>@endif
            @if($p->area_sqft)<span><svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><rect x="1.500" y="1.500" width="13" height="13"/><path d="M1.500 6h13M6 1.500v13"/></svg> <b>{{ number_format($p->area_sqft) }}</b> sq ft</span>@endif
        </div>
    </div>
</a>

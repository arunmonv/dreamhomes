@extends('layouts.admin')
@section('title', $p->exists ? 'Edit Property' : 'Add Property')
@section('content')
<form class="box" method="post" enctype="multipart/form-data"
      action="{{ $p->exists ? route('admin.properties.update', $p) : route('admin.properties.store') }}">
    @csrf @if($p->exists) @method('PUT') @endif
    <h2>{{ $p->exists ? 'Edit' : 'Add' }} Property</h2>

    <label>Title</label><input name="title" value="{{ old('title', $p->title) }}" required>
    <div class="row">
        <div><label>Type</label>
            <select name="type">
                @foreach(\App\Models\Property::TYPES as $k => $v)
                    <option value="{{ $k }}" @selected(old('type', $p->type) === $k)>{{ $v }}</option>
                @endforeach
            </select></div>
        <div><label>Location</label><input name="location" value="{{ old('location', $p->location) }}" required></div>
        <div><label>Price (₹, digits only)</label><input type="number" name="price" value="{{ old('price', $p->price) }}" required></div>
        <div><label>Area (Sq.Ft)</label><input type="number" name="area_sqft" value="{{ old('area_sqft', $p->area_sqft) }}"></div>
        <div><label>Bedrooms (BHK)</label><input type="number" name="bedrooms" value="{{ old('bedrooms', $p->bedrooms) }}"></div>
        <div><label>Sort order (low = first)</label><input type="number" name="sort" value="{{ old('sort', $p->sort ?? 0) }}"></div>
    </div>
    <label>Description</label><textarea name="description" rows="4">{{ old('description', $p->description) }}</textarea>
    <label>Image (jpg/png/webp, max 2 MB; use ~1200px wide)</label>
    @if($p->image)<img class="th" src="{{ $p->image_url }}" alt="">@endif
    <input type="file" name="image" accept="image/*">
    <label style="font-weight:400"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $p->is_active))> Show on website</label>
    <label style="font-weight:400"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $p->is_featured))> Show in "Featured Properties" on home page</label>
    <p><button class="btn">Save</button> <a href="{{ route('admin.properties.index') }}">Cancel</a></p>
</form>
@endsection

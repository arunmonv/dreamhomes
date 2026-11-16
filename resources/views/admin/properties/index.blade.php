@extends('layouts.admin')
@section('title', 'Properties')
@section('content')
<p><a class="btn" href="{{ route('admin.properties.create') }}">+ Add Property</a></p>
<div class="box">
<table>
    <tr><th></th><th>Title</th><th>Type</th><th>Location</th><th>Price</th><th>Status</th><th></th></tr>
    @forelse($items as $p)
    <tr>
        <td><img class="th" src="{{ $p->image_url }}" alt=""></td>
        <td>{{ $p->title }}</td>
        <td>{{ \App\Models\Property::TYPES[$p->type] }}</td>
        <td>{{ $p->location }}</td>
        <td>{{ $p->price_formatted }}</td>
        <td>{{ $p->is_active ? 'Live' : 'Hidden' }}</td>
        <td style="white-space:nowrap">
            <a class="btn sm" href="{{ route('admin.properties.edit', $p) }}">Edit</a>
            <form method="post" action="{{ route('admin.properties.destroy', $p) }}" style="display:inline" onsubmit="return confirm('Delete this property?')">
                @csrf @method('DELETE')<button class="btn red sm">Delete</button>
            </form>
        </td>
    </tr>
    @empty
    <tr><td colspan="7">No properties yet.</td></tr>
    @endforelse
</table>
{{ $items->links() }}
</div>
@endsection

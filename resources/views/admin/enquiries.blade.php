@extends('layouts.admin')
@section('title', 'Enquiries')
@section('content')
<div class="box">
<table>
    <tr><th>Date</th><th>Name</th><th>Phone</th><th>Email / City</th><th>Message</th><th>Property</th><th></th></tr>
    @forelse($items as $e)
    <tr>
        <td>{{ $e->created_at->format('d M, h:i A') }}</td>
        <td>{{ $e->name }}</td>
        <td><a href="tel:{{ $e->phone }}">{{ $e->phone }}</a></td>
        <td>{{ $e->email }}<br>{{ $e->city }}</td>
        <td>{{ $e->message }}</td>
        <td>{{ $e->property?->title ?? 'Free estimate' }}</td>
        <td><form method="post" action="{{ route('admin.enquiries.destroy', $e) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn red sm">x</button></form></td>
    </tr>
    @empty
    <tr><td colspan="7">No enquiries yet.</td></tr>
    @endforelse
</table>
{{ $items->links() }}
</div>
@endsection

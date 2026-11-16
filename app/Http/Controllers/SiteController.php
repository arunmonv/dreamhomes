<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Models\Property;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    // Home: three rows of 4 latest active listings, one query per type
    public function home()
    {
        // Featured row: admin-ticked listings first, topped up with the newest ones
        $featured = Property::active()->orderByDesc('is_featured')->limit(4)->get();

        $sections = [];
        foreach (Property::TYPES as $type => $label) {
            $sections[$type] = Property::active()->where('type', $type)->limit(4)->get();
        }
        return view('home', compact('sections', 'featured'));
    }

    // About Us page (text is edited in admin → Site Settings)
    public function about()
    {
        return view('about');
    }

    // Listing page, optional ?type= and ?q= (matches title or location)
    public function index(Request $request, ?string $type = null)
    {
        abort_if($type && ! isset(Property::TYPES[$type]), 404);

        $q = trim((string) $request->query('q'));
        $items = Property::active()
            ->when($type, fn ($qb) => $qb->where('type', $type))
            ->when($q !== '', fn ($qb) => $qb->where(fn ($w) => $w
                ->where('title', 'like', "%$q%")->orWhere('location', 'like', "%$q%")))
            ->paginate(12)->withQueryString();

        $heading = $type ? Property::TYPES[$type] : 'All Properties';
        return view('listing', compact('items', 'heading', 'q'));
    }

    public function show(Property $property)
    {
        abort_unless($property->is_active, 404);
        return view('show', compact('property'));
    }

    // Public enquiry / free estimate form
    public function enquire(Request $request)
    {
        // Honeypot: bots fill the hidden "website" field
        if ($request->filled('website')) {
            return back();
        }
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'phone'       => ['required', 'regex:/^[0-9+\-\s]{8,15}$/'],
            'email'       => 'nullable|email|max:150',
            'city'        => 'required|string|max:100',
            'message'     => 'nullable|string|max:1000',
            'property_id' => 'nullable|exists:properties,id',
        ]);
        Enquiry::create($data);
        return back()->with('ok', 'Thank you! We will call you shortly.');
    }
}

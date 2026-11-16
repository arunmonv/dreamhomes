<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\Property;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /* ---------- Auth ---------- */
    public function loginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $cred = $request->validate(['email' => 'required|email', 'password' => 'required']);
        if (! Auth::attempt($cred, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Invalid email or password.'])->onlyInput('email');
        }
        $request->session()->regenerate();
        return redirect()->route('admin.properties.index');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    /* ---------- Properties CRUD ---------- */
    public function propertiesIndex()
    {
        return view('admin.properties.index', ['items' => Property::latest('id')->paginate(20)]);
    }

    public function propertyCreate()
    {
        return view('admin.properties.form', ['p' => new Property(['is_active' => true])]);
    }

    public function propertyStore(Request $request)
    {
        $p = Property::create($this->propertyData($request));
        return redirect()->route('admin.properties.index')->with('ok', "Saved: {$p->title}");
    }

    public function propertyEdit(Property $property)
    {
        return view('admin.properties.form', ['p' => $property]);
    }

    public function propertyUpdate(Request $request, Property $property)
    {
        $property->update($this->propertyData($request, $property));
        return redirect()->route('admin.properties.index')->with('ok', 'Updated.');
    }

    public function propertyDestroy(Property $property)
    {
        if ($property->image) {
            Storage::disk('public')->delete($property->image);
        }
        $property->delete();
        return back()->with('ok', 'Deleted.');
    }

    // Validate + store image (replaces old one on edit)
    private function propertyData(Request $request, ?Property $existing = null): array
    {
        $data = $request->validate([
            'title'     => 'required|string|max:150',
            'type'      => 'required|in:' . implode(',', array_keys(Property::TYPES)),
            'location'  => 'required|string|max:100',
            'price'     => 'required|integer|min:0',
            'area_sqft' => 'nullable|integer|min:0',
            'bedrooms'  => 'nullable|integer|min:0|max:20',
            'description' => 'nullable|string|max:3000',
            'sort'      => 'nullable|integer|min:0',
            'image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['sort'] = $data['sort'] ?? 0;

        if ($request->hasFile('image')) {
            if ($existing?->image) {
                Storage::disk('public')->delete($existing->image);
            }
            $data['image'] = $request->file('image')->store('properties', 'public');
        } else {
            unset($data['image']);
        }
        return $data;
    }

    /* ---------- Site settings (hero, contact, CTA banner, trust bar) ---------- */
    public const SETTING_KEYS = [
        'site_name', 'tagline', 'whatsapp', 'phone',
        'hero_title', 'hero_highlight', 'hero_text',
        'cta_title', 'cta_text',
        'trust_1', 'trust_2', 'trust_3', 'about_text', 'address', 'email',
        'stat1_num', 'stat1_label', 'stat2_num', 'stat2_label', 'stat3_num', 'stat3_label',
    ];
    public const IMAGE_KEYS = ['logo', 'hero_image', 'cta_image', 'about_image'];
    // Short looping background video for the hero (mp4/webm, max ~12 MB)
    public const VIDEO_KEY = 'hero_video';

    public function settingsForm()
    {
        return view('admin.settings', ['s' => Setting::all_()]);
    }

    public function settingsSave(Request $request)
    {
        $request->validate(array_merge(
            array_fill_keys(self::SETTING_KEYS, 'nullable|string|max:3000'),
            array_fill_keys(self::IMAGE_KEYS, 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072'),
            [self::VIDEO_KEY => 'nullable|file|mimes:mp4,webm|mimetypes:video/mp4,video/webm|max:12288'],
        ));
        foreach (self::SETTING_KEYS as $k) {
            Setting::put($k, $request->input($k));
        }
        foreach (self::IMAGE_KEYS as $k) {
            if ($request->hasFile($k)) {
                if ($old = Setting::get($k)) {
                    Storage::disk('public')->delete($old);
                }
                Setting::put($k, $request->file($k)->store('site', 'public'));
            }
        }
        // Hero video: replace, or remove when the box is ticked
        $vk = self::VIDEO_KEY;
        if ($request->hasFile($vk) || $request->boolean('remove_hero_video')) {
            if ($old = Setting::get($vk)) {
                Storage::disk('public')->delete($old);
                Setting::put($vk, null);
            }
            if ($request->hasFile($vk)) {
                Setting::put($vk, $request->file($vk)->store('site', 'public'));
            }
        }
        return back()->with('ok', 'Settings saved.');
    }

    /* ---------- Enquiries ---------- */
    public function enquiries()
    {
        $items = Enquiry::with('property:id,title')->latest()->paginate(30);
        Enquiry::where('is_read', false)->update(['is_read' => true]);
        return view('admin.enquiries', compact('items'));
    }

    public function enquiryDestroy(Enquiry $enquiry)
    {
        $enquiry->delete();
        return back()->with('ok', 'Deleted.');
    }

    /* ---------- Change password (no SSH on shared hosting, so it must be possible here) ---------- */
    public function passwordForm()
    {
        return view('admin.password');
    }

    public function passwordSave(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password'         => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::min(12)->letters()->numbers()],
        ]);
        $request->user()->update(['password' => \Illuminate\Support\Facades\Hash::make($request->password)]);
        return back()->with('ok', 'Password changed.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Property;

class SeoController extends Controller
{
    // /sitemap.xml: home, category pages and every live property
    public function sitemap()
    {
        $urls = [[route('home'), now(), '1.0']];
        foreach (array_keys(Property::TYPES) as $type) {
            $urls[] = [route('properties.type', $type), now(), '0.8'];
        }
        foreach (Property::active()->get(['slug', 'updated_at']) as $p) {
            $urls[] = [route('properties.show', $p), $p->updated_at, '0.6'];
        }
        $xml = view('sitemap', compact('urls'))->render();
        return response($xml, 200, ['Content-Type' => 'application/xml'])
            ->header('Cache-Control', 'public, max-age=3600');
    }

    // /robots.txt generated so the Sitemap line carries the real domain
    public function robots()
    {
        $txt = "User-agent: *\nDisallow: /admin\nDisallow: /enquire\nAllow: /\n\nSitemap: " . route('sitemap') . "\n";
        return response($txt, 200, ['Content-Type' => 'text/plain']);
    }
}

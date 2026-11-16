<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>Admin - @yield('title')</title>
    <style>
        body{font:14px/1.5 system-ui,sans-serif;margin:0;background:#f3f5f8;color:#0f1d2e}
        /* Left sidebar menu (becomes a top bar on phones) */
        .app{display:flex;min-height:100vh}
        aside{width:220px;background:#0f5a2a;color:#fff;padding:16px 0;display:flex;flex-direction:column;position:sticky;top:0;height:100vh;box-sizing:border-box}
        aside h3{margin:0 16px 14px;font-size:18px}
        aside a,aside button{display:block;width:100%;text-align:left;color:#dff5e6;background:none;border:0;font:inherit;cursor:pointer;text-decoration:none;padding:10px 16px;box-sizing:border-box;border-left:4px solid transparent}
        aside a:hover,aside button:hover{background:#0b4a22;color:#fff}
        aside a.on{background:#0b4a22;color:#fff;border-left-color:#f5b400}
        aside .sp{flex:1}
        .content{flex:1;min-width:0}
        main{max-width:1000px;margin:20px auto;padding:0 16px}
        .box{background:#fff;border-radius:8px;padding:16px;box-shadow:0 1px 4px #0001;overflow-x:auto}
        table{width:100%;border-collapse:collapse}th,td{text-align:left;padding:8px;border-bottom:1px solid #eef0f3;vertical-align:top}
        label{display:block;margin:10px 0 3px;font-weight:600}
        input:not([type=checkbox]),select,textarea{width:100%;padding:8px;border:1px solid #cbd3dd;border-radius:6px;font:inherit;box-sizing:border-box}
        .btn{background:#1d4ed8;color:#fff;border:0;padding:8px 16px;border-radius:6px;cursor:pointer;text-decoration:none;display:inline-block;font:inherit}
        .btn.red{background:#dc2626}.btn.sm{padding:4px 10px}
        .ok{background:#dcfce7;padding:10px;border-radius:6px;margin-bottom:12px}
        .er{background:#fee2e2;padding:10px;border-radius:6px;margin-bottom:12px}
        .row{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:12px}
        img.th{width:60px;height:40px;object-fit:cover;border-radius:4px}
        @media(max-width:760px){
            .app{flex-direction:column}
            aside{width:100%;height:auto;position:static;flex-direction:row;flex-wrap:wrap;padding:8px}
            aside h3{width:100%;margin:4px 8px}
            aside a,aside button{width:auto;border-left:0;padding:8px 12px}
            aside .sp{display:none}
        }
    </style>
</head>
<body>
@auth
<div class="app">
<aside>
    <h3>Admin Panel</h3>
    <a href="{{ route('admin.properties.index') }}" @class(['on' => request()->routeIs('admin.properties.*')])>Properties</a>
    <a href="{{ route('admin.enquiries') }}" @class(['on' => request()->routeIs('admin.enquiries*')])>Enquiries</a>
    <a href="{{ route('admin.settings') }}" @class(['on' => request()->routeIs('admin.settings*')])>Site Settings</a>
    <a href="{{ route('admin.password') }}" @class(['on' => request()->routeIs('admin.password*')])>Change Password</a>
    <a href="{{ route('home') }}" target="_blank">View site</a>
    <span class="sp"></span>
    <form method="post" action="{{ route('admin.logout') }}">@csrf<button>Logout</button></form>
</aside>
<div class="content">
@endauth
<main>
    @if(session('ok'))<div class="ok">{{ session('ok') }}</div>@endif
    @if($errors->any())<div class="er">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>@endif
    @yield('content')
</main>
@auth
</div></div>
@endauth
</body>
</html>

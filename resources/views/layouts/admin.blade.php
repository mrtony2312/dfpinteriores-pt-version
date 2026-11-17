<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — DFP Interiores</title>
    <style>
        :root {
            --ink: #1a1a1a;
            --muted: #5c5c5c;
            --line: #e4e0d8;
            --bg: #f7f5f1;
            --panel: #ffffff;
            --accent: #2f4a3a;
            --accent-hover: #243a2d;
            --danger: #8b2e2e;
            --ok: #1f5c3a;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Segoe UI", "Helvetica Neue", Arial, sans-serif;
            color: var(--ink);
            background:
                radial-gradient(1200px 500px at 10% -10%, #e8efe9 0%, transparent 55%),
                radial-gradient(900px 400px at 100% 0%, #f0ebe3 0%, transparent 50%),
                var(--bg);
        }
        a { color: var(--accent); text-decoration: none; }
        a:hover { text-decoration: underline; }
        .wrap { max-width: 720px; margin: 0 auto; padding: 2rem 1.25rem 3rem; }
        .top {
            display: flex; align-items: center; justify-content: space-between;
            gap: 1rem; margin-bottom: 1.75rem;
        }
        .brand { font-size: 1.15rem; font-weight: 700; letter-spacing: 0.02em; }
        .brand span { color: var(--muted); font-weight: 500; }
        .panel {
            background: var(--panel);
            border: 1px solid var(--line);
            padding: 1.5rem 1.35rem;
        }
        h1 { margin: 0 0 0.35rem; font-size: 1.45rem; font-weight: 700; }
        .lead { margin: 0 0 1.35rem; color: var(--muted); font-size: 0.95rem; line-height: 1.45; }
        label { display: block; font-size: 0.82rem; font-weight: 600; margin: 0 0 0.35rem; }
        .field { margin-bottom: 1rem; }
        input[type="email"], input[type="password"], input[type="text"] {
            width: 100%; padding: 0.7rem 0.8rem; border: 1px solid var(--line);
            background: #fbfaf8; font-size: 1rem; color: var(--ink);
        }
        input:focus { outline: 2px solid rgba(47,74,58,.25); border-color: var(--accent); }
        .btn {
            display: inline-block; border: 0; cursor: pointer;
            background: var(--accent); color: #fff; font-weight: 600;
            padding: 0.75rem 1.2rem; font-size: 0.95rem;
        }
        .btn:hover { background: var(--accent-hover); }
        .btn-danger { background: var(--danger); }
        .btn-danger:hover { background: #6e2424; }
        .btn-link { background: transparent; color: var(--muted); border: 0; cursor: pointer; font-size: 0.9rem; }
        .actions { display: flex; flex-wrap: wrap; gap: 0.75rem; align-items: center; }
        .danger-zone {
            margin-top: 1.75rem; padding-top: 1.25rem; border-top: 1px solid var(--line);
        }
        .danger-zone p { margin: 0 0 0.75rem; color: var(--muted); font-size: 0.9rem; }
        .alert {
            padding: 0.75rem 0.9rem; margin-bottom: 1rem; font-size: 0.92rem;
            border: 1px solid var(--line);
        }
        .alert-ok { background: #eef7f1; color: var(--ok); border-color: #cfe8d8; }
        .alert-err { background: #faf0f0; color: var(--danger); border-color: #edd0d0; }
        .errors { margin: 0 0 1rem; padding-left: 1.1rem; color: var(--danger); font-size: 0.9rem; }
        .hint { margin-top: 1.25rem; font-size: 0.85rem; color: var(--muted); }
    </style>
</head>
<body>
    <div class="wrap">
        @hasSection('header')
            @yield('header')
        @else
            <div class="top">
                <div class="brand">DFP Interiores <span>/ Admin</span></div>
                @auth
                    @if(auth()->user()->is_admin)
                        <form method="post" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="btn-link">Terminar sessão</button>
                        </form>
                    @endif
                @endauth
            </div>
        @endif

        @yield('content')
    </div>
</body>
</html>

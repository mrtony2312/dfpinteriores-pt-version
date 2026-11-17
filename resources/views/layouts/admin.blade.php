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
            background: var(--bg);
        }
        a { color: var(--accent); }
        .wrap { max-width: 760px; margin: 0 auto; padding: 2rem 1.25rem 3rem; }
        .top {
            display: flex; justify-content: space-between; align-items: center;
            gap: 1rem; margin-bottom: 1.5rem;
        }
        .brand { font-weight: 700; letter-spacing: .02em; text-decoration: none; color: var(--ink); }
        .brand span { color: var(--muted); font-weight: 500; }
        .card {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 1.5rem;
            box-shadow: 0 8px 24px rgba(0,0,0,.04);
        }
        h1 { margin: 0 0 .35rem; font-size: 1.45rem; }
        .lead { margin: 0 0 1.35rem; color: var(--muted); font-size: .95rem; line-height: 1.45; }
        label { display: block; font-size: .85rem; font-weight: 600; margin: 0 0 .35rem; }
        input {
            width: 100%; padding: .7rem .85rem; border: 1px solid var(--line);
            border-radius: 10px; font: inherit; background: #fff;
        }
        .field { margin-bottom: 1rem; }
        .actions { display: flex; flex-wrap: wrap; gap: .75rem; margin-top: 1.25rem; }
        .btn {
            border: 0; border-radius: 999px; padding: .7rem 1.15rem;
            font: inherit; font-weight: 600; cursor: pointer; text-decoration: none;
            display: inline-flex; align-items: center;
        }
        .btn-primary { background: var(--accent); color: #fff; }
        .btn-primary:hover { background: var(--accent-hover); }
        .btn-danger { background: var(--danger); color: #fff; }
        .btn-link { background: transparent; color: var(--muted); }
        .alert {
            padding: .85rem 1rem; border-radius: 10px; margin-bottom: 1rem; font-size: .92rem;
        }
        .alert-ok { background: #e8f5ee; color: var(--ok); }
        .alert-err { background: #f8eaea; color: var(--danger); }
        .errors { color: var(--danger); font-size: .85rem; margin: .35rem 0 0; }
        .hint { margin-top: 1.25rem; font-size: .85rem; color: var(--muted); }
        table { width: 100%; border-collapse: collapse; font-size: .85rem; margin-top: 1.5rem; }
        th, td { text-align: left; padding: .55rem .35rem; border-bottom: 1px solid var(--line); vertical-align: top; }
        th { color: var(--muted); font-weight: 600; }
        code { font-size: .8rem; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="top">
            <a class="brand" href="{{ route('home') }}">DFP <span>Interiores</span></a>
            @auth
                @if (auth()->user()->is_admin)
                    <form method="post" action="{{ route('admin.logout') }}">
                        @csrf
                        <button class="btn btn-link" type="submit">Terminar sessão</button>
                    </form>
                @endif
            @endauth
        </div>
        @if (session('success'))
            <div class="alert alert-ok">{{ session('success') }}</div>
        @endif
        @yield('content')
    </div>
</body>
</html>

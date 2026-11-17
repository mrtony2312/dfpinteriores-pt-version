@extends('layouts.admin')

@section('title', 'Login admin')

@section('header')
    <div class="top">
        <div class="brand">DFP Interiores <span>/ Admin</span></div>
        <a href="{{ url('/') }}">← Voltar ao site</a>
    </div>
@endsection

@section('content')
    <div class="panel">
        <h1>Área de administração</h1>
        <p class="lead">Inicie sessão para editar os dados bancários apresentados no checkout.</p>

        @if ($errors->any())
            <ul class="errors">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form method="post" action="{{ route('admin.login.submit') }}">
            @csrf
            <div class="field">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            </div>
            <div class="field">
                <label for="password">Palavra-passe</label>
                <input id="password" type="password" name="password" required autocomplete="current-password">
            </div>
            <button type="submit" class="btn">Entrar</button>
        </form>
    </div>
@endsection

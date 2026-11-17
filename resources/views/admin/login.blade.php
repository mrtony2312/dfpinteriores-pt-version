@extends('layouts.admin')

@section('title', 'Login admin')

@section('content')
<div class="card" style="max-width:420px;margin:2rem auto;">
    <h1>Área de administração</h1>
    <p class="lead">Inicie sessão para editar os dados bancários do checkout.</p>

    @if ($errors->any())
        <div class="alert alert-err">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="post" action="{{ route('admin.login.submit') }}">
        @csrf
        <div class="field">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>
        <div class="field">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required>
        </div>
        <div class="actions">
            <button class="btn btn-primary" type="submit">Entrar</button>
        </div>
    </form>
</div>
@endsection

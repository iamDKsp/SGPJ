@extends('layouts.app')

@section('title', 'Entrar | SGPJ')
@section('body-class', 'auth-page')

@section('content')
  <div class="login-screen">
    <section class="login-card" role="dialog" aria-modal="true">
      <header class="login-card__header">
        <div class="login-brand">SGPJ</div>
        <h1 class="login-title">Bem-vindo de volta</h1>
        <p class="login-subtitle">Acesse o painel com suas credenciais autorizadas.</p>
      </header>
      <form class="login-form" method="POST" action="{{ route('login.submit') }}" novalidate>
        @csrf
        <label class="login-field" for="username">
          <span>Usuário</span>
          <input
            type="text"
            id="username"
            name="username"
            autocomplete="username"
            placeholder="Digite tarcisio ou lucas"
            value="{{ old('username') }}"
            required
          />
        </label>
        <label class="login-field" for="password">
          <span>Senha</span>
          <input
            type="password"
            id="password"
            name="password"
            autocomplete="current-password"
            placeholder="Digite 123"
            required
          />
        </label>
        <label class="login-remember" for="remember">
          <input type="checkbox" id="remember" name="remember" value="1" {{ old('remember') ? 'checked' : '' }} />
          <span>Lembrar-me neste dispositivo</span>
        </label>
        <button class="login-submit" type="submit">Entrar</button>
        <p class="login-hint">Usuários autorizados: tarcisio · lucas — senha: 123</p>
        @if ($errors->any())
          <p class="login-error" role="alert" aria-live="polite">{{ $errors->first() }}</p>
        @endif
      </form>
    </section>
  </div>
@endsection

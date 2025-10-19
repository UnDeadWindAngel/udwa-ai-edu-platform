@extends('layouts.app')

@section('title', 'UDWA AI Edu Platform')

@section('content')
    <div class="row">
        <div class="col-12 text-center mb-5">
            <h1 class="display-4 text-primary">
                <i class="fas fa-graduation-cap"></i> UDWA AI Edu Platform
            </h1>
            <p class="lead">Интеллектуальная образовательная платформа с последовательным обучением</p>

            @guest
                <div class="mt-4">
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg me-3">
                        <i class="fas fa-sign-in-alt"></i> Войти
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-user-plus"></i> Регистрация
                    </a>
                </div>
            @endguest
        </div>
    </div>

    @auth
        <!-- Дашборд для разных ролей -->
        @if(auth()->user()->isStudent())
            @include('home.student-dashboard')
        @elseif(auth()->user()->isTeacher())
            @include('home.teacher-dashboard')
        @elseif(auth()->user()->isParent())
            @include('home.parent-dashboard')
        @elseif(auth()->user()->isAdmin() || auth()->user()->isModerator())
            @include('home.admin-dashboard')
        @endif

        <!-- Блок с системой уровней -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <h4 class="mb-0"><i class="fas fa-layer-group"></i> Система уровней UDWA</h4>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <div class="level-badge bg-primary text-white p-3 rounded">
                                    <h5>Начальный</h5>
                                    <small>Основы предмета</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="level-badge bg-success text-white p-3 rounded">
                                    <h5>Базовый</h5>
                                    <small>Фундаментальные знания</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="level-badge bg-warning text-dark p-3 rounded">
                                    <h5>Продвинутый</h5>
                                    <small>Углубленное изучение</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="level-badge bg-danger text-white p-3 rounded">
                                    <h5>Профильный</h5>
                                    <small>Экспертные знания</small>
                                </div>
                            </div>
                        </div>
                        <p class="text-center mt-3 text-muted">
                            <small>Прогрессируйте последовательно: каждый следующий уровень открывается после успешного завершения предыдущего</small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endauth
@endsection

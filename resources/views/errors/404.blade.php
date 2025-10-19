@extends('layouts.app')

@section('title', 'Страница не найдена')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="error-container">
                        <i class="fas fa-exclamation-triangle fa-5x text-warning mb-4"></i>
                        <h1 class="display-4 text-muted">404</h1>
                        <h2 class="mb-4">Страница не найдена</h2>
                        <p class="lead text-muted mb-4">
                            Запрашиваемая страница не существует или была перемещена.
                        </p>

                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                                    <a href="{{ url('/') }}" class="btn btn-primary btn-lg">
                                        <i class="fas fa-home"></i> На главную
                                    </a>
                                    <a href="javascript:history.back()" class="btn btn-outline-secondary btn-lg">
                                        <i class="fas fa-arrow-left"></i> Назад
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5">
                            <h5>Популярные разделы</h5>
                            <div class="row mt-3">
                                <div class="col-md-3 mb-2">
                                    <a href="{{ route('subjects.index') }}" class="btn btn-outline-primary w-100">
                                        <i class="fas fa-book"></i> Предметы
                                    </a>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <a href="{{ route('progress') }}" class="btn btn-outline-success w-100">
                                        <i class="fas fa-chart-line"></i> Мой прогресс
                                    </a>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <a href="{{ route('profile') }}" class="btn btn-outline-info w-100">
                                        <i class="fas fa-user"></i> Профиль
                                    </a>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <a href="{{ route('teacher.dashboard') }}" class="btn btn-outline-warning w-100">
                                        <i class="fas fa-chalkboard-teacher"></i> Учителю
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .error-container {
            max-width: 600px;
            margin: 0 auto;
        }
    </style>
@endpush

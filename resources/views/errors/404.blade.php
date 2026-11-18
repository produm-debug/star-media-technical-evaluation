@extends('layouts.app')

@section('title', 'Page not found')

@section('content')
    <div class="text-center py-5">
        <h1 class="display-4">404</h1>
        <p class="lead">Sorry, we couldn't find that page.</p>
        <a href="{{ route('articles.index') }}" class="btn btn-primary">Back to articles</a>
    </div>
@endsection

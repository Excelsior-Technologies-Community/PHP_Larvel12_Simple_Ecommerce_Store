@extends('layouts.customer')

@section('title', $page->title)

@section('content')
<div class="container">
    <div class="glass-card p-5">
        <h1 class="fw-bold mb-4">{{ $page->title }}</h1>
        <div class="content">
            {!! nl2br(e($page->content)) !!}
        </div>
    </div>
</div>
@endsection

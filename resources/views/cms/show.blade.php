@extends('layouts.app')

@section('title', $page->meta_title ?: $page->title)

@section('meta')
    @if($page->meta_description)
        <meta name="description" content="{{ $page->meta_description }}">
    @endif
    @if($page->meta_keywords)
        <meta name="keywords" content="{{ $page->meta_keywords }}">
    @endif
@endsection

@section('content')
<div class="min-h-screen bg-white">
    <!-- Page Header -->
    <div class="bg-stone-50 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-stone-900 sm:text-5xl">
                    {{ $page->title }}
                </h1>
                @if($page->excerpt)
                    <p class="mt-4 text-xl text-stone-600 max-w-3xl mx-auto">
                        {{ $page->excerpt }}
                    </p>
                @endif
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="prose prose-lg max-w-none">
            @if($page->featured_image)
                <div class="mb-8">
                    <img src="{{ asset('storage/' . $page->featured_image) }}" 
                         alt="{{ $page->title }}" 
                         class="w-full h-64 object-cover rounded-xl shadow-lg">
                </div>
            @endif
            
            {!! $page->content !!}
        </div>
    </div>
</div>
@endsection

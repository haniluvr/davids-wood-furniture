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

@section('styles')
<style>
    .sidebar-link {
        position: relative;
        padding-left: 1.5rem;
        transition: all 0.2s ease;
        color: #4a5568;
    }
    a.sidebar-link.active {
        color: #1a202c !important;
        font-weight: 900 !important;
        border-left: 3px solid #655e4e !important;
        padding-left: calc(1.5rem - 3px) !important;
    }
    .sidebar-link:hover {
        color: #655e4e;
    }
    #content-area section {
        scroll-margin-top: 120px;
    }
    .content-link {
        color: #059669;
        text-decoration: underline;
    }
    .content-link:hover {
        color: #047857;
    }
</style>
@endsection

@section('content')
@php
    // Parse content to extract headings with IDs for sidebar navigation
    $sidebarLinks = [];
    $hasSections = false;
    
    if (!empty($page->content)) {
        // Use DOMDocument to parse HTML content
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $htmlContent = mb_convert_encoding($page->content, 'HTML-ENTITIES', 'UTF-8');
        @$dom->loadHTML($htmlContent, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        
        // Find all headings (h2, h3) with IDs
        $xpath = new DOMXPath($dom);
        $headings = $xpath->query('//h2[@id] | //h3[@id]');
        
        if ($headings->length > 0) {
            $hasSections = true;
            foreach ($headings as $heading) {
                $id = $heading->getAttribute('id');
                $text = trim($heading->textContent);
                if ($id && $text) {
                    $sidebarLinks[] = [
                        'id' => $id,
                        'text' => $text,
                        'level' => $heading->nodeName === 'h2' ? 2 : 3
                    ];
                }
            }
        }
    }
@endphp

<div class="min-h-screen">
    <!-- Top Banner with Light Green Background -->
    <div class="py-40 sm:px-6" style="background-color: #b3aa99;">
        <div class="max-w-7xl mx-auto text-center">
            <h1 class="text-5xl font-bold text-gray-900 mb-5">{{ $page->title }}</h1>
            @if($page->excerpt)
                <p class="text-2xl text-gray-700 mb-5 font-normal">{{ $page->excerpt }}</p>
            @endif
            @if($page->updated_at)
                <p class="text-gray-600 text-base">Updated {{ $page->updated_at->format('F d, Y') }}</p>
            @endif
        </div>
    </div>

    <!-- Main Content Layout -->
    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row">
        @if($hasSections && count($sidebarLinks) > 0)
        <!-- Left Sidebar Navigation -->
        <aside class="w-full lg:w-80 lg:sticky lg:top-20 lg:self-start border-l border-gray-300 my-20">
            <nav class="space-y-1">
                @foreach($sidebarLinks as $link)
                    <a href="#{{ $link['id'] }}" class="sidebar-link block py-2.5 px-8">{{ $link['text'] }}</a>
                @endforeach
            </nav>
        </aside>
        @endif

        <!-- Right Content Area -->
        <main id="content-area" class="flex-1 {{ $hasSections && count($sidebarLinks) > 0 ? 'ps-20' : '' }}">
            @if($page->featured_image)
                <div class="mb-8 pt-20">
                    <img src="{{ asset('storage/' . $page->featured_image) }}" 
                         alt="{{ $page->title }}" 
                         class="w-full h-64 object-cover rounded-xl shadow-lg">
                </div>
            @endif
            
            <div class="space-y-6 text-gray-700 leading-relaxed text-base {{ $hasSections && count($sidebarLinks) > 0 ? 'pt-20' : 'pt-10' }}">
                {!! $page->content !!}
            </div>
        </main>
    </div>
</div>

@if($hasSections && count($sidebarLinks) > 0)
<script>
(function() {
    'use strict';
    
    function initSidebarActive() {
        try {
            var contentArea = document.getElementById('content-area');
            if (!contentArea) {
                setTimeout(initSidebarActive, 100);
                return;
            }
            
            var sections = contentArea.querySelectorAll('section[id], h2[id], h3[id]');
            var links = document.querySelectorAll('.sidebar-link');
            
            if (sections.length === 0 || links.length === 0) {
                setTimeout(initSidebarActive, 100);
                return;
            }
            
            function setActiveLink() {
                var current = '';
                var scrollY = window.scrollY || window.pageYOffset || 0;
                var scrollPos = scrollY + 200;
                
                // Find current section
                for (var i = 0; i < sections.length; i++) {
                    var section = sections[i];
                    var sectionTop = section.offsetTop;
                    var sectionHeight = section.offsetHeight || section.clientHeight;
                    
                    if (scrollPos >= sectionTop && scrollPos < sectionTop + sectionHeight) {
                        current = section.getAttribute('id');
                        break;
                    }
                }
                
                // Fallback: find last passed section
                if (!current) {
                    if (scrollY < 100) {
                        current = sections[0] ? sections[0].getAttribute('id') : '';
                    } else {
                        for (var j = sections.length - 1; j >= 0; j--) {
                            if (scrollY >= sections[j].offsetTop - 200) {
                                current = sections[j].getAttribute('id');
                                break;
                            }
                        }
                    }
                }
                
                // Update links
                if (current) {
                    for (var k = 0; k < links.length; k++) {
                        var link = links[k];
                        var href = link.getAttribute('href') || '';
                        var linkId = href.replace('#', '').trim();
                        
                        if (linkId === current) {
                            link.classList.add('active');
                        } else {
                            link.classList.remove('active');
                        }
                    }
                }
            }
            
            // Click handlers
            for (var i = 0; i < links.length; i++) {
                (function(link) {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        var href = this.getAttribute('href') || '';
                        var targetId = href.replace('#', '').trim();
                        var target = document.getElementById(targetId);
                        
                        if (target) {
                            // Remove all active
                            for (var j = 0; j < links.length; j++) {
                                links[j].classList.remove('active');
                            }
                            // Add to clicked
                            this.classList.add('active');
                            
                            // Scroll
                            var offset = 150;
                            var rect = target.getBoundingClientRect();
                            var targetPos = (rect.top + (window.pageYOffset || document.documentElement.scrollTop)) - offset;
                            
                            window.scrollTo({
                                top: Math.max(0, targetPos),
                                behavior: 'smooth'
                            });
                            
                            setTimeout(setActiveLink, 600);
                        }
                    });
                })(links[i]);
            }
            
            // Scroll handler
            var scrollTimeout = null;
            window.addEventListener('scroll', function() {
                if (scrollTimeout) {
                    clearTimeout(scrollTimeout);
                }
                scrollTimeout = setTimeout(setActiveLink, 10);
            }, { passive: true });
            
            // Initial set
            setTimeout(setActiveLink, 50);
        } catch (e) {
            console.error('Sidebar active error:', e);
        }
    }
    
    // Run immediately if DOM is ready, otherwise wait
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSidebarActive);
    } else {
        initSidebarActive();
    }
})();
</script>
@endif
@endsection

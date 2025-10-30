@extends('admin.layouts.app')

@section('title', 'Integrations')

@section('content')
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-title-md font-semibold text-gray-800 dark:text-white">Integrations</h1>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($integrations as $integration)
      <div class="bg-white dark:bg-boxdark rounded-xl p-6 border border-stroke dark:border-strokedark flex flex-col">
        <div class="flex items-start justify-between">
          <div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">{{ $integration['name'] }}</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $integration['description'] }}</p>
          </div>
          <span class="text-xs px-2 py-1 rounded-full {{ $integration['enabled'] ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
            {{ $integration['enabled'] ? 'Enabled' : 'Disabled' }}
          </span>
        </div>
        <div class="mt-4">
          <a href="{{ admin_route('integrations.edit', $integration['key']) }}" class="inline-flex items-center gap-2 text-sm px-3 py-2 rounded-lg bg-primary text-white hover:bg-primary/90">
            <i data-lucide="settings-2" class="w-4 h-4"></i>
            Configure
          </a>
        </div>
      </div>
    @endforeach
  </div>
@endsection




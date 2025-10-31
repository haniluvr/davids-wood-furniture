@extends('admin.layouts.app')

@section('title', 'Sustainability Settings')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-title-md font-semibold text-gray-800 dark:text-white">Sustainability Settings</h1>
    <a href="{{ admin_route('settings.index') }}" class="text-sm text-gray-500 hover:text-primary">Back to Settings</a>
  </div>

  <div class="grid grid-cols-1 gap-6">
    <form method="POST" action="{{ admin_route('settings.sustainability.update') }}" class="bg-white dark:bg-boxdark rounded-xl p-6 border border-stroke dark:border-strokedark">
      @csrf
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Carbon Offset Provider</label>
          <input type="text" name="carbon_offset_provider" value="{{ old('carbon_offset_provider', $settings['carbon_offset_provider']) }}" class="mt-1 w-full rounded-lg border border-stroke dark:border-strokedark bg-white dark:bg-boxdark py-2 px-3" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Offset per Order (kg CO₂e)</label>
          <input type="number" step="0.01" min="0" name="offset_per_order" value="{{ old('offset_per_order', $settings['offset_per_order']) }}" class="mt-1 w-full rounded-lg border border-stroke dark:border-strokedark bg-white dark:bg-boxdark py-2 px-3" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Packaging Material</label>
          <input type="text" name="packaging_material" value="{{ old('packaging_material', $settings['packaging_material']) }}" class="mt-1 w-full rounded-lg border border-stroke dark:border-strokedark bg-white dark:bg-boxdark py-2 px-3" />
        </div>
        <div class="md:col-span-2">
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sourcing Policy</label>
          <textarea name="sourcing_policy" rows="5" class="mt-1 w-full rounded-lg border border-stroke dark:border-strokedark bg-white dark:bg-boxdark py-2 px-3">{{ old('sourcing_policy', $settings['sourcing_policy']) }}</textarea>
        </div>
        <div class="md:col-span-2 flex items-center gap-3">
          <input type="checkbox" id="show_badge" name="show_badge" value="1" {{ old('show_badge', $settings['show_badge']) ? 'checked' : '' }} class="rounded" />
          <label for="show_badge" class="text-sm text-gray-700 dark:text-gray-300">Show sustainability badge on storefront</label>
        </div>
      </div>
      <div class="mt-6 flex justify-end">
        <button type="submit" class="px-4 py-2 rounded-lg bg-primary text-white hover:bg-primary/90">Save Changes</button>
      </div>
    </form>
  </div>
@endsection







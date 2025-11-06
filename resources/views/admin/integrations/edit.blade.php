@extends('admin.layouts.app')

@section('title', ucfirst($integration).' Integration')

@section('content')
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-title-md font-semibold text-gray-800 dark:text-white">{{ ucfirst($integration) }} Integration</h1>
    <a href="{{ admin_route('integrations.index') }}" class="text-sm text-gray-500 hover:text-primary">Back</a>
  </div>

  @if($integration === 'xendit')
    <div class="bg-white dark:bg-boxdark rounded-xl p-6 border border-stroke dark:border-strokedark">
      <form id="xenditForm" method="POST" action="{{ admin_route('integrations.update', ['integration' => 'xendit']) }}">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Public Key</label>
            <input type="text" name="public_key" value="{{ old('public_key', $config['public_key']) }}" class="mt-1 w-full rounded-lg border border-stroke dark:border-strokedark bg-white dark:bg-boxdark py-2 px-3" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Secret Key</label>
            <input type="text" name="secret_key" value="{{ old('secret_key', $config['secret_key']) }}" class="mt-1 w-full rounded-lg border border-stroke dark:border-strokedark bg-white dark:bg-boxdark py-2 px-3" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Callback Token</label>
            <input type="text" name="callback_token" value="{{ old('callback_token', $config['callback_token']) }}" class="mt-1 w-full rounded-lg border border-stroke dark:border-strokedark bg-white dark:bg-boxdark py-2 px-3" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Environment</label>
            <select name="environment" class="mt-1 w-full rounded-lg border border-stroke dark:border-strokedark bg-white dark:bg-boxdark py-2 px-3">
              <option value="test" {{ old('environment', $config['environment']) === 'test' ? 'selected' : '' }}>Test</option>
              <option value="live" {{ old('environment', $config['environment']) === 'live' ? 'selected' : '' }}>Live</option>
            </select>
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Payment Methods</label>
            @php
              $selectedMethods = explode(',', old('payment_methods', $config['payment_methods'] ?? 'CREDIT_CARD,DEBIT_CARD,EWALLET'));
              $selectedMethods = array_map('trim', $selectedMethods);
              $paymentMethodOptions = [
                'CREDIT_CARD' => 'Credit Card (Visa, Mastercard, Amex, JCB)',
                'DEBIT_CARD' => 'Debit Card',
                'EWALLET' => 'E-Wallet (GCash, PayMaya, GrabPay, ShopeePay)',
                'BANK_TRANSFER' => 'Bank Transfer (BPI, BDO, Metrobank)',
                'RETAIL_OUTLET' => 'Retail Outlet (7-Eleven, Cebuana, LBC)',
                'QR_CODE' => 'QR Code (QRPH)',
                'DIRECT_DEBIT' => 'Direct Debit (BPI, RCBC, Chinabank, UBP)',
              ];
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 p-4 bg-gray-50 dark:bg-graydark rounded-lg border border-stroke dark:border-strokedark">
              @foreach($paymentMethodOptions as $value => $label)
                <label class="flex items-center gap-2 cursor-pointer">
                  <input 
                    type="checkbox" 
                    name="payment_methods[]" 
                    value="{{ $value }}"
                    {{ in_array($value, $selectedMethods) ? 'checked' : '' }}
                    class="rounded border-stroke dark:border-strokedark"
                  />
                  <span class="text-sm text-gray-700 dark:text-gray-300">{{ $label }}</span>
                </label>
              @endforeach
            </div>
          </div>
        </div>
        <div class="mt-6 flex justify-between items-center gap-3">
          <div class="flex items-center gap-3">
            <input type="checkbox" id="enabled" name="enabled" value="1" {{ old('enabled', $config['enabled']) ? 'checked' : '' }} class="rounded" />
            <label for="enabled" class="text-sm text-gray-700 dark:text-gray-300">Enable Xendit</label>
          </div>
          <div class="flex gap-3">
          <button type="button" id="testXendit" class="px-4 py-2 rounded-lg border border-stroke dark:border-strokedark text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-graydark">Test Connection</button>
          <button type="submit" class="px-4 py-2 rounded-lg bg-primary text-white hover:bg-primary/90">Save</button>
          </div>
        </div>
      </form>
    </div>
  @endif
  @push('scripts')
  <script>
    document.getElementById('testXendit')?.addEventListener('click', async function(){
      const form = document.getElementById('xenditForm');
      const data = new FormData(form);
      const payload = {
        public_key: data.get('public_key') || '',
        secret_key: data.get('secret_key') || '',
        callback_token: data.get('callback_token') || '',
        environment: data.get('environment') || '{{ $config['environment'] ?? 'test' }}',
      };
      const resp = await fetch('{{ admin_route('integrations.test', ['integration' => 'xendit']) }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(payload)
      });
      const json = await resp.json();
      alert(json.success ? ('Success: ' + (json.message || 'Connected')) : ('Failed: ' + (json.message || 'Check keys')));
    });
  </script>
  @endpush
@endsection



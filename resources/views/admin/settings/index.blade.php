@extends('admin.layouts.app')

@section('title', 'Settings')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Settings</h1>
            <p class="text-muted">Manage your application settings and configuration</p>
        </div>
    </div>

    <!-- Settings Tabs -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="settingsTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="general-tab" data-bs-toggle="tab" 
                                    data-bs-target="#general" type="button" role="tab" aria-controls="general" 
                                    aria-selected="true">
                                <i class="fas fa-cog"></i> General
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="email-tab" data-bs-toggle="tab" 
                                    data-bs-target="#email" type="button" role="tab" aria-controls="email" 
                                    aria-selected="false">
                                <i class="fas fa-envelope"></i> Email
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="payment-tab" data-bs-toggle="tab" 
                                    data-bs-target="#payment" type="button" role="tab" aria-controls="payment" 
                                    aria-selected="false">
                                <i class="fas fa-credit-card"></i> Payment
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="shipping-tab" data-bs-toggle="tab" 
                                    data-bs-target="#shipping" type="button" role="tab" aria-controls="shipping" 
                                    aria-selected="false">
                                <i class="fas fa-shipping-fast"></i> Shipping
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="settingsTabsContent">
                        <!-- General Settings -->
                        <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                            <form method="POST" action="{{ route('admin.settings.update-general') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="site_name" class="form-label">Site Name</label>
                                            <input type="text" class="form-control" id="site_name" name="site_name" 
                                                   value="{{ old('site_name', 'David\'s Wood Furniture') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="site_email" class="form-label">Site Email</label>
                                            <input type="email" class="form-control" id="site_email" name="site_email" 
                                                   value="{{ old('site_email', 'info@davidswoodfurniture.com') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="currency" class="form-label">Currency</label>
                                            <select class="form-select" id="currency" name="currency">
                                                <option value="USD" {{ old('currency', 'USD') == 'USD' ? 'selected' : '' }}>USD</option>
                                                <option value="EUR" {{ old('currency', 'USD') == 'EUR' ? 'selected' : '' }}>EUR</option>
                                                <option value="GBP" {{ old('currency', 'USD') == 'GBP' ? 'selected' : '' }}>GBP</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="timezone" class="form-label">Timezone</label>
                                            <select class="form-select" id="timezone" name="timezone">
                                                <option value="UTC" {{ old('timezone', 'UTC') == 'UTC' ? 'selected' : '' }}>UTC</option>
                                                <option value="America/New_York" {{ old('timezone', 'UTC') == 'America/New_York' ? 'selected' : '' }}>America/New_York</option>
                                                <option value="Europe/London" {{ old('timezone', 'UTC') == 'Europe/London' ? 'selected' : '' }}>Europe/London</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="site_description" class="form-label">Site Description</label>
                                    <textarea class="form-control" id="site_description" name="site_description" rows="3">{{ old('site_description', 'Premium wood furniture for your home') }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save General Settings
                                </button>
                            </form>
                        </div>

                        <!-- Email Settings -->
                        <div class="tab-pane fade" id="email" role="tabpanel" aria-labelledby="email-tab">
                            <form method="POST" action="{{ route('admin.settings.update-email') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="mail_driver" class="form-label">Mail Driver</label>
                                            <select class="form-select" id="mail_driver" name="mail_driver">
                                                <option value="smtp" {{ old('mail_driver', 'smtp') == 'smtp' ? 'selected' : '' }}>SMTP</option>
                                                <option value="mailgun" {{ old('mail_driver', 'smtp') == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                                <option value="ses" {{ old('mail_driver', 'smtp') == 'ses' ? 'selected' : '' }}>SES</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="mail_host" class="form-label">Mail Host</label>
                                            <input type="text" class="form-control" id="mail_host" name="mail_host" 
                                                   value="{{ old('mail_host', 'smtp.gmail.com') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="mail_port" class="form-label">Mail Port</label>
                                            <input type="number" class="form-control" id="mail_port" name="mail_port" 
                                                   value="{{ old('mail_port', '587') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="mail_username" class="form-label">Mail Username</label>
                                            <input type="text" class="form-control" id="mail_username" name="mail_username" 
                                                   value="{{ old('mail_username') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="mail_password" class="form-label">Mail Password</label>
                                    <input type="password" class="form-control" id="mail_password" name="mail_password">
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="mail_encryption" name="mail_encryption" 
                                               value="tls" {{ old('mail_encryption') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="mail_encryption">
                                            Enable TLS Encryption
                                        </label>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save Email Settings
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="testEmail()">
                                    <i class="fas fa-paper-plane"></i> Test Email
                                </button>
                            </form>
                        </div>

                        <!-- Payment Settings -->
                        <div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="payment-tab">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                Payment gateway configuration will be available here.
                            </div>
                        </div>

                        <!-- Shipping Settings -->
                        <div class="tab-pane fade" id="shipping" role="tabpanel" aria-labelledby="shipping-tab">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                Shipping method configuration will be available here.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function testEmail() {
    // This would typically make an AJAX request to test the email configuration
    alert('Email test functionality will be implemented here.');
}
</script>
@endpush
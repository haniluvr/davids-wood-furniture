<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'password',
        'google_id',
        'avatar',
        'provider',
        'phone',
        'region',
        'date_of_birth',
        'address_line_1',
        'address_line_2',
        'street',
        'barangay',
        'city',
        'province',
        'state',
        'postal_code',
        'zip_code',
        'country',
        'newsletter_subscribed',
        'newsletter_product_updates',
        'newsletter_special_offers',
        'marketing_emails',
        'is_suspended',
        'two_factor_enabled',
        'two_factor_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'newsletter_subscribed' => 'boolean',
            'newsletter_product_updates' => 'boolean',
            'newsletter_special_offers' => 'boolean',
            'marketing_emails' => 'boolean',
            'is_suspended' => 'boolean',
            'two_factor_enabled' => 'boolean',
            'two_factor_verified_at' => 'datetime',
        ];
    }

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return trim($this->first_name.' '.$this->last_name);
    }

    /**
     * Get the user's cart items.
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get the user's wishlist items.
     */
    public function wishlistItems(): HasMany
    {
        return $this->hasMany(WishlistItem::class);
    }

    /**
     * Get the user's orders.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the user's wishlists.
     */
    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Get the user's payment methods.
     */
    public function paymentMethods(): HasMany
    {
        return $this->hasMany(PaymentMethod::class);
    }

    /**
     * Check if the user is an SSO user (signed in with Google).
     */
    public function isSsoUser(): bool
    {
        return ! empty($this->google_id) || $this->provider === 'google';
    }

    /**
     * Check if the user has a password set.
     */
    public function hasPassword(): bool
    {
        return ! empty($this->password);
    }
}

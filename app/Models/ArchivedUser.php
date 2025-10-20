<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchivedUser extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'archived_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'original_user_id',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'first_name',
        'last_name',
        'phone',
        'street',
        'barangay',
        'city',
        'province',
        'region',
        'zip_code',
        'newsletter_product_updates',
        'newsletter_special_offers',
        'google_id',
        'avatar',
        'archived_at',
        'archive_reason',
        'archive_notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'archived_at' => 'datetime',
        'newsletter_product_updates' => 'boolean',
        'newsletter_special_offers' => 'boolean',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
}

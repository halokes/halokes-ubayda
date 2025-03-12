<?php

namespace App\Models\Ubayda;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessUser extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 'business_user'; // Specify the pivot table
    protected $fillable = ['user_id', 'business_id', 'role', 'last_selected', 'created_by',
        'updated_by'];

    /**
     * Get the user that owns the business user record
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the business that the user has access to
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
}

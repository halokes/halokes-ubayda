<?php

namespace App\Models\Ubayda;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Business extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'business';

    protected $fillable = ['name', 'address', 'type'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'business_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function getRouteKeyName()
    {
        return 'id';
    }
}

<?php

namespace App\Models\Ubayda;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessUser extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 'business_user'; // Specify the pivot table
    protected $fillable = ['user_id', 'business_id', 'role', 'last_selected'];

  }

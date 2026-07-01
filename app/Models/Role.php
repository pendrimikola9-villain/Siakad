<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'name',
        'slug',
        'role_id',
    ];

    /**
     * Get the parent role.
     */
    public function parent()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Get the child roles.
     */
    public function children()
    {
        return $this->hasMany(Role::class, 'role_id');
    }

    /**
     * Get the users associated with this role.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }
}

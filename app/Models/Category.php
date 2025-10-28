<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'slug'];

    protected $dates = ['deleted_at'];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the category's name with event count.
     */
    public function getNameWithCountAttribute()
    {
        $count = $this->events()->count();
        return $count > 0 ? "{$this->name} ({$count})" : $this->name;
    }

    /**
     * Scope a query to order by name.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('name');
    }

    /**
     * Scope a query to include event count.
     */
    public function scopeWithEventCount($query)
    {
        return $query->withCount('events');
    }
}

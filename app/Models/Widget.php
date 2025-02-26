<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'api_source',
        'endpoint',
        'description',
        'default_settings',
        'is_active'
    ];

    protected $casts = [
        'default_settings' => 'array',
        'is_active' => 'boolean'
    ];

    /**
     * Get the users that have this widget
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_widgets')
            ->withPivot('settings', 'position', 'is_visible')
            ->withTimestamps();
    }

    /**
     * Get user widget settings
     */
    public function userWidgets()
    {
        return $this->hasMany(UserWidget::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWidget extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'widget_id',
        'settings',
        'position',
        'is_visible'
    ];

    protected $casts = [
        'settings' => 'array',
        'position' => 'integer',
        'is_visible' => 'boolean'
    ];

    /**
     * Get the user that owns the widget
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the widget
     */
    public function widget()
    {
        return $this->belongsTo(Widget::class);
    }
}

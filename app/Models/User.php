<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'widget_ids',
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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'widget_ids' => 'array',
    ];

    /**
     * Get all widgets for this user
     */
    public function widgets()
    {
        return $this->belongsToMany(Widget::class, 'user_widgets')
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

    /**
     * Get dashboard settings
     */
    public function dashboardSettings()
    {
        return $this->userWidgets()
            ->with('widget')
            ->orderBy('position')
            ->get()
            ->map(function ($userWidget) {
                return [
                    'id' => $userWidget->widget_id,
                    'name' => $userWidget->widget->name,
                    'slug' => $userWidget->widget->slug,
                    'api_source' => $userWidget->widget->api_source,
                    'settings' => $userWidget->settings ?? $userWidget->widget->default_settings,
                    'position' => $userWidget->position,
                    'is_visible' => $userWidget->is_visible,
                ];
            });
    }
}

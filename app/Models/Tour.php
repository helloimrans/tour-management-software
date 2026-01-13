<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Tour extends Model
{
    use HasFactory, SoftDeletes;

    // Status constants
    const STATUS_UPCOMING = 'upcoming';
    const STATUS_ONGOING = 'ongoing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CLOSED = 'closed';

    protected $fillable = [
        'name',
        'destination',
        'start_date',
        'end_date',
        'description',
        'total_cost',
        'per_member_cost',
        'max_members',
        'image',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_cost' => 'decimal:2',
        'per_member_cost' => 'decimal:2',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return $this->image
            ? url(Storage::url($this->image))
            : asset('defaults/noimage/no_img.jpg');
    }

    /**
     * Scope to filter active tours (upcoming or ongoing)
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['upcoming', 'ongoing']);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id')->withDefault([
            'first_name' => '--',
        ]);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id')->withDefault([
            'first_name' => '--',
        ]);
    }

    public function users()
    {
        return $this->hasMany(User::class, 'tour_id', 'id');
    }

    public function tourMembers()
    {
        return $this->hasMany(TourMember::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function schedules()
    {
        return $this->hasMany(TourSchedule::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TourSchedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tour_id',
        'schedule_date',
        'title',
        'details',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'schedule_date' => 'date',
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class);
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
}

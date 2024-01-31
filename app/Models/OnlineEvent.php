<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineEvent extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'link',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
    */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Check if the event is happening now.
     *
     * @return bool
     */
    public function isHappeningNow()
    {
        return now()->between($this->start_date, $this->end_date);
    }
}

<?php

namespace App\Models\PLT;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrackingLog extends Model
{
    use HasFactory;

    protected $connection = 'plt';
    protected $table = 'plt_tracking_logs';
    protected $primaryKey = 'track_id';

    protected $fillable = [
        'track_project_id',
        'track_log_type',
        'track_description',
        'track_logged_by',
        'track_reference_id',
    ];

    // Relationships
    public function project()
    {
        return $this->belongsTo(Project::class, 'track_project_id', 'pro_id');
    }
}
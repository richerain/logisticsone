<?php

namespace App\Models\PSM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetLog extends Model
{
    use HasFactory;

    protected $connection = 'psm';

    protected $table = 'psm_budget_logs';

    protected $fillable = [
        'bud_id',
        'bud_spent',
        'spent_to',
        'bud_type',
        'bud_spent_date',
    ];

    protected $casts = [
        'bud_spent' => 'decimal:2',
        'bud_spent_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the budget associated with the log.
     */
    public function budget()
    {
        return $this->belongsTo(Budget::class, 'bud_id', 'bud_id');
    }
}

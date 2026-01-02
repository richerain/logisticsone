<?php

namespace App\Models\PSM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $connection = 'psm';

    protected $table = 'psm_vendor';

    protected $primaryKey = 'id';

    protected $fillable = [
        'ven_id',
        'ven_company_name',
        'ven_contact_person',
        'ven_email',
        'ven_phone',
        'ven_address',
        'ven_rating',
        'ven_type',
        'ven_product',
        'ven_status',
        'ven_desc',
        'ven_module_from',
        'ven_submodule_from',
    ];

    protected $casts = [
        'ven_rating' => 'integer',
        'ven_product' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the products associated with the vendor.
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'prod_vendor', 'ven_id');
    }

    /**
     * Scope a query to only include active vendors.
     */
    public function scopeActive($query)
    {
        return $query->where('ven_status', 'active');
    }

    /**
     * Scope a query to only include vendors of a specific type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('ven_type', $type);
    }

    /**
     * Scope a query to search in company name, contact person, or email.
     */
    public function scopeSearch($query, $search)
    {
        if (! $search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('ven_company_name', 'like', "%{$search}%")
                ->orWhere('ven_contact_person', 'like', "%{$search}%")
                ->orWhere('ven_email', 'like', "%{$search}%");
        });
    }
}

<?php

namespace App\Models;

use App\Models\User;
use App\Models\PenjualanDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penjualan extends Model
{
    protected $table = 'penjualan';
    protected $guarded = [];

    public function scopeSearch($query, $search)
    {
        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('id', 'like', "%" . $search . "%")
                    ->orWhere('nama_pelanggan', 'like', "%" . $search . "%");
            });
        }
        return $query; // Always return the query builder
    }
    public function penjualan_detail(): HasMany
    {
        return $this->hasMany(PenjualanDetail::class, 'id_penjualan');
    }

    // In Data.php model
    public function kasir(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kasir_id');
    }
}

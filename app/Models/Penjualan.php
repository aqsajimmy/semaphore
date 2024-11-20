<?php

namespace App\Models;

use App\Models\User;
use App\Models\PenjualanDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penjualan extends Model
{
    use HasFactory, Notifiable;
    protected $table = 'penjualan';
    protected $guarded = [];
    protected $casts = [
        'tanggal' => 'datetime',
    ];
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

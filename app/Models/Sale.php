<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Sale extends Model
{
    protected $fillable = [
        'tenant_id',
        'order_id',
        'user_id',
        'number',
        'payment_method',
        'subtotal',
        'total',
        'paid_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'payment_method' => PaymentMethod::class,
            'subtotal' => 'decimal:2',
            'total' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public static function generateNumber(int $tenantId): string
    {
        return DB::transaction(function () use ($tenantId): string {
            $prefix = 'V-'.now()->format('ymd');
            $count = static::query()
                ->where('tenant_id', $tenantId)
                ->where('number', 'like', $prefix.'-%')
                ->lockForUpdate()
                ->count();

            return $prefix.'-'.str_pad((string) ($count + 1), 4, '0', STR_PAD_LEFT);
        });
    }
}

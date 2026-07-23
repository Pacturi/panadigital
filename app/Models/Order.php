<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    protected $fillable = [
        'tenant_id',
        'user_id',
        'number',
        'status',
        'notes',
        'subtotal',
        'total',
        'paid_at',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'subtotal' => 'decimal:2',
            'total' => 'decimal:2',
            'paid_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function sale(): HasOne
    {
        return $this->hasOne(Sale::class);
    }

    public function isDraft(): bool
    {
        return $this->status === OrderStatus::Draft;
    }

    public function isPaid(): bool
    {
        return $this->status === OrderStatus::Paid;
    }

    public function isCancelled(): bool
    {
        return $this->status === OrderStatus::Cancelled;
    }

    public function recalculateTotals(): void
    {
        $subtotal = (float) $this->items()->sum('line_total');

        $this->forceFill([
            'subtotal' => $subtotal,
            'total' => $subtotal,
        ])->saveQuietly();
    }

    public static function generateNumber(int $tenantId): string
    {
        return DB::transaction(function () use ($tenantId): string {
            $prefix = 'P-'.now()->format('ymd');
            $count = static::query()
                ->where('tenant_id', $tenantId)
                ->where('number', 'like', $prefix.'-%')
                ->lockForUpdate()
                ->count();

            return $prefix.'-'.str_pad((string) ($count + 1), 4, '0', STR_PAD_LEFT);
        });
    }
}

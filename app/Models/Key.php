<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $title
 * @property int $system_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lock> $locks
 * @property-read int|null $locks_count
 * @property-read \App\Models\System|null $system
 * @method static \Database\Factories\KeyFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Key newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Key newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Key query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Key whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Key whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Key whereSystemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Key whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Key whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Key extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'system_id',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'system_id' => 'integer',
        ];
    }

    public function system(): BelongsTo
    {
        return $this->belongsTo(System::class);
    }

    public function locks(): BelongsToMany
    {
        return $this->belongsToMany(Lock::class);
    }
}

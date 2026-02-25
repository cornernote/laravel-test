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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Key> $keys
 * @property-read int|null $keys_count
 * @property-read \App\Models\System|null $system
 * @method static \Database\Factories\LockFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lock query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lock whereSystemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lock whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lock whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Lock extends Model
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

    public function keys(): BelongsToMany
    {
        return $this->belongsToMany(Key::class);
    }
}

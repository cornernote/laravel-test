<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Key> $keys
 * @property-read int|null $keys_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lock> $locks
 * @property-read int|null $locks_count
 * @method static \Database\Factories\SystemFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|System newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|System newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|System query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|System whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|System whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|System whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|System whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class System extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'integer',
        ];
    }

    public function keys(): HasMany
    {
        return $this->hasMany(Key::class);
    }

    public function locks(): HasMany
    {
        return $this->hasMany(Lock::class);
    }
}

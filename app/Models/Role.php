<?php

namespace App\Models;

use App\Models\Scopes\OrderByScope;
use Lacodix\LaravelModelFilter\Traits\IsSearchable;
use Lacodix\LaravelModelFilter\Traits\IsSortable;
use LaravelArchivable\Archivable;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use Archivable, IsSearchable, IsSortable;

    protected static function booted(): void
    {
        static::addGlobalScope(new OrderByScope('name'));
    }

    protected $fillable = ['name', 'guard_name'];

    protected $searchable = [
        'name',
    ];

    protected $sortable = [
        'name',
    ];
}

<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;

class PermissionService
{
    public static $permissionsByRole = [
        'admin' => [
            'User' => ['view users', 'view user rate', 'create user', 'edit user', 'archive user', 'restore user'],
            'Label' => ['view labels', 'create label', 'edit label', 'archive label', 'restore label'],
            'Role' => ['view roles', 'create role', 'edit role', 'archive role', 'restore role'],
            'Owner Company' => ['view owner company', 'edit owner company'],
            'Client User' => ['view client users', 'create client user', 'edit client user', 'archive client user', 'restore client user'],
            'Client Company' => ['view client companies', 'create client company', 'edit client company', 'archive client company', 'restore client company'],
            'Project' => ['view projects', 'view project', 'create project', 'edit project', 'archive project', 'restore project', 'edit project user access'],
            'TaskGroups' => ['create task group', 'edit task group', 'archive task group', 'restore task group', 'reorder task group'],
            'Tasks' => [
                'view tasks', 'create task', 'edit task', 'archive task', 'restore task', 'reorder task', 'complete task', 'add time log', 'delete time log',
                'view time logs', 'view comments',
            ],
            'Invoices' => ['view invoices'],
            'Reports' => ['view reports'],
            'Activities' => ['view activities'],
        ],
        'manager' => [
            'User' => ['view users'],
            'Project' => ['view projects', 'view project', 'create project', 'edit project', 'archive project', 'restore project', 'edit project user access'],
            'TaskGroups' => ['create task group', 'edit task group', 'archive task group', 'restore task group', 'reorder task group'],
            'Tasks' => [
                'view tasks', 'create task', 'edit task', 'archive task', 'restore task', 'reorder task', 'complete task', 'add time log', 'delete time log',
                'view time logs', 'view comments',
            ],
            'Reports' => ['view reports'],
        ],
        'developer' => [
            'Project' => ['view projects', 'view project'],
            'Tasks' => [
                'view tasks', 'create task', 'edit task', 'restore task', 'reorder task', 'complete task', 'add time log', 'delete time log',
                'view time logs', 'view comments',
            ],
        ],
        'designer' => [
            'Project' => ['view projects', 'view project'],
            'Tasks' => [
                'view tasks', 'create task', 'edit task', 'restore task', 'reorder task', 'complete task', 'add time log', 'delete time log',
                'view time logs', 'view comments',
            ],
        ],
        'client' => [
            'Project' => ['view projects', 'view project'],
            'Tasks' => [
                'view tasks', 'create task', 'view time logs', 'view comments',
            ],
        ],
    ];

    public static function allPermissionsGrouped(): array
    {
        return self::$permissionsByRole['admin'];
    }

    private static $usersWithAccessToProject = null;

    public static function usersWithAccessToProject($project): Collection
    {
        if (self::$usersWithAccessToProject !== null) {
            return self::$usersWithAccessToProject;
        }

        $admins = User::role('admin')
            ->with('roles:id,name')
            ->get(['id', 'name', 'avatar'])
            ->map(fn ($user) => [...$user->toArray(), 'reason' => 'admin']);

        $owners = $project
            ->clientCompany
            ->clients
            ->load('roles:id,name')
            ->map(fn ($user) => [...$user->toArray(), 'reason' => 'company owner']);

        $givenAccess = $project
            ->users
            ->load('roles:id,name')
            ->map(fn ($user) => [...$user->toArray(), 'reason' => 'given access']);

        self::$usersWithAccessToProject = collect([
            ...$admins,
            ...$owners,
            ...$givenAccess,
        ])
            ->unique('id')
            ->sortBy('name')
            ->values();

        return self::$usersWithAccessToProject;
    }
}

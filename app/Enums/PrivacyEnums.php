<?php

namespace App\Enums;

class PrivacyEnums
{
    const USERS = 'USERS';
    const ROLES  = 'ROLES';
    const POSTS = 'POSTS';

    public static function listConstants(): array
    {
        $sdClass = new \ReflectionClass(__CLASS__);
        return $sdClass->getConstants();
    }

    public static function getCapabilities(string $privacy): array
    {
        return match ($privacy) {
            self::USERS => [
                'create',
                'read',
                'update',
                'delete',
                'assign_role',
                'unassign_role',
            ],
            self::ROLES => [
                'create',
                'read',
                'update',
                'delete',
                'get_permission',
                'assign_permission',
                'unassign_permission',
                'update_capability',
                'assign_capability',
                'unassign_capability',
            ],
            self::POSTS => [
                'create',
                'read',
                'update',
                'delete'
            ],
            default => [],
        };

    }
}

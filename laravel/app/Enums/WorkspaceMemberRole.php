<?php

namespace App\Enums;

enum WorkspaceMemberRole: string
{
    case MEMBER = 'member';
    case ADMIN = 'admin';
    case OWNER = 'owner';
}

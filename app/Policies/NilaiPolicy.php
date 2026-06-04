<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Nilai;
use Illuminate\Auth\Access\HandlesAuthorization;

class NilaiPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Nilai');
    }

    public function view(AuthUser $authUser, Nilai $nilai): bool
    {
        return $authUser->can('View:Nilai');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Nilai');
    }

    public function update(AuthUser $authUser, Nilai $nilai): bool
    {
        return $authUser->can('Update:Nilai');
    }

    public function delete(AuthUser $authUser, Nilai $nilai): bool
    {
        return $authUser->can('Delete:Nilai');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Nilai');
    }

    public function restore(AuthUser $authUser, Nilai $nilai): bool
    {
        return $authUser->can('Restore:Nilai');
    }

    public function forceDelete(AuthUser $authUser, Nilai $nilai): bool
    {
        return $authUser->can('ForceDelete:Nilai');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Nilai');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Nilai');
    }

    public function replicate(AuthUser $authUser, Nilai $nilai): bool
    {
        return $authUser->can('Replicate:Nilai');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Nilai');
    }

}
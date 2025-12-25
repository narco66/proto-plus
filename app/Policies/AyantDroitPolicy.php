<?php

namespace App\Policies;

use App\Models\AyantDroit;
use App\Models\User;

class AyantDroitPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('ayants_droit.view');
    }

    public function view(User $user, AyantDroit $ayantDroit): bool
    {
        return $ayantDroit->fonctionnaire_user_id === $user->id 
            || $user->can('ayants_droit.view');
    }

    public function create(User $user): bool
    {
        return $user->can('ayants_droit.create');
    }

    public function update(User $user, AyantDroit $ayantDroit): bool
    {
        return $ayantDroit->fonctionnaire_user_id === $user->id 
            && $user->can('ayants_droit.edit');
    }

    public function delete(User $user, AyantDroit $ayantDroit): bool
    {
        return $ayantDroit->fonctionnaire_user_id === $user->id 
            && $user->can('ayants_droit.delete');
    }
}



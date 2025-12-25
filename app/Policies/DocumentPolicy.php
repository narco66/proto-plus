<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;

class DocumentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('documents.view');
    }

    public function view(User $user, Document $document): bool
    {
        // Documents confidentiels nécessitent permission spéciale
        if ($document->confidentiel) {
            return $user->can('documents.view_sensitive');
        }
        
        return $user->can('documents.view');
    }

    public function create(User $user): bool
    {
        return $user->can('documents.upload');
    }

    public function update(User $user, Document $document): bool
    {
        return $user->can('documents.upload') 
            && $document->created_by === $user->id;
    }

    public function delete(User $user, Document $document): bool
    {
        return $user->can('documents.delete') 
            && $document->created_by === $user->id;
    }

    public function download(User $user, Document $document): bool
    {
        // Documents confidentiels nécessitent permission spéciale
        if ($document->confidentiel) {
            return $user->can('documents.view_sensitive');
        }
        
        return $user->can('documents.download');
    }
}

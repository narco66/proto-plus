<?php

namespace App\Services;

use App\Models\Demande;
use App\Models\Document;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentService
{
    public function handleUploads(Demande $demande, array $documents, int $userId): void
    {
        foreach ($documents as $documentData) {
            $file = $documentData['file'] ?? null;
            if (!($file instanceof UploadedFile)) {
                continue;
            }

            $this->createDocument($demande, $documentData, $file, $userId);
        }
    }

    protected function createDocument(Demande $demande, array $attributes, UploadedFile $file, int $userId): void
    {
        $disk = config('filesystems.default', 'local');
        $directory = 'documents/' . $demande->reference;
        $slug = Str::slug($attributes['titre'] ?? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $filename = sprintf('%s-%s.%s', $slug ?: 'document', now()->format('YmdHis'), $file->getClientOriginalExtension());
        $path = Storage::disk($disk)->putFileAs($directory, $file, $filename);

        Document::create([
            'demande_id' => $demande->id,
            'beneficiaire_type' => $attributes['beneficiaire_type'] ?? 'fonctionnaire',
            'beneficiaire_id' => $attributes['beneficiaire_id'] ?? $demande->demandeur_user_id,
            'type_document' => $attributes['type_document'] ?? 'autre',
            'nom_fichier' => $file->getClientOriginalName(),
            'chemin_fichier' => (string) $path,
            'mime_type' => $file->getClientMimeType(),
            'taille' => $file->getSize(),
            'checksum' => hash_file('sha256', $file->getRealPath()),
            'titre' => $attributes['titre'] ?? $file->getClientOriginalName(),
            'description' => $attributes['description'] ?? '',
            'confidentiel' => $attributes['confidentiel'] ?? false,
            'version' => 1,
            'created_by' => $userId,
        ]);
    }
}

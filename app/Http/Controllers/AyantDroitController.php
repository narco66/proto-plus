<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAyantDroitRequest;
use App\Http\Requests\UpdateAyantDroitRequest;
use App\Models\AyantDroit;
use Illuminate\Http\Request;

class AyantDroitController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = $request->user();

        // Fonctionnaires voient leurs propres ayants droit
        if ($user->can('ayants_droit.view')) {
            $query = AyantDroit::with('fonctionnaire')
                ->where('fonctionnaire_user_id', $user->id)
                ->latest();
        } else {
            $query = AyantDroit::where('fonctionnaire_user_id', $user->id)->latest();
        }

        $ayantsDroit = $query->paginate(15);

        return view('ayants-droit.index', compact('ayantsDroit'));
    }

    public function create(Request $request)
    {
        if (!$request->user()->can('ayants_droit.create')) {
            abort(403, 'Vous n\'avez pas les permissions nécessaires pour créer un ayant droit.');
        }

        return view('ayants-droit.create');
    }

    public function store(StoreAyantDroitRequest $request)
    {
        try {
            $ayantDroit = AyantDroit::create([
                'fonctionnaire_user_id' => $request->user()->id,
                ...$request->validated(),
            ]);

            return redirect()
                ->route('ayants-droit.index')
                ->with('success', 'Ayant droit créé avec succès.');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création d\'un ayant droit', [
                'user_id' => $request->user()->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création. Veuillez réessayer.');
        }
    }

    public function show(Request $request, AyantDroit $ayantDroit)
    {
        $ayantDroit = $ayantDroit instanceof AyantDroit ? $ayantDroit : AyantDroit::findOrFail($ayantDroit);
        if ($ayantDroit->fonctionnaire_user_id != $request->user()->id) {
            abort(403, 'Vous n\'avez pas les permissions nécessaires pour consulter cet ayant droit.');
        }

        $ayantDroit->load('fonctionnaire');

        return view('ayants-droit.show', compact('ayantDroit'));
    }

    public function edit(Request $request, AyantDroit $ayantDroit)
    {
        $ayantDroit = $ayantDroit instanceof AyantDroit ? $ayantDroit : AyantDroit::findOrFail($ayantDroit);
        if ($ayantDroit->fonctionnaire_user_id != $request->user()->id) {
            abort(403, 'Vous n\'avez pas les permissions nécessaires pour modifier cet ayant droit.');
        }

        return view('ayants-droit.edit', compact('ayantDroit'));
    }

    public function update(UpdateAyantDroitRequest $request, AyantDroit $ayantDroit)
    {
        try {
            $ayantDroit = $ayantDroit instanceof AyantDroit ? $ayantDroit : AyantDroit::findOrFail($ayantDroit);
            if ($ayantDroit->fonctionnaire_user_id != $request->user()->id) {
                abort(403, 'Vous n\'avez pas les permissions nécessaires pour modifier cet ayant droit.');
            }

            $ayantDroit->update($request->validated());

            return redirect()
                ->route('ayants-droit.show', $ayantDroit->id)
                ->with('success', 'Ayant droit mis à jour avec succès.');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la mise à jour d\'un ayant droit', [
                'ayant_droit_id' => $ayantDroit->id ?? null,
                'user_id' => $request->user()->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la mise à jour. Veuillez réessayer.');
        }
    }

    public function destroy(Request $request, AyantDroit $ayantDroit)
    {
        $ayantDroit = $ayantDroit instanceof AyantDroit ? $ayantDroit : AyantDroit::findOrFail($ayantDroit);
        if ($ayantDroit->fonctionnaire_user_id != $request->user()->id) {
            abort(403, 'Vous n\'avez pas les permissions nécessaires pour supprimer cet ayant droit.');
        }

        try {
            $ayantDroit->delete();

            return redirect()
                ->route('ayants-droit.index')
                ->with('success', 'Ayant droit supprimé avec succès.');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la suppression d\'un ayant droit', [
                'ayant_droit_id' => $ayantDroit->id ?? null,
                'user_id' => $request->user()->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'Une erreur est survenue lors de la suppression. Veuillez réessayer.');
        }
    }
}

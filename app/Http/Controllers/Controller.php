<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Retourner une réponse de succès
     */
    protected function successResponse(string $message, $data = null, string $route = null, int $statusCode = 200)
    {
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $data,
            ], $statusCode);
        }

        $redirect = $route ? redirect()->route($route) : redirect()->back();
        return $redirect->with('success', $message);
    }

    /**
     * Retourner une réponse d'erreur
     */
    protected function errorResponse(string $message, int $statusCode = 422, string $route = null)
    {
        if (request()->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message,
            ], $statusCode);
        }

        $redirect = $route ? redirect()->route($route) : redirect()->back();
        return $redirect->withInput()->with('error', $message);
    }

    /**
     * Gérer une exception et retourner une réponse appropriée
     */
    protected function handleException(\Throwable $e, string $defaultMessage = 'Une erreur est survenue.', string $route = null)
    {
        \Log::error('Exception dans le contrôleur', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);

        // Si c'est une exception personnalisée, utiliser son message
        if ($e instanceof \App\Exceptions\WorkflowException || 
            $e instanceof \App\Exceptions\DemandeException || 
            $e instanceof \App\Exceptions\PermissionException) {
            return $this->errorResponse($e->getMessage(), $e->getCode() ?: 422, $route);
        }

        // Sinon, utiliser le message par défaut
        return $this->errorResponse($defaultMessage, 500, $route);
    }
}

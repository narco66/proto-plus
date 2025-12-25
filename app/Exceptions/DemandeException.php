<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DemandeException extends Exception
{
    protected $statusCode = 422;

    public function __construct(
        string $message = 'Erreur lors du traitement de la demande',
        int $code = 0,
        ?\Throwable $previous = null,
        int $statusCode = 422
    ) {
        parent::__construct($message, $code, $previous);
        $this->statusCode = $statusCode;
    }

    /**
     * Render the exception as an HTTP response.
     */
    public function render(Request $request): Response|JsonResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $this->getMessage(),
                'error' => 'demande_error',
            ], $this->statusCode);
        }

        return redirect()
            ->back()
            ->withInput()
            ->with('error', $this->getMessage());
    }

    /**
     * Report the exception.
     */
    public function report(): bool
    {
        \Log::warning('DemandeException: ' . $this->getMessage(), [
            'exception' => $this,
        ]);

        return false;
    }
}

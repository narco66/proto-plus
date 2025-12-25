<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PermissionException extends Exception
{
    protected $statusCode = 403;

    public function __construct(
        string $message = 'Vous n\'avez pas les permissions nécessaires pour effectuer cette action',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
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
                'error' => 'permission_denied',
            ], $this->statusCode);
        }

        return redirect()
            ->back()
            ->with('error', $this->getMessage())
            ->setStatusCode($this->statusCode);
    }

    /**
     * Report the exception.
     */
    public function report(): bool
    {
        \Log::warning('PermissionException: ' . $this->getMessage(), [
            'user_id' => auth()->id(),
            'exception' => $this,
        ]);

        return false;
    }
}

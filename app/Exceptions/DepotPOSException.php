<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class DepotPOSException extends Exception
{
    protected $context;
    protected $logLevel;

    public function __construct(string $message = "", int $code = 0, ?Exception $previous = null, array $context = [], string $logLevel = 'error')
    {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
        $this->logLevel = $logLevel;
    }

    /**
     * Report the exception.
     */
    public function report(): void
    {
        Log::{$this->logLevel}('Depot POS Exception: ' . $this->getMessage(), array_merge([
            'exception' => get_class($this),
            'code' => $this->getCode(),
            'file' => $this->getFile(),
            'line' => $this->getLine(),
        ], $this->context));
    }

    /**
     * Render the exception as an HTTP response.
     */
    public function render(Request $request): Response
    {
        if ($request->expectsJson()) {
            return response()->json([
                'error' => $this->getMessage(),
                'code' => $this->getCode() ?: 500
            ], $this->getCode() ?: 500);
        }

        return response()->view('errors.depot-pos', [
            'message' => $this->getMessage(),
            'code' => $this->getCode() ?: 500
        ], $this->getCode() ?: 500);
    }

    /**
     * Set additional context for logging.
     */
    public function setContext(array $context): self
    {
        $this->context = array_merge($this->context, $context);
        return $this;
    }

    /**
     * Set log level for this exception.
     */
    public function setLogLevel(string $level): self
    {
        $this->logLevel = $level;
        return $this;
    }
}
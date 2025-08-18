<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogApiRequests
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Log the incoming request
        Log::info('API Request', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'headers' => $this->getFilteredHeaders($request),
            'body' => $request->all(),
            'timestamp' => now()->toISOString(),
        ]);

        $startTime = microtime(true);
        
        $response = $next($request);
        
        $endTime = microtime(true);
        $duration = round(($endTime - $startTime) * 1000, 2); // Convert to milliseconds

        // Log the response
        Log::info('API Response', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'status' => $response->getStatusCode(),
            'duration_ms' => $duration,
            'content_length' => strlen($response->getContent()),
            'response_preview' => substr($response->getContent(), 0, 500), // First 500 chars
            'timestamp' => now()->toISOString(),
        ]);

        return $response;
    }

    /**
     * Filter sensitive headers from logging
     */
    private function getFilteredHeaders(Request $request): array
    {
        $headers = $request->headers->all();
        
        // Remove sensitive headers
        unset($headers['authorization']);
        unset($headers['cookie']);
        unset($headers['x-csrf-token']);
        
        return $headers;
    }
}
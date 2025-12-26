<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyHmacMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $client = $request->header('X-Client');
        $timestamp = $request->header('X-Timestamp');
        $signature = $request->header('X-Signature');

        if (! $client || ! $timestamp || ! $signature) {
            abort(401, 'Missing HMAC headers');
        }

        if (abs(time() - (int) $timestamp) > 300) {
            abort(401, 'Request expired');
        }

        $secret = config("hmac.clients.$client");

        if (! $secret) {
            abort(401, 'Invalid client');
        }

        $bodyHash = hash('sha256', $request->getContent());

        $payload = implode('|', [
            $request->method(),
            $request->path(),
            $timestamp,
            $bodyHash,
        ]);

        $expected = hash_hmac('sha256', $payload, $secret);

        if (! hash_equals($expected, $signature)) {
            abort(401, 'Invalid signature');
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockIp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Define the IP addresses you want to block
        $blockedIps = [
            // '192.168.1.100', // Example single IP
            // '203.0.113.0/24', // Example CIDR range
        ];

        // Get the client's IP address
        // IMPORTANT: If you are behind a proxy/load balancer/CDN,
        // you MUST configure Laravel to trust the proxy.
        // See Step 4 below for how to do this.
        $clientIp = $request->ip();

        // Check if the client's IP is in the blocked list or range
        foreach ($blockedIps as $blockedIp) {
            if (str_contains($blockedIp, '/')) {
                // It's a CIDR range
                if ($this->ip_in_range($clientIp, $blockedIp)) {
                    abort(403, 'Access Denied: Your IP address is blocked.');
                }
            } else {
                // It's a single IP
                if ($clientIp === $blockedIp) {
                    abort(403, 'Access Denied: Your IP address is blocked.');
                }
            }
        }

        return $next($request);
    }

    /**
     * Helper function to check if an IP is within a CIDR range.
     * Source: https://stackoverflow.com/questions/5941126/how-to-check-if-an-ip-address-is-in-a-particular-range-in-php
     */
    private function ip_in_range($ip, $range )
    {
        if (str_contains($range, '/')) {
            // IP range specified with CIDR
            list($range, $netmask) = explode('/', $range, 2);
            $range_decimal = ip2long($range);
            $ip_decimal = ip2long($ip);
            $wildcard_decimal = pow(2, (32 - $netmask)) - 1;
            $netmask_decimal = ~$wildcard_decimal;
            return (($ip_decimal & $netmask_decimal) == ($range_decimal & $netmask_decimal));
        } else {
            // Not a CIDR, assume single IP
            return ($ip == $range);
        }
    }
}

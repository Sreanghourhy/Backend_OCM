<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WhitelistIp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Define the IP addresses you want to ALLOW
        $allowedIps = explode( ',' , env( 'ALLOWED_IPS' , '' ) );
        $allowedIps = is_array( $allowedIps ) && !empty( $allowedIps ) ? $allowedIps : [] ;
        // $allowedIps = 
        // [
	    //     '172.16.20.1', // Server itself
        //     '172.16.20.2', // Server itself
        //     '172.16.20.3', // Server itself
        //     '172.16.20.4', // Server itself
	    //     # '::1',            // IPv6 localhost
        //     # '203.0.113.10',   // Example: Your specific public IP
        //     # '192.168.1.0/24', // Example: Your office network range
        //     # '10.0.0.0/8',     // Example: Your VPN range
        // ];

        // Get the client's IP address
        // IMPORTANT: If you are behind a proxy/load balancer/CDN,
        // you MUST configure Laravel to trust the proxy.
        // See Step 4 below for how to do this.
        $clientIp = $request->ip();

        // Check if the client's IP is in the allowed list or range
        $isAllowed = false;
        foreach ($allowedIps as $allowedIp) {
            if (str_contains($allowedIp, '/')) {
                // It's a CIDR range
                if ($this->ip_in_range($clientIp, $allowedIp)) {
                    $isAllowed = true;
                    break;
                }
            } else {
                // It's a single IP
                if ($clientIp === $allowedIp) {
                    $isAllowed = true;
                    break;
                }
            }
        }

        // If the IP is NOT allowed, abort with a 403 Forbidden response
        if (!$isAllowed) {
            abort(403, 'លេខ IP របស់អ្នកមិនមានសិទ្ធិគ្រប់គ្រាន់ឡើយ។');
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

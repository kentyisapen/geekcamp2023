<?php

namespace App\Http\Middleware;

use App\Models\IpLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\IpUtils;

class IpIsValid
{
    private const ALLOWED_IPS = [
        '127.0.0.1',
        '172.17.0.1/16'
    ];
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (config('app.env') === 'local' || $this->isAllowedIp($request->ip())) {
            return $next($request);
        }

        $ipLog = new IpLog();

        $count = $ipLog
        ->where('ip', '=', $request->ip())
        ->where('created_at', '>=', now()
        ->subMinutes(10))
        ->count();

        if ($count >= 5) {
            return redirect("/");
        }

        $ipLog->create([
            "ip" => $request->ip()
        ]);

        return $next($request);
    }

    private function isAllowedIp(string $ip): bool
    {
        return IpUtils::checkIp($ip, self::ALLOWED_IPS);
    }
}

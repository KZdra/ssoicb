<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Request;

class AuditLogger extends BaseService
{
    public static function log(?int $userId, string $action, ?string $description = null)
    {
        AuditLog::create([
            'user_id' => $userId,
            'ip_address' => Request::ip(),
            'browser' => Request::header('User-Agent'),
            'operating_system' => self::getOS(Request::header('User-Agent')),
            'action' => $action,
            'description' => $description,
        ]);
    }

    private static function getOS(?string $userAgent): string
    {
        if (!$userAgent) return 'Unknown';
        
        $osPlatform = "Unknown OS";
        $osArray = array(
            '/windows nt 10/i'      =>  'Windows 10',
            '/windows nt 6.3/i'     =>  'Windows 8.1',
            '/windows nt 6.2/i'     =>  'Windows 8',
            '/windows nt 6.1/i'     =>  'Windows 7',
            '/macintosh|mac os x/i' =>  'Mac OS X',
            '/mac_powerpc/i'        =>  'Mac OS 9',
            '/linux/i'              =>  'Linux',
            '/ubuntu/i'             =>  'Ubuntu',
            '/iphone/i'             =>  'iPhone',
            '/ipod/i'               =>  'iPod',
            '/ipad/i'               =>  'iPad',
            '/android/i'            =>  'Android',
            '/blackberry/i'         =>  'BlackBerry',
            '/webos/i'              =>  'Mobile'
        );

        foreach ($osArray as $regex => $value) {
            if (preg_match($regex, $userAgent)) {
                $osPlatform = $value;
            }
        }
        return $osPlatform;
    }
}

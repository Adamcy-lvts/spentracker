<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'last_login_at',
        'last_login_ip',
        'last_login_user_agent',
        'last_login_location',
        'last_login_latitude',
        'last_login_longitude',
        'last_login_city',
        'last_login_country',
        'last_login_device_type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * Get the categories for the user.
     */
    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    /**
     * Update user login tracking information
     */
    public function updateLoginTracking($request = null)
    {
        if (!$request) {
            $request = request();
        }

        // Get device type from user agent
        $deviceType = $this->getDeviceType($request->userAgent());
        
        // Get location data
        $locationData = $this->processLocationData($request, $deviceType);

        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
            'last_login_user_agent' => $request->userAgent(),
            'last_login_device_type' => $deviceType,
            'last_login_location' => $locationData['display_location'],
            'last_login_latitude' => $locationData['latitude'],
            'last_login_longitude' => $locationData['longitude'],
            'last_login_city' => $locationData['city'],
            'last_login_country' => $locationData['country'],
        ]);
    }

    /**
     * Determine device type from user agent
     */
    private function getDeviceType($userAgent)
    {
        if (stripos($userAgent, 'Android') !== false) {
            return 'Android';
        }
        if (stripos($userAgent, 'iPhone') !== false || stripos($userAgent, 'iPad') !== false) {
            return 'iOS';
        }
        if (stripos($userAgent, 'Mobile') !== false) {
            return 'Mobile Web';
        }
        return 'Web';
    }

    /**
     * Process location data from request (supports mobile GPS coordinates)
     */
    private function processLocationData($request, $deviceType)
    {
        $locationData = [
            'display_location' => null,
            'latitude' => null,
            'longitude' => null,
            'city' => null,
            'country' => null,
        ];

        // Check for GPS coordinates from mobile apps
        if ($request->has('latitude') && $request->has('longitude')) {
            $locationData['latitude'] = $request->input('latitude');
            $locationData['longitude'] = $request->input('longitude');
            $locationData['city'] = $request->input('city', 'Unknown City');
            $locationData['country'] = $request->input('country', 'Unknown Country');
            
            $displayLocation = $locationData['city'] . ', ' . $locationData['country'];
            if ($deviceType === 'Android' || $deviceType === 'iOS') {
                $displayLocation .= ' (GPS)';
            }
            $locationData['display_location'] = $displayLocation;
        } else {
            // Fallback to IP-based location detection
            $locationData['display_location'] = $this->getLocationFromIp($request->ip(), $deviceType);
        }

        return $locationData;
    }

    /**
     * Get location from IP address (basic implementation)
     * In production, consider using services like ipinfo.io, geoip, etc.
     */
    private function getLocationFromIp($ip, $deviceType = 'Web')
    {
        if ($ip === '127.0.0.1' || $ip === '::1' || $ip === 'localhost') {
            return 'Local Development';
        }
        
        // For production, you might want to use a geolocation service like:
        // - ipinfo.io
        // - maxmind.com
        // - ipgeolocation.io
        // For now, we'll return a basic fallback based on device type
        return $deviceType . ' Browser';
    }
}

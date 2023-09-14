<?php

namespace App\System\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

class Domain
{
    /**
     * @return Collection<int, string>
     */
    public static function getDomainName() : Collection
    {
        return collect(static::getFinder()->directories()->depth(0))
            ->reject(function ($item) {
                return $item->getBasename() === 'System';
            })
            ->map(function ($item) {
                return $item->getBasename();
            });
    }

    /**
     * @return Collection<int, string>
     */
    public static function getServiceProviders() : Collection
    {
        return self::getFilteredClasses()
            ->reject(function ($class) {
                return str_contains($class, 'App\\Domain\\System\\');
            })
            ->filter(function ($class) : bool {
                return is_subclass_of($class, ServiceProvider::class);
            })
            ->values();
    }

    /**
     * @return array<int, string>
     */
    public static function getMigrationsPath() : array
    {
        return static::getDomainName()->filter(function ($domain, string $path) : bool {
            if ($domain === 'System') {
                return false;
            }

            return is_dir($path . '/Database/Migrations');
        })
            ->flip()
            ->toArray();
    }

    /**
     * @return Collection
     */
    private static function getFilteredClasses() : Collection
    {
        return collect(static::getFinder()->files()->name('*.php'))->map(function (SplFileInfo $spl) {
            return path_to_class($spl);
        });
    }

    /**
     * @return Finder
     */
    private static function getFinder() : Finder
    {
        return (new Finder)->in(base_path('app/Domain'));
    }
}

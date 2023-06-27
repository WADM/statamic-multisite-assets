<?php

namespace WADM\MultisiteForAssets;

use Statamic\Events\AssetContainerBlueprintFound;
use Statamic\Providers\AddonServiceProvider;
use Statamic\Statamic;
use Wadm\MultisiteForAssets\Fieldtypes\Alt;
use Wadm\MultisiteForAssets\Listeners\AppendAltFieldListener;

class ServiceProvider extends AddonServiceProvider
{
    protected $vite = [
        'input' => [
            'resources/js/check-for-site.js',
        ],
        'publicDirectory' => 'resources/dists'
    ];

    protected $listen = [
        AssetContainerBlueprintFound::class => [
            AppendAltFieldListener::class,
        ],
    ];

    public function bootAddon()
    {
        //
    }
}

<?php

namespace Theafolayan\ListmonkLaravel\Tests;

use Illuminate\Support\ServiceProvider;
use Theafolayan\ListmonkLaravel\Facades\Listmonk as ListmonkFacade;
use Theafolayan\ListmonkLaravel\Listmonk;
use Theafolayan\ListmonkLaravel\ListmonkServiceProvider;

class ListmonkServiceProviderTest extends TestCase
{
    public function testItBindsTheServiceAsASingleton()
    {
        $service = $this->app->make('listmonk');

        $this->assertInstanceOf(Listmonk::class, $service);
        $this->assertSame($service, $this->app->make('listmonk'));
    }

    public function testTheFacadeResolvesTheService()
    {
        $this->assertInstanceOf(Listmonk::class, ListmonkFacade::getFacadeRoot());
    }

    public function testItMergesTheDefaultConfig()
    {
        $defaults = require __DIR__ . '/../src/Config/listmonk.php';

        $this->assertSame(['base_url', 'api_user', 'api_token'], array_keys($defaults));
        $this->assertSame('https://listmonk.test', config('listmonk.base_url'));
    }

    public function testTheConfigFileIsPublishable()
    {
        $paths = ServiceProvider::pathsToPublish(ListmonkServiceProvider::class, 'listmonk-config');

        $this->assertContains(config_path('listmonk.php'), $paths);
    }
}

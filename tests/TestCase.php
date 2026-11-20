<?php

namespace Theafolayan\ListmonkLaravel\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Theafolayan\ListmonkLaravel\ListmonkServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [ListmonkServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('listmonk.base_url', 'https://listmonk.test');
        $app['config']->set('listmonk.api_user', 'api-user');
        $app['config']->set('listmonk.api_token', 'api-token');
    }
}

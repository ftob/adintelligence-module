<?php
namespace AdIntelligence\Client\Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;

trait CreateApplication
{
    /**
     * Creates the application.
     *
     * @return Application
     */
    public function createApplication()
    {

        $app = new Application(
            realpath(__DIR__.'/../')
        );

        return $app;
    }
}
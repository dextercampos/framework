<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Framework\Providers;

use EoneoPay\ApiFormats\Interfaces\RequestEncoderGuesserInterface;
use EoneoPay\External\ORM\Interfaces\EntityManagerInterface;
use EoneoPay\Framework\Providers\FrameworkServiceProvider;
use Laravel\Lumen\Application;
use Tests\EoneoPay\Framework\TestCases\TestCase;

class FrameworkServiceProviderTest extends TestCase
{
    /**
     * Test provider register other packages service providers correctly.
     *
     * @return void
     *
     * @throws \Illuminate\Container\EntryNotFoundException
     */
    public function testRegister(): void
    {
        $tests = [
            RequestEncoderGuesserInterface::class,
            EntityManagerInterface::class
        ];

        $application = new Application();
        /** @noinspection PhpParamsInspection Due to Laravel/Lumen working way */
        (new FrameworkServiceProvider($application))->register();

        foreach ($tests as $serviceId) {
            self::assertTrue($application->has($serviceId));
        }
    }
}
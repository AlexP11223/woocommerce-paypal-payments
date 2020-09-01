<?php
/**
 * The API module.
 *
 * @package Inpsyde\PayPalCommerce\ApiClient
 */

declare(strict_types=1);

namespace Inpsyde\PayPalCommerce\ApiClient;

use Dhii\Container\ServiceProvider;
use Dhii\Modular\Module\Exception\ModuleExceptionInterface;
use Dhii\Modular\Module\ModuleInterface;
use Interop\Container\ServiceProviderInterface;
use Psr\Container\ContainerInterface;

/**
 * Class ApiModule
 */
class ApiModule implements ModuleInterface {

	/**
	 * Sets up the module.
	 *
	 * @return ServiceProviderInterface
	 */
	public function setup(): ServiceProviderInterface {
		return new ServiceProvider(
			require __DIR__ . '/../services.php',
			require __DIR__ . '/../extensions.php'
		);
	}

	/**
	 * Runs the module.
	 *
	 * @param ContainerInterface $container The container.
	 */
	public function run( ContainerInterface $container ) {
	}
}

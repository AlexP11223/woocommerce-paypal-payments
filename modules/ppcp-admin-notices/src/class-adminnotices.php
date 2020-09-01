<?php
/**
 * The admin notice module.
 *
 * @package Inpsyde\PayPalCommerce\Button
 */

declare(strict_types=1);

namespace Inpsyde\PayPalCommerce\AdminNotices;

use Dhii\Container\ServiceProvider;
use Dhii\Modular\Module\ModuleInterface;
use Interop\Container\ServiceProviderInterface;
use Psr\Container\ContainerInterface;

/**
 * Class AdminNotices
 */
class AdminNotices implements ModuleInterface {

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
		add_action(
			'admin_notices',
			function() use ( $container ) {
				$renderer = $container->get( 'admin-notices.renderer' );
				$renderer->render();
			}
		);
	}
}

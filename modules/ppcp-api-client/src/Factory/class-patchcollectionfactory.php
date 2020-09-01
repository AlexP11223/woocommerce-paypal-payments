<?php
/**
 * The PatchCollection factory.
 *
 * @package Inpsyde\PayPalCommerce\ApiClient\Factory
 */

declare(strict_types=1);

namespace Inpsyde\PayPalCommerce\ApiClient\Factory;

use Inpsyde\PayPalCommerce\ApiClient\Entity\Order;
use Inpsyde\PayPalCommerce\ApiClient\Entity\Patch;
use Inpsyde\PayPalCommerce\ApiClient\Entity\PatchCollection;
use Inpsyde\PayPalCommerce\ApiClient\Entity\PurchaseUnit;

/**
 * Class PatchCollectionFactory
 */
class PatchCollectionFactory {


	/**
	 * Creates a Patch Collection by comparing two orders.
	 *
	 * @param Order $from The inital order.
	 * @param Order $to The target order.
	 *
	 * @return PatchCollection
	 */
	public function from_orders( Order $from, Order $to ): PatchCollection {
		$all_patches  = array();
		$all_patches += $this->purchase_units( $from->purchase_units(), $to->purchase_units() );

		return new PatchCollection( ...$all_patches );
	}

	/**
	 * Returns patches from purchase units diffs.
	 *
	 * @param PurchaseUnit[] $from The Purchase Units to start with.
	 * @param PurchaseUnit[] $to The Purchase Units to end with after patches where applied.
	 *
	 * @return Patch[]
	 */
	private function purchase_units( array $from, array $to ): array {
		$patches = array();

		$path = '/purchase_units';
		foreach ( $to as $purchase_unit_to ) {
			$needs_update = ! count(
				array_filter(
					$from,
					static function ( PurchaseUnit $unit ) use ( $purchase_unit_to ): bool {
                        //phpcs:disable WordPress.PHP.StrictComparisons.LooseComparison
						// Loose comparison needed to compare two objects.
						return $unit == $purchase_unit_to;
                        //phpcs:enable WordPress.PHP.StrictComparisons.LooseComparison
					}
				)
			);
			$needs_update = true;
			if ( ! $needs_update ) {
				continue;
			}
			$purchase_unit_from = current(
				array_filter(
					$from,
					static function ( PurchaseUnit $unit ) use ( $purchase_unit_to ): bool {
						return $purchase_unit_to->reference_id() === $unit->reference_id();
					}
				)
			);
			$operation          = $purchase_unit_from ? 'replace' : 'add';
			$value              = $purchase_unit_to->to_array();
			$patches[]          = new Patch(
				$operation,
				$path . "/@reference_id=='" . $purchase_unit_to->reference_id() . "'",
				$value
			);
		}

		return $patches;
	}
}

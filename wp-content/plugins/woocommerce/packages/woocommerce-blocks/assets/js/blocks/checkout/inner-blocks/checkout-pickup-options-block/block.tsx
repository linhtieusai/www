/**
 * External dependencies
 */
import { _n, __ } from '@wordpress/i18n';
import {
	useState,
	useEffect,
	useCallback,
	createInterpolateElement,
} from '@wordpress/element';
import { useShippingData } from '@woocommerce/base-context/hooks';
import { getCurrencyFromPriceResponse } from '@woocommerce/price-format';
import FormattedMonetaryAmount from '@woocommerce/base-components/formatted-monetary-amount';
import { decodeEntities } from '@wordpress/html-entities';
import { getSetting } from '@woocommerce/settings';
import { Icon, mapMarker } from '@wordpress/icons';
import RadioControl from '@woocommerce/base-components/radio-control';
import type { RadioControlOption } from '@woocommerce/base-components/radio-control/types';
import { CartShippingPackageShippingRate } from '@woocommerce/types';
import { isPackageRateCollectable } from '@woocommerce/base-utils';

/**
 * Internal dependencies
 */
import './style.scss';

const getPickupLocation = (
	option: CartShippingPackageShippingRate
): string => {
	if ( option?.meta_data ) {
		const match = option.meta_data.find(
			( meta ) => meta.key === 'pickup_location'
		);
		return match ? match.value : '';
	}
	return '';
};

const getPickupAddress = (
	option: CartShippingPackageShippingRate
): string => {
	if ( option?.meta_data ) {
		const match = option.meta_data.find(
			( meta ) => meta.key === 'pickup_address'
		);
		return match ? match.value : '';
	}
	return '';
};

const getPickupDetails = (
	option: CartShippingPackageShippingRate
): string => {
	if ( option?.meta_data ) {
		const match = option.meta_data.find(
			( meta ) => meta.key === 'pickup_details'
		);
		return match ? match.value : '';
	}
	return '';
};

const renderPickupLocation = (
	option: CartShippingPackageShippingRate,
	packageCount: number
): RadioControlOption => {
	const priceWithTaxes = getSetting( 'displayCartPricesIncludingTax', false )
		? option.price + option.taxes
		: option.price;
	const location = getPickupLocation( option );
	const address = getPickupAddress( option );
	const details = getPickupDetails( option );

	return {
		value: option.rate_id,
		label: location
			? decodeEntities( location )
			: decodeEntities( option.name ),
		secondaryLabel:
			parseInt( priceWithTaxes, 10 ) > 0 ? (
				createInterpolateElement(
					/* translators: %1$s name of the product (ie: Sunglasses), %2$d number of units in the current cart package */
					_n(
						'<price/>',
						'<price/> x <packageCount/> packages',
						packageCount,
						'woo-gutenberg-products-block'
					),
					{
						price: (
							<FormattedMonetaryAmount
								currency={ getCurrencyFromPriceResponse(
									option
								) }
								value={ priceWithTaxes }
							/>
						),
						packageCount: <>{ packageCount }</>,
					}
				)
			) : (
				<em>{ __( 'free', 'woo-gutenberg-products-block' ) }</em>
			),
		description: decodeEntities( details ),
		secondaryDescription: address ? (
			<>
				<Icon
					icon={ mapMarker }
					className="wc-block-editor-components-block-icon"
				/>
				{ decodeEntities( address ) }
			</>
		) : undefined,
	};
};

const Block = (): JSX.Element | null => {
	const { shippingRates, selectShippingRate } = useShippingData();

	// Get pickup locations from the first shipping package.
	const pickuplocations = ( shippingRates[ 0 ]?.shipping_rates || [] ).filter(
		isPackageRateCollectable
	);

	const [ selectedOption, setSelectedOption ] = useState< string >(
		() => pickuplocations.find( ( rate ) => rate.selected )?.rate_id || ''
	);

	const onSelectRate = useCallback(
		( rateId: string ) => {
			selectShippingRate( rateId );
		},
		[ selectShippingRate ]
	);

	// Update the selected option if there is no rate selected on mount.
	useEffect( () => {
		if ( ! selectedOption && pickuplocations[ 0 ] ) {
			setSelectedOption( pickuplocations[ 0 ].rate_id );
			onSelectRate( pickuplocations[ 0 ].rate_id );
		}
	}, [ onSelectRate, pickuplocations, selectedOption ] );

	return (
		<RadioControl
			onChange={ ( value: string ) => {
				setSelectedOption( value );
				onSelectRate( value );
			} }
			selected={ selectedOption }
			options={ pickuplocations.map( ( location ) =>
				renderPickupLocation( location, shippingRates.length )
			) }
		/>
	);
};

export default Block;

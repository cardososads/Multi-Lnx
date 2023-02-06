/**
 * External dependencies
 */
import React, { useEffect, useState } from 'react';

/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;
import { dispatch, useSelect } from '@wordpress/data';

/**
 * Internal dependencies
 */
import { STORE_NAME } from '../../data/minify';
import './summary.scss';
import Tooltip from '../../components/sui-tooltip';
import Icon from '../../components/sui-icon';
import List from '../../components/sui-list';
import BoxSummary from '../../components/sui-box-summary';
import Tag from '../../components/sui-tag';
import Toggle from '../../components/sui-toggle';
import Button from '../../components/sui-button';
import HBAPIFetch from '../../api';

/**
 * MinifySummary functional component.
 *
 * @since 3.4.0
 * @param {Object} props
 * @return {JSX.Element} Summary meta box
 */
export const MinifySummary = ( props ) => {
	const api = new HBAPIFetch();
	const [ loading, setLoading ] = useState( true );
	const { cdn, safeMode, assets, hasResolved } = useSelect( ( select ) => {
		if ( ! select( STORE_NAME ).hasStartedResolution( 'getOptions' ) ) {
			select( STORE_NAME ).getOptions();
		}

		return {
			cdn: select( STORE_NAME ).getOption( 'cdn' ),
			safeMode: select( STORE_NAME ).getOption( 'safeMode' ),
			assets: select( STORE_NAME ).getAssets(),
			hasResolved: select( STORE_NAME ).hasFinishedResolution( 'getOptions' ) && select( STORE_NAME ).hasFinishedResolution( 'getAssets' ),
		};
	}, [] );

	/**
	 * Sync loading state with resolver.
	 *
	 * @since 3.4.0
	 */
	useEffect( () => {
		if ( loading && hasResolved ) {
			setTimeout( () => {
				setLoading( false );
			}, 250 );
		}
	}, [ hasResolved, setLoading ] );

	/**
	 * Get original/compressed sizes.
	 *
	 * @since 3.4.0
	 *
	 * @return {Array} Array of original and compressed sizes.
	 */
	const getSizes = () => {
		if ( undefined === assets.styles || undefined === assets.scripts ) {
			return [ 0, 0 ];
		}

		let originalSize = 0;
		let compressedSize = 0;

		Object.values( assets.styles ).forEach( ( asset ) => {
			if ( undefined !== asset?.compressedSize && asset?.compressedSize ) {
				originalSize += Number( asset?.originalSize );
				compressedSize += Number( asset?.compressedSize );
			}
		} );

		Object.values( assets.scripts ).forEach( ( asset ) => {
			if ( undefined !== asset?.compressedSize && asset?.compressedSize ) {
				originalSize += Number( asset?.originalSize );
				compressedSize += Number( asset?.compressedSize );
			}
		} );

		return [ originalSize, compressedSize ];
	};

	/**
	 * Get compressed size stats.
	 *
	 * @since 3.4.0
	 *
	 * @return {number} Compressed size value.
	 */
	const getCompressedSize = () => {
		const sizes = getSizes();
		return Math.round( sizes[ 1 ] );
	};

	/**
	 * Get number of enqueued files.
	 *
	 * @since 3.4.0
	 *
	 * @return {number} Number of assets
	 */
	const getEnqueuedFiles = () => {
		if ( undefined === assets.styles || undefined === assets.scripts ) {
			return 0;
		}

		let total = Object.keys( assets.styles ).length + Object.keys( assets.scripts ).length;

		if ( undefined !== assets.fonts ) {
			total += Object.keys( assets.fonts ).length;
		}

		return total;
	};

	/**
	 * Get percent optimized value.
	 *
	 * @since 3.4.0
	 *
	 * @return {number} Percent
	 */
	const getPercentOptimized = () => {
		const [ originalSize, compressedSize ] = getSizes();

		if ( 0 === originalSize || 0 === compressedSize ) {
			return 0;
		}

		return parseFloat( (100 - ( parseInt(compressedSize) * 100 / parseInt(originalSize) )).toFixed(1) );
	};

	/**
	 * Toggle CDN.
	 *
	 * @since 3.4.0
	 *
	 * @param {Object} e
	 */
	const toggleCDN = ( e ) => {

		api.post( 'minify_toggle_cdn', e.target.checked )
			.then( () => {
				dispatch( STORE_NAME ).invalidateResolution( 'getOptions' );
			} )
			.catch( window.console.log );
	};

	/**
	 * Get summary segment content.
	 *
	 * @return {JSX.Element} Summary segment
	 */
	const getSummarySegmentLeft = () => {
		const percentage = getPercentOptimized();

		return (
			<div className="sui-summary-details">
				{ 0 === percentage && 'basic' === props.wphbData.mode &&
					<Tooltip text={ __( 'All assets are auto-compressed', 'wphb' ) }>
						<Icon classes="sui-icon-check-tick sui-lg sui-success" />
					</Tooltip> }

				{ 0 === percentage && 'basic' !== props.wphbData.mode && String.fromCharCode( 8212 ) }

				{ 0 !== percentage &&
					<span className="sui-summary-large">
						{ percentage }%
					</span> }
				<span className="sui-summary-sub">{ __( 'Compression savings', 'wphb' ) }</span>
			</div>
		);
	};

	/**
	 * Get summary segment content.
	 *
	 * @return {JSX.Element} Summary segment
	 */
	const getSummarySegmentRight = () => {
		const compressedSize = getCompressedSize();
		let reduction = compressedSize.toString() + 'kb';

		if ( 'basic' === props.wphbData.mode && 0 === compressedSize ) {
			reduction = (
				<React.Fragment>
					{ __( 'Files are compressed', 'wphb' ) }
					<Icon classes="sui-icon-check-tick sui-md sui-success" />
				</React.Fragment>
			);
		}

		let cdnDetails;
		if ( ! props.wphbData.isMultisite ) {
			if ( props.wphbData.isMember ) {
				cdnDetails =
					<Tooltip text={ __( 'Enable WPMU DEV CDN', 'wphb' ) } classes={ [ 'sui-tooltip-top-right' ] }>
						<Toggle id="use_cdn" checked={ cdn && props.wphbData.isMember } disabled={ ! props.wphbData.isMember } onChange={ toggleCDN } />
					</Tooltip>;
			} else {
				cdnDetails =
					<Tooltip text={ __( 'Host your files on WPMU DEV’s blazing-fast CDN', 'wphb' ) } classes={ [ 'sui-tooltip-top-right' ] }>
						<Button url={ props.wphbData.links.cdnUpsell } target="blank" text={ __( 'Try CDN Free', 'wphb' ) } classes={ [ 'sui-button', 'sui-button-purple' ] } />
					</Tooltip>;
			}
		} else if ( cdn && props.wphbData.isMember ) {
			cdnDetails =
				<Tooltip text={ __( 'The Network Admin has the WPMU DEV CDN turned on', 'wphb' ) } classes={ [ 'sui-tooltip-top-right' ] }>
					<Icon classes="sui-icon-check-tick sui-md sui-info" />
				</Tooltip>;
		} else {
			cdnDetails =
				<Tooltip text={ __( 'The Network Admin has the WPMU DEV CDN turned off', 'wphb' ) } classes={ [ 'sui-tooltip-top-right' ] }>
					<Tag value={ __( 'Disabled', 'wphb' ) } type="disabled" />
				</Tooltip>;
		}

		const elements = [
			{
				label: __( 'Total files', 'wphb' ),
				details: getEnqueuedFiles(),
			},
			{
				label: __( 'Filesize reductions', 'wphb' ),
				details: reduction,
			},
			{
				label: <React.Fragment>
					{ __( 'WPMU DEV CDN', 'wphb' ) }
					{ ! props.wphbData.isMember && <Tag type="pro" value={ __( 'Pro', 'wphb' ) } /> }
				</React.Fragment>,
				details: cdnDetails,
			},
		];

		return <List elements={ elements } />;
	};

	if ( ! loading ) {
		return (
			<BoxSummary
				loading={ loading || ! hasResolved }
				brandingHeroImage={ props.wphbData.brandingHeroImage }
				hideBranding={ Boolean( props.wphbData.hideBranding ) }
				summarySegmentLeft={ getSummarySegmentLeft() }
				summarySegmentRight={ getSummarySegmentRight() }
			/>
		);
	} else {
		return null;
	}
};

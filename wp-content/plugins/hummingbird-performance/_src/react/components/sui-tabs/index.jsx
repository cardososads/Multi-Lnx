/* global SUI */

/**
 * External dependencies
 */
import React from 'react';
import classNames from 'classnames';

/**
 * Internal dependencies
 */
import Button from '../sui-button';

/**
 * Tabs component.
 */
export default class Tabs extends React.Component {
	/**
	 * Share UI actions need to be performed manually for elements.
	 * They should be done in this method.
	 *
	 * @see https://wpmudev.github.io/shared-ui/tabs/  For callback params.
	 */
	componentDidMount() {
		if ( ! this.props.sideTabs ) {
			SUI.tabs();
		}
	}

	/**
	 * Render component.
	 *
	 * @return {JSX.Element}  Select component.
	 */
	render() {
		const menuItems = Object.values( this.props.menu ).map( ( el ) => {
			const active = 'undefined' !== typeof el.checked && el.checked;
			return (
				<Button
					text={ el.title }
					id={ el.id + '-tab' }
					classes={ classNames( 'sui-tab-item', { active } ) }
					type="button"
					role="tab"
					aria-controls={ el.id + '-tab-content' }
					aria-selected={ active }
					tabIndex={ active ? '0' : '-1' }
					key={ el.id }
					onClick={ el.onClick }
				/>
			);
		} );

		const items = Object.values( this.props.tabs ).map( ( el, id ) => {
			const active = 'undefined' !== typeof el.active && el.active;
			const classes = classNames( { 'sui-tab-content': ! this.props.sideTabs }, { 'sui-tab-boxed': this.props.sideTabs }, { active } );
			return (
				<div
					role="tabpanel"
					tabIndex="0"
					id={ el.id + '-tab-content' }
					className={ classes }
					aria-labelledby={ el.id + '-tab' }
					hidden={ ! active }
					key={ id }
				>
					{ el.description && <div className="sui-description sui-margin-bottom">{ el.description }</div> }
					{ el.content }
				</div>
			);
		} );

		return (
			<div
				className={ classNames(
					{ 'sui-tabs': ! this.props.sideTabs },
					{ 'sui-tabs-flushed': this.props.flushed },
					{ 'sui-side-tabs': this.props.sideTabs },
					this.props.className,
				) }
			>
				<div role="tablist" className="sui-tabs-menu">
					{ menuItems }
				</div>
				{ ! window.lodash.isEmpty( this.props.tabs ) &&
					<div className="sui-tabs-content">{ items }</div> }
			</div>
		);
	}
}

Tabs.defaultProps = {
	tabs: {},
};

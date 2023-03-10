/**
 * External dependencies
 */
import React from 'react';
import classNames from 'classnames';

/**
 * Internal dependencies
 */
import Icon from '../sui-icon';

/**
 * Notice functional component.
 *
 * @param {Object} props         Component props.
 * @param {string} props.message Notice message.
 * @param {Array}  props.classes Array of extra classes to use.
 * @param {Object} props.content CTA content.
 * @return {JSX.Element} Notice component.
 * @class
 */
export default function Notice( { message, classes, content } ) {
	const combinedClasses = classNames( 'sui-notice', classes );

	return (
		<div className={ combinedClasses }>
			<div className="sui-notice-content">
				<div className="sui-notice-message">
					<Icon classes="sui-notice-icon sui-icon-info sui-md" />
					{ message && <p>{ message }</p> }
					{ content && <p>{ content }</p> }
				</div>
			</div>
		</div>
	);
}

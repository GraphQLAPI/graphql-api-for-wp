import { Component } from 'react';
import { __ } from '@wordpress/i18n';
import './style.scss';

class EditBlock extends Component {
	constructor( props ) {
		super( props );
		this.props = props;
	}

	render() {
		const { className } = this.props;
		return (
			<div className={ className }>
				<div className={ className+'__items' }>
					<div className={ className+'__item' }>
						<div className={ className+'__item_data' }>
							<div className={ className+'__item_data_for' }>
								<p className={ className+'__item_data__title' }><strong>{ __('Fields and directives:', 'graphql-api') }</strong></p>
								<p>For Lorem ipsum...</p>
							</div>
							<div className={ className+'__item_data_who' }>
								<p className={ className+'__item_data__title' }><strong>{ __('Who can access:', 'graphql-api') }</strong></p>
								<p>Who Lorem ipsum...</p>
								<p>Who Lorem ipsum...</p>
							</div>
						</div>
						<hr/>
					</div>
				</div>
			</div>
		);
	}
}

export default EditBlock;

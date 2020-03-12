import { Component } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { MultiSelectControl } from '../../../packages/components/src';
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
								<p className={ className+'__item_data__title' }><strong>{ __('Define access for:', 'graphql-api') }</strong></p>
								<p>{ __('Fields:', 'graphql-api') }</p>
								<div className="edit-post-manage-blocks-modal">
									<MultiSelectControl />
								</div>
								<p>{ __('Directives:', 'graphql-api') }</p>
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

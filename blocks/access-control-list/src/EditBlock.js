import { Component } from 'react';
import { __ } from '@wordpress/i18n';
import './style.scss';
import BlockManager from './components/multiselector/manager';
import { withSelect, withDispatch } from '@wordpress/data';
import { compose } from '@wordpress/compose';

class EditBlock extends Component {
	constructor( props ) {
		super( props );
		this.props = props;
	}

	render() {
		const { className } = this.props;
		// console.log(withSelect);
		// console.log(BlockManager);
		// 	withSelect( ( select ) => {
		// 		const { isModalActive } = select( 'core/edit-post' );

		// 		return {
		// 			isActive: isModalActive( MODAL_NAME ),
		// 		};
		// 	} ),
		// 	withDispatch( ( dispatch ) => {
		// 		const { closeModal } = dispatch( 'core/edit-post' );

		// 		return {
		// 			closeModal,
		// 		};
		// 	} ),
		// ] )( BlockManager );
		return (
			<div className={ className }>
				<div className={ className+'__items' }>
					<div className={ className+'__item' }>
						<div className={ className+'__item_data' }>
							<div className={ className+'__item_data_for' }>
								<p className={ className+'__item_data__title' }><strong>{ __('Define access for:', 'graphql-api') }</strong></p>
								<p>{ __('Fields:', 'graphql-api') }</p>
								<div className="edit-post-manage-blocks-modal">
									<BlockManager />
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

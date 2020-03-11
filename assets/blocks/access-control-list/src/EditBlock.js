import { Component } from 'react';
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
				<div className={ className+'__for' }>
					For Lorem ipsum...
				</div>
				<div className={ className+'__who' }>
					Who Lorem ipsum...
				</div>
			</div>
		);
	}
}

export default EditBlock;

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
				Lorem ipsum...
			</div>
		);
	}
}

export default EditBlock;

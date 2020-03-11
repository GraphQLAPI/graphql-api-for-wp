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
				<div className={ className+'__items' }>
					<div className={ className+'__for' }>
						<p>For Lorem ipsum...</p>
					</div>
					<div className={ className+'__who' }>
						<p>Who Lorem ipsum...</p>
						<p>Who Lorem ipsum...</p>
					</div>
				</div>
			</div>
		);
	}
}

export default EditBlock;

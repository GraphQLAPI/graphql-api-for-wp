import React, { Component } from 'react';
import GraphiQL from 'graphiql';
import fetch from 'isomorphic-fetch';
import 'graphiql/graphiql.css';
import './style.scss';

class EditBlock extends Component {
	constructor( props ) {
		super( props );
		this.props = props;
	}

	graphQLFetcher( graphQLParams ) {
		return fetch( window.location.origin + '/api/graphql', {
			method: 'post',
			headers: { 'Content-Type': 'application/json' },
			body: JSON.stringify( graphQLParams ),
		} ).then( ( response ) => response.json() );
	}

	render() {
		const { attributes: { query, variables }, setAttributes, className } = this.props;
		const onEditQuery = newValue => setAttributes( { query: newValue } );
		const onEditVariables = newValue => setAttributes( { variables: newValue } );
		return (
			<div className={ className }>
				<GraphiQL
					fetcher={ this.graphQLFetcher }
					query={ query }
					variables={ variables }
					onEditQuery={ onEditQuery }
					onEditVariables={ onEditVariables }
					docExplorerOpen={ false }
				/>
			</div>
		);
	}
}

export default EditBlock;

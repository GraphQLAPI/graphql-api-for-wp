/**
 * GraphiQl dependencies
 */
import GraphiQL from 'graphiql';
import fetch from 'isomorphic-fetch';
import 'graphiql/graphiql.css';

/**
 * Internal dependencies
 */
import './style.scss';
import KeyboardShortcuts from './keyboard-shortcuts';

const graphQLFetcher = ( graphQLParams ) => {
	return fetch( window.graphqlApiGraphiql.endpoint, {
		method: 'post',
		headers: {
			Accept: 'application/json',
			'Content-Type': 'application/json',
			'X-WP-Nonce': window.graphqlApiGraphiql.nonce
		},
		body: JSON.stringify( graphQLParams ),
	} ).then( ( response ) => response.json() );
}

const EditBlock = ( props ) => {
	const {
		attributes: { query, variables },
		setAttributes,
		className,
	} = props;
	const onEditQuery = ( newValue ) =>
		setAttributes( { query: newValue } );
	const onEditVariables = ( newValue ) =>
		setAttributes( { variables: newValue } );
	return (
		<>
			<KeyboardShortcuts.Unregister />
			<div className={ className }>
				<GraphiQL
					fetcher={ graphQLFetcher }
					query={ query }
					variables={ variables }
					onEditQuery={ onEditQuery }
					onEditVariables={ onEditVariables }
					docExplorerOpen={ false }
				/>
			</div>
		</>
	);
}

export default EditBlock;

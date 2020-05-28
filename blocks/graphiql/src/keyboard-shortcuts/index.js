/**
 * WordPress dependencies
 */
import { useEffect } from '@wordpress/element';
import { useDispatch } from '@wordpress/data';

function KeyboardShortcuts() {
	return null;
}

function KeyboardShortcutsUnregister() {
	const {
		unregisterShortcut,
	} = useDispatch( 'core/keyboard-shortcuts' );
	useEffect( () => {
		// console.log('adentro 1');
		unregisterShortcut( 'core/block-editor/select-all' );
	}, [ unregisterShortcut ] );
	// console.log('adentro 2');
	return null;
}

KeyboardShortcuts.Unregister = KeyboardShortcutsUnregister;

export default KeyboardShortcuts;

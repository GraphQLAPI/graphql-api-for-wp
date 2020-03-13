/**
 * External dependencies
 */
import { receiveFieldsAndDirectives, setFieldsAndDirectives } from './actions';


export default {
	* receiveFieldsAndDirectives( state ) {
		const query = `
			echo([
				[name:"core-embed/wordpress-tv", category:"embed", title:"core-embed/wordpress-tv"],
				[name:"core-embed/youtube", category:"embed", title:"core-embed/youtube"],
				[name:"core/archives", category:"widgets", title:"core/archives"],
				[name:"core/audio", category:"widgets", title:"core/audio"],
			])@echo
		`
		const fieldsAndDirectives = yield receiveFieldsAndDirectives( query );
		return setFieldsAndDirectives( fieldsAndDirectives.data?.echo ?? [] );
	},
};

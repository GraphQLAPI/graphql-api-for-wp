
const getMarkdownContent = ( lang, fileName ) => {
	return import( /* webpackMode: "eager" */ `@endpointDocs/${ lang }/${ fileName }.md` )
		.then(obj => obj.default)
		// .then( ( { default: _ } ) )
}
const getMarkdownContentOrUseDefault = ( lang, defaultLang, fileName ) => {
	return getMarkdownContent( lang, fileName )
		.catch(err => getMarkdownContent( defaultLang, fileName ) )
}
export default getMarkdownContentOrUseDefault;
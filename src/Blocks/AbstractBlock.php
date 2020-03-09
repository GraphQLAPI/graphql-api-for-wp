<?php
namespace Leoloso\GraphQLByPoPWPPlugin\Blocks;

abstract class AbstractBlock {

    public function init(): void
    {
        // Initialize the GraphiQL
        \add_action('init', [$this, 'initBlock']);
    }

    protected function isDynamicBlock(): bool
    {
        return false;
    }

    protected function registerEditorCSS(): bool {
        return false;
    }

    protected function registerCommonStyleCSS(): bool {
        return false;
    }

    protected function getBlockNamespace(): string
    {
        return 'leoloso';//'graphql-by-pop';
    }

    abstract protected function getBlockName(): string;

    protected function getBlockFullName(): string
    {
        return sprintf(
            '%s/%s',
            $this->getBlockNamespace(),
            $this->getBlockName()
        );
    }

    protected function getBlockRegistrationName(): string
    {
        return sprintf(
            '%s-%s',
            $this->getBlockNamespace(),
            $this->getBlockName()
        );
    }

    protected function getBlockClassName(): string
    {
        return sprintf(
            'wp-block-%s',
            $this->getBlockRegistrationName()
        );
    }

    /**
     * Registers all block assets so that they can be enqueued through the block editor
     * in the corresponding context.
     *
     * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/applying-styles-with-stylesheets/
     */
    public function initBlock(): void
    {
        $dir = \GRAPHQL_BY_POP_PLUGIN_DIR.'/assets/blocks/'.$this->getBlockName();

        $script_asset_path = "$dir/build/index.asset.php";
        if ( ! file_exists( $script_asset_path ) ) {
            throw new Error(
                'You need to run `npm start` or `npm run build` for the "leoloso/graphiql" block first.'
            );
        }

        $url = \GRAPHQL_BY_POP_PLUGIN_URL.'/assets/blocks/'.$this->getBlockName().'/';
        $blockRegistrationName = $this->getBlockRegistrationName();
        $blockConfiguration = [];

        // Load the block scripts and styles
        $index_js     = 'build/index.js';
        $script_asset = require( $script_asset_path );
        \wp_register_script(
            $blockRegistrationName.'-block-editor',
            $url.$index_js,
            $script_asset['dependencies'],
            $script_asset['version']
        );
        $blockConfiguration['editor_script'] = $blockRegistrationName.'-block-editor';

        /**
         * Register editor CSS file
         */
        if ($this->registerEditorCSS()) {
            $editor_css = 'editor.css';
            \wp_register_style(
                $blockRegistrationName.'-block-editor',
                $url.$editor_css,
                array(),
                filemtime( "$dir/$editor_css" )
            );
            $blockConfiguration['editor_style'] = $blockRegistrationName.'-block-editor';
        }

        /**
         * Register client/editor CSS file
         */
        if ($this->registerCommonStyleCSS()) {
            $style_css = 'style.css';
            \wp_register_style(
                $blockRegistrationName.'-block',
                $url.$style_css,
                array(),
                filemtime( "$dir/$style_css" )
            );
            $blockConfiguration['style'] = $blockRegistrationName.'-block';
        }

        /**
         * Register callback function for dynamic block
         */
        if ($this->isDynamicBlock()) {
            $blockConfiguration['render_callback'] = [$this, 'renderBlock'];
        }

        \register_block_type( $this->getBlockFullName(), $blockConfiguration );
	}

	public function renderBlock($attributes): string
	{
		return '';
	}
}

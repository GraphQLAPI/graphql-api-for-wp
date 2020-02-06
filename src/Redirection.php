<?php
namespace Leoloso\GraphQLByPoPWPPlugin;

use PoP\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;

class Redirection {

    public static $ENDPOINT;

    public static function init()
    {
        self::$ENDPOINT = apply_filters(
            __CLASS__.':endpoint',
            'api/graphql'
        );

        add_action(
            'init',
            [self::class, 'addRewriteEndpoint']
        );
        add_filter(
            'query_vars',
            [self::class, 'addQueryVar'],
            1,
            1
        );
        add_action(
            'parse_request',
            [self::class, 'parseRequest']
        );
        // add_action(
        //     'wp_loaded',
        //     [self::class, 'maybeFlushRewriteRules']
        // );
    }

    /**
     * This resolves the http request and ensures that WordPress can respond with the appropriate
     * JSON response instead of responding with a template from the standard WordPress Template
     * Loading process
     *
     * @since  0.0.1
     * @access public
     * @return void
     * @throws \Exception
     * @throws \Throwable
     */
    public static function parseRequest() {

        // global $wp_query;
        // $doingPoPGraphQL = array_key_exists( self::$ENDPOINT , $wp_query->query_vars );
        // var_dump('$doingPoPGraphQL', $doingPoPGraphQL, $wp_query->query_vars);die;

        // Support wp-graphiql style request to /index.php?pop_graphql
        $doingPoPGraphQL = false;
        if ( isset($_GET['pop_graphql'])) {
            $doingPoPGraphQL = true;
        }

        // If before 'init' check $_SERVER.
        /*else*/if ( /*isset( $_SERVER['HTTP_HOST'] ) && */isset( $_SERVER['REQUEST_URI'] ) ) {
            // $haystack = wp_unslash( $_SERVER['HTTP_HOST'] )
            //     . wp_unslash( $_SERVER['REQUEST_URI'] );
            // $needle   = site_url(self::$ENDPOINT);

            // // Strip protocol.
            // $haystack = preg_replace( '#^(http(s)?://)#', '', $haystack );
            // $needle   = preg_replace( '#^(http(s)?://)#', '', $needle );
            // $len      = strlen( $needle );
            // $doingPoPGraphQL = ( substr( $haystack, 0, $len ) === $needle );
            $endpointSuffix = '/'.trim(self::$ENDPOINT, '/').'/';
            $uri = $_SERVER['REQUEST_URI'];
            // Remove everything after "?" and "#"
            $symbols = ['?', '#'];
            foreach ($symbols as $symbol) {
                $symbolPos = strpos($uri, $symbol);
                if ($symbolPos !== false) {
                    $uri = substr($uri, 0, $symbolPos);
                }
            }
            $uri = trailingslashit($uri);
            $doingPoPGraphQL = substr($uri, -1*strlen($endpointSuffix)) == $endpointSuffix;
        }

        if ($doingPoPGraphQL) {
            $_REQUEST[GD_URLPARAM_SCHEME] = POP_SCHEME_API;
            $_REQUEST[GD_URLPARAM_DATASTRUCTURE] = GraphQLDataStructureFormatter::getName();
        }
    }

    public static function addRewriteEndpoint()
    {
        // add_rewrite_rule(
        //     self::$ENDPOINT.'/?$',
        //     'index.php?pop_graphql=true',
        //     'top'
        // );
        add_rewrite_endpoint( self::$ENDPOINT, EP_ALL );
    }

    public static function addQueryVar($query_vars)
    {
        $query_vars[] = self::$ENDPOINT;
        return $query_vars;
    }

    // public static function maybeFlushRewriteRules() {
    //     $rules = get_option('rewrite_rules');
    //     $entry = self::$ENDPOINT.'/?$';
    //     if (!isset($rules[$entry])) {
    //         flush_rewrite_rules();
    //     }
    // }
}

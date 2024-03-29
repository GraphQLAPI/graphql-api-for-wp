<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointExecuters;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLEndpointCustomPostTypeInterface;
use GraphQLAPI\GraphQLAPI\Services\EndpointAnnotators\ClientEndpointAnnotatorInterface;
use GraphQLByPoP\GraphQLClientsForWP\Clients\AbstractClient;
use GraphQLByPoP\GraphQLClientsForWP\Constants\CustomHeaders;
use PoP\EngineWP\HelperServices\TemplateHelpersInterface;
use PoP\Root\App;

abstract class AbstractClientEndpointExecuter extends AbstractCPTEndpointExecuter implements EndpointExecuterServiceTagInterface
{
    private ?GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType = null;
    private ?TemplateHelpersInterface $templateHelpers = null;

    final public function setGraphQLCustomEndpointCustomPostType(GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType): void
    {
        $this->graphQLCustomEndpointCustomPostType = $graphQLCustomEndpointCustomPostType;
    }
    final protected function getGraphQLCustomEndpointCustomPostType(): GraphQLCustomEndpointCustomPostType
    {
        /** @var GraphQLCustomEndpointCustomPostType */
        return $this->graphQLCustomEndpointCustomPostType ??= $this->instanceManager->getInstance(GraphQLCustomEndpointCustomPostType::class);
    }
    final public function setTemplateHelpers(TemplateHelpersInterface $templateHelpers): void
    {
        $this->templateHelpers = $templateHelpers;
    }
    final protected function getTemplateHelpers(): TemplateHelpersInterface
    {
        /** @var TemplateHelpersInterface */
        return $this->templateHelpers ??= $this->instanceManager->getInstance(TemplateHelpersInterface::class);
    }

    protected function getCustomPostType(): GraphQLEndpointCustomPostTypeInterface
    {
        return $this->getGraphQLCustomEndpointCustomPostType();
    }

    public function executeEndpoint(): void
    {
        $response = App::getResponse();
        $response->setContent($this->getClient()->getClientHTML());
        $response->headers->set('content-type', 'text/html');

        /**
         * Add a Custom Header with the GraphQL endpoint to the response.
         *
         * Add it always (i.e. for both PROD and DEV) so that:
         *
         * - DEV: Can test that enabling/disabling the client works.
         * - PROD: Can execute the "PROD Integration Tests"
         * - In General: it's easier to find this useful information
         *               (the endpoint is also printed in the HTML)
         */
        $response->headers->set(CustomHeaders::CLIENT_ENDPOINT, $this->getClient()->getEndpoint());

        // Add a hook to send the Response to the client.
        $this->getTemplateHelpers()->sendResponseToClient();
    }

    abstract protected function getClient(): AbstractClient;

    /**
     * Only enable the service, if the corresponding module is also enabled
     */
    public function isServiceEnabled(): bool
    {
        if (!parent::isServiceEnabled()) {
            return false;
        }

        // Check the client has not been disabled in the CPT
        global $post;
        if (!$this->getClientEndpointAnnotator()->isClientEnabled($post)) {
            return false;
        }

        return true;
    }

    abstract protected function getClientEndpointAnnotator(): ClientEndpointAnnotatorInterface;
}

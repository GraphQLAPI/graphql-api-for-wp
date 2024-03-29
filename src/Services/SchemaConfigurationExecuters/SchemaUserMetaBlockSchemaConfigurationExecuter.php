<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\MetaSchemaTypeModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\BlockInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigSchemaUserMetaBlock;
use PoPCMSSchema\UserMeta\Environment as UserMetaEnvironment;
use PoPCMSSchema\UserMeta\Module as UserMetaModule;
use PoP\Root\Module\ModuleConfigurationHelpers;

class SchemaUserMetaBlockSchemaConfigurationExecuter extends AbstractSchemaMetaBlockSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    private ?SchemaConfigSchemaUserMetaBlock $schemaConfigSchemaUserMetaBlock = null;

    final public function setSchemaConfigSchemaUserMetaBlock(SchemaConfigSchemaUserMetaBlock $schemaConfigSchemaUserMetaBlock): void
    {
        $this->schemaConfigSchemaUserMetaBlock = $schemaConfigSchemaUserMetaBlock;
    }
    final protected function getSchemaConfigSchemaUserMetaBlock(): SchemaConfigSchemaUserMetaBlock
    {
        /** @var SchemaConfigSchemaUserMetaBlock */
        return $this->schemaConfigSchemaUserMetaBlock ??= $this->instanceManager->getInstance(SchemaConfigSchemaUserMetaBlock::class);
    }

    public function getEnablingModule(): ?string
    {
        return MetaSchemaTypeModuleResolver::SCHEMA_USER_META;
    }

    protected function getEntriesHookName(): string
    {
        return ModuleConfigurationHelpers::getHookName(
            UserMetaModule::class,
            UserMetaEnvironment::USER_META_ENTRIES
        );
    }

    protected function getBehaviorHookName(): string
    {
        return ModuleConfigurationHelpers::getHookName(
            UserMetaModule::class,
            UserMetaEnvironment::USER_META_BEHAVIOR
        );
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getSchemaConfigSchemaUserMetaBlock();
    }
}

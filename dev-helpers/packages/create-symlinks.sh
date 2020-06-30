#!/bin/bash
# Create the symlinks from node_modules/ in the current folder,
# to all the blocks/editor-scripts/packages in the plugin
# Then, a single node_modules/ folder can service everything
# Make sure package.json contains ALL dependencies needed for everything
# cd dirname "$0"
DIR="$( dirname ${BASH_SOURCE[0]} )"
NODE_MODULES_DIR="$DIR/node_modules/"
cd "$DIR"
# Blocks
ln -snf $NODE_MODULES_DIR ../../blocks/access-control/
ln -snf $NODE_MODULES_DIR ../../blocks/access-control-disable-access/
ln -snf $NODE_MODULES_DIR ../../blocks/access-control-user-capabilities/
ln -snf $NODE_MODULES_DIR ../../blocks/access-control-user-roles/
ln -snf $NODE_MODULES_DIR ../../blocks/access-control-user-state/
ln -snf $NODE_MODULES_DIR ../../blocks/cache-control/
ln -snf $NODE_MODULES_DIR ../../blocks/endpoint-options/
ln -snf $NODE_MODULES_DIR ../../blocks/field-deprecation/
ln -snf $NODE_MODULES_DIR ../../blocks/graphiql/
ln -snf $NODE_MODULES_DIR ../../blocks/graphiql-with-explorer/
ln -snf $NODE_MODULES_DIR ../../blocks/persisted-query-options/
ln -snf $NODE_MODULES_DIR ../../blocks/schema-config-access-control-lists/
ln -snf $NODE_MODULES_DIR ../../blocks/schema-config-cache-control-lists/
ln -snf $NODE_MODULES_DIR ../../blocks/schema-config-field-deprecation-lists/
ln -snf $NODE_MODULES_DIR ../../blocks/schema-config-options/
ln -snf $NODE_MODULES_DIR ../../blocks/schema-configuration/
# Editor Scripts
ln -snf $NODE_MODULES_DIR ../../editor-scripts/endpoint-editor-components/
ln -snf $NODE_MODULES_DIR ../../editor-scripts/persisted-query-editor-components/
# Packages
ln -snf $NODE_MODULES_DIR ../../packages/api-fetch/
ln -snf $NODE_MODULES_DIR ../../packages/components/

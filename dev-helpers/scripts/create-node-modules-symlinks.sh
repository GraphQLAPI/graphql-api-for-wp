#!/bin/bash
# Create the symlinks from node_modules/ in the current folder,
# to all the blocks/editor-scripts/packages in the plugin
# Then, a single node_modules/ folder can service everything
# Make sure package.json contains ALL dependencies needed for everything
DIR="$( dirname ${BASH_SOURCE[0]} )"
NODE_MODULES_DIR="$DIR/../packages/node_modules/"
cd "$DIR"

# Blocks
declare -a BlockArray=("access-control" "access-control-disable-access" "access-control-user-capabilities" "access-control-user-roles" "access-control-user-state" "cache-control" "endpoint-options" "field-deprecation" "graphiql" "graphiql-with-explorer" "persisted-query-options" "schema-config-access-control-lists" "schema-config-cache-control-lists" "schema-config-field-deprecation-lists" "schema-config-options" "schema-configuration")
for val in ${BlockArray[@]}; do
   ln -snf $NODE_MODULES_DIR "../../blocks/$val/"
done

# Editor Scripts
declare -a EditorScriptArray=("endpoint-editor-components" "persisted-query-editor-components")
for val in ${EditorScriptArray[@]}; do
   ln -snf $NODE_MODULES_DIR "../../editor-scripts/$val/"
done

# Packages
declare -a PackageArray=("api-fetch" "components")
for val in ${PackageArray[@]}; do
   ln -snf $NODE_MODULES_DIR "../../packages/$val/"
done

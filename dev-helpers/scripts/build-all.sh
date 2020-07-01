#!/bin/bash
# Create the symlinks to node_modules/ everywhere
./create-node-modules-symlinks.sh

# Build all scripts
# Current dir
DIR="$( dirname ${BASH_SOURCE[0]} )"

# Blocks
declare -a BlockArray=("access-control" "access-control-disable-access" "access-control-user-capabilities" "access-control-user-roles" "access-control-user-state" "cache-control" "endpoint-options" "field-deprecation" "graphiql" "graphiql-with-explorer" "persisted-query-options" "schema-config-access-control-lists" "schema-config-cache-control-lists" "schema-config-field-deprecation-lists" "schema-config-options" "schema-configuration")
for val in ${BlockArray[@]}; do
   cd "$DIR/../../blocks/$val/"
   npm run build
done

# Editor Scripts
declare -a EditorScriptArray=("endpoint-editor-components" "persisted-query-editor-components")
for val in ${EditorScriptArray[@]}; do
   cd "$DIR/../../blocks/$val/"
   npm run build
done

# Packages
declare -a PackageArray=("api-fetch" "components")
for val in ${PackageArray[@]}; do
   cd "$DIR/../../blocks/$val/"
   npm run build
done


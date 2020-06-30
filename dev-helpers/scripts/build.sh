#!/bin/bash
# Build all scripts
DIR="$( dirname ${BASH_SOURCE[0]} )"
# Iterate the arrays using for loop
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


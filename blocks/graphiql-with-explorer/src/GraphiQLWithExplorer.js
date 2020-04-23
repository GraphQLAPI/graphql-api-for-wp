import React, { Component } from "react";
import GraphiQL from "graphiql";
import GraphiQLExplorer from "graphiql-explorer";
import { buildClientSchema, getIntrospectionQuery, parse } from "graphql";

import "graphiql/graphiql.css";
import "./GraphiQLWithExplorer.css";

import { __ } from '@wordpress/i18n';

const fetchURL = window.location.origin + '/api/graphql';

function fetcher(params) {
  return fetch(
    fetchURL,
    {
      method: "POST",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json"
      },
      body: JSON.stringify(params)
    }
  )
    .then(function(response) {
      return response.text();
    })
    .then(function(responseBody) {
      try {
        return JSON.parse(responseBody);
      } catch (e) {
        return responseBody;
      }
    });
}

const DEFAULT_QUERY = `{
  user(id: 1) {
    id
    url
    posts {
      id
      title
      featuredImage {
        src
      }
      defaultFeaturedImage: featuredImage @default(value: 70) {
        src
      }
    }
  }
}`;

class GraphiQLWithExplorer extends Component {

	constructor(props) {
    super(props);
		this._graphiql = null;
		this.state = { schema: null, query: DEFAULT_QUERY, explorerIsOpen: true };
		this._handleEditQuery = this._handleEditQuery.bind(this);
		this._handleToggleExplorer = this._handleToggleExplorer.bind(this);
	}

  componentDidMount() {
    fetcher({
      query: getIntrospectionQuery()
    }).then(result => {
      const editor = this._graphiql.getQueryEditor();
      editor.setOption("extraKeys", {
        ...(editor.options.extraKeys || {}),
        "Shift-Alt-LeftClick": this._handleInspectOperation
      });

      this.setState({ schema: buildClientSchema(result.data) });
    });
  }

  _handleInspectOperation(
    cm,
    mousePos
  ) {
    const parsedQuery = parse(this.state.query || "");

    if (!parsedQuery) {
      console.error("Couldn't parse query document");
      return null;
    }

    var token = cm.getTokenAt(mousePos);
    var start = { line: mousePos.line, ch: token.start };
    var end = { line: mousePos.line, ch: token.end };
    var relevantMousePos = {
      start: cm.indexFromPos(start),
      end: cm.indexFromPos(end)
    };

    var position = relevantMousePos;

    var def = parsedQuery.definitions.find(definition => {
      if (!definition.loc) {
        console.log("Missing location information for definition");
        return false;
      }

      const { start, end } = definition.loc;
      return start <= position.start && end >= position.end;
    });

    if (!def) {
      console.error(
        "Unable to find definition corresponding to mouse position"
      );
      return null;
    }

    var operationKind =
      def.kind === "OperationDefinition"
        ? def.operation
        : def.kind === "FragmentDefinition"
        ? "fragment"
        : "unknown";

    var operationName =
      def.kind === "OperationDefinition" && !!def.name
        ? def.name.value
        : def.kind === "FragmentDefinition" && !!def.name
        ? def.name.value
        : "unknown";

    var selector = `.graphiql-explorer-root #${operationKind}-${operationName}`;

    var el = document.querySelector(selector);
    el && el.scrollIntoView();
  };

  _handleEditQuery(query) {
		this.setState({ query });
	}

  _handleToggleExplorer() {
    this.setState({ explorerIsOpen: !this.state.explorerIsOpen });
  }

  render() {
    const { query, schema } = this.state;
    return (
      <div className="graphiql-container">
        <GraphiQLExplorer
          schema={ schema }
          query={ query }
          onEdit={ this._handleEditQuery }
          onRunOperation={ operationName =>
            this._graphiql.handleRunQuery( operationName )
          }
          explorerIsOpen={ this.state.explorerIsOpen }
          onToggleExplorer={ this._handleToggleExplorer }
        />
        <GraphiQL
          ref={ ref => ( this._graphiql = ref ) }
          fetcher={ fetcher }
          schema={ schema }
          query={ query }
          onEditQuery={ this._handleEditQuery }
        >
          <GraphiQL.Toolbar>
            <GraphiQL.Button
              onClick={ () => this._graphiql.handlePrettifyQuery() }
              label={ __('Prettify', 'graphql-api') }
              title={ __('Prettify Query (Shift-Ctrl-P)', 'graphql-api') }
            />
            <GraphiQL.Button
              onClick={ () => this._graphiql.handleToggleHistory() }
              label={ __('History', 'graphql-api') }
              title={ __('Show History', 'graphql-api') }
            />
            <GraphiQL.Button
              onClick={ this._handleToggleExplorer }
              label={ __('Explorer', 'graphql-api') }
              title={ __('Toggle Explorer', 'graphql-api') }
            />
          </GraphiQL.Toolbar>
        </GraphiQL>
      </div>
    );
  }
}

export default GraphiQLWithExplorer;

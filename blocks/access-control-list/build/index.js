/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/index.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "../../packages/api-fetch/node_modules/isomorphic-fetch/fetch-npm-browserify.js":
/*!*************************************************************************************************************************************************!*\
  !*** /Users/leo/GitRepos/GitHub/Plugins/leoloso/graphql-api-wp-plugin/packages/api-fetch/node_modules/isomorphic-fetch/fetch-npm-browserify.js ***!
  \*************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// the whatwg-fetch polyfill installs the fetch() function
// on the global object (window or self)
//
// Return that as the export for use in Webpack, Browserify etc.
__webpack_require__(/*! whatwg-fetch */ "../../packages/api-fetch/node_modules/whatwg-fetch/fetch.js");
module.exports = self.fetch.bind(self);


/***/ }),

/***/ "../../packages/api-fetch/node_modules/whatwg-fetch/fetch.js":
/*!******************************************************************************************************************************!*\
  !*** /Users/leo/GitRepos/GitHub/Plugins/leoloso/graphql-api-wp-plugin/packages/api-fetch/node_modules/whatwg-fetch/fetch.js ***!
  \******************************************************************************************************************************/
/*! exports provided: Headers, Request, Response, DOMException, fetch */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "Headers", function() { return Headers; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "Request", function() { return Request; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "Response", function() { return Response; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "DOMException", function() { return DOMException; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "fetch", function() { return fetch; });
var support = {
  searchParams: 'URLSearchParams' in self,
  iterable: 'Symbol' in self && 'iterator' in Symbol,
  blob:
    'FileReader' in self &&
    'Blob' in self &&
    (function() {
      try {
        new Blob()
        return true
      } catch (e) {
        return false
      }
    })(),
  formData: 'FormData' in self,
  arrayBuffer: 'ArrayBuffer' in self
}

function isDataView(obj) {
  return obj && DataView.prototype.isPrototypeOf(obj)
}

if (support.arrayBuffer) {
  var viewClasses = [
    '[object Int8Array]',
    '[object Uint8Array]',
    '[object Uint8ClampedArray]',
    '[object Int16Array]',
    '[object Uint16Array]',
    '[object Int32Array]',
    '[object Uint32Array]',
    '[object Float32Array]',
    '[object Float64Array]'
  ]

  var isArrayBufferView =
    ArrayBuffer.isView ||
    function(obj) {
      return obj && viewClasses.indexOf(Object.prototype.toString.call(obj)) > -1
    }
}

function normalizeName(name) {
  if (typeof name !== 'string') {
    name = String(name)
  }
  if (/[^a-z0-9\-#$%&'*+.^_`|~]/i.test(name)) {
    throw new TypeError('Invalid character in header field name')
  }
  return name.toLowerCase()
}

function normalizeValue(value) {
  if (typeof value !== 'string') {
    value = String(value)
  }
  return value
}

// Build a destructive iterator for the value list
function iteratorFor(items) {
  var iterator = {
    next: function() {
      var value = items.shift()
      return {done: value === undefined, value: value}
    }
  }

  if (support.iterable) {
    iterator[Symbol.iterator] = function() {
      return iterator
    }
  }

  return iterator
}

function Headers(headers) {
  this.map = {}

  if (headers instanceof Headers) {
    headers.forEach(function(value, name) {
      this.append(name, value)
    }, this)
  } else if (Array.isArray(headers)) {
    headers.forEach(function(header) {
      this.append(header[0], header[1])
    }, this)
  } else if (headers) {
    Object.getOwnPropertyNames(headers).forEach(function(name) {
      this.append(name, headers[name])
    }, this)
  }
}

Headers.prototype.append = function(name, value) {
  name = normalizeName(name)
  value = normalizeValue(value)
  var oldValue = this.map[name]
  this.map[name] = oldValue ? oldValue + ', ' + value : value
}

Headers.prototype['delete'] = function(name) {
  delete this.map[normalizeName(name)]
}

Headers.prototype.get = function(name) {
  name = normalizeName(name)
  return this.has(name) ? this.map[name] : null
}

Headers.prototype.has = function(name) {
  return this.map.hasOwnProperty(normalizeName(name))
}

Headers.prototype.set = function(name, value) {
  this.map[normalizeName(name)] = normalizeValue(value)
}

Headers.prototype.forEach = function(callback, thisArg) {
  for (var name in this.map) {
    if (this.map.hasOwnProperty(name)) {
      callback.call(thisArg, this.map[name], name, this)
    }
  }
}

Headers.prototype.keys = function() {
  var items = []
  this.forEach(function(value, name) {
    items.push(name)
  })
  return iteratorFor(items)
}

Headers.prototype.values = function() {
  var items = []
  this.forEach(function(value) {
    items.push(value)
  })
  return iteratorFor(items)
}

Headers.prototype.entries = function() {
  var items = []
  this.forEach(function(value, name) {
    items.push([name, value])
  })
  return iteratorFor(items)
}

if (support.iterable) {
  Headers.prototype[Symbol.iterator] = Headers.prototype.entries
}

function consumed(body) {
  if (body.bodyUsed) {
    return Promise.reject(new TypeError('Already read'))
  }
  body.bodyUsed = true
}

function fileReaderReady(reader) {
  return new Promise(function(resolve, reject) {
    reader.onload = function() {
      resolve(reader.result)
    }
    reader.onerror = function() {
      reject(reader.error)
    }
  })
}

function readBlobAsArrayBuffer(blob) {
  var reader = new FileReader()
  var promise = fileReaderReady(reader)
  reader.readAsArrayBuffer(blob)
  return promise
}

function readBlobAsText(blob) {
  var reader = new FileReader()
  var promise = fileReaderReady(reader)
  reader.readAsText(blob)
  return promise
}

function readArrayBufferAsText(buf) {
  var view = new Uint8Array(buf)
  var chars = new Array(view.length)

  for (var i = 0; i < view.length; i++) {
    chars[i] = String.fromCharCode(view[i])
  }
  return chars.join('')
}

function bufferClone(buf) {
  if (buf.slice) {
    return buf.slice(0)
  } else {
    var view = new Uint8Array(buf.byteLength)
    view.set(new Uint8Array(buf))
    return view.buffer
  }
}

function Body() {
  this.bodyUsed = false

  this._initBody = function(body) {
    this._bodyInit = body
    if (!body) {
      this._bodyText = ''
    } else if (typeof body === 'string') {
      this._bodyText = body
    } else if (support.blob && Blob.prototype.isPrototypeOf(body)) {
      this._bodyBlob = body
    } else if (support.formData && FormData.prototype.isPrototypeOf(body)) {
      this._bodyFormData = body
    } else if (support.searchParams && URLSearchParams.prototype.isPrototypeOf(body)) {
      this._bodyText = body.toString()
    } else if (support.arrayBuffer && support.blob && isDataView(body)) {
      this._bodyArrayBuffer = bufferClone(body.buffer)
      // IE 10-11 can't handle a DataView body.
      this._bodyInit = new Blob([this._bodyArrayBuffer])
    } else if (support.arrayBuffer && (ArrayBuffer.prototype.isPrototypeOf(body) || isArrayBufferView(body))) {
      this._bodyArrayBuffer = bufferClone(body)
    } else {
      this._bodyText = body = Object.prototype.toString.call(body)
    }

    if (!this.headers.get('content-type')) {
      if (typeof body === 'string') {
        this.headers.set('content-type', 'text/plain;charset=UTF-8')
      } else if (this._bodyBlob && this._bodyBlob.type) {
        this.headers.set('content-type', this._bodyBlob.type)
      } else if (support.searchParams && URLSearchParams.prototype.isPrototypeOf(body)) {
        this.headers.set('content-type', 'application/x-www-form-urlencoded;charset=UTF-8')
      }
    }
  }

  if (support.blob) {
    this.blob = function() {
      var rejected = consumed(this)
      if (rejected) {
        return rejected
      }

      if (this._bodyBlob) {
        return Promise.resolve(this._bodyBlob)
      } else if (this._bodyArrayBuffer) {
        return Promise.resolve(new Blob([this._bodyArrayBuffer]))
      } else if (this._bodyFormData) {
        throw new Error('could not read FormData body as blob')
      } else {
        return Promise.resolve(new Blob([this._bodyText]))
      }
    }

    this.arrayBuffer = function() {
      if (this._bodyArrayBuffer) {
        return consumed(this) || Promise.resolve(this._bodyArrayBuffer)
      } else {
        return this.blob().then(readBlobAsArrayBuffer)
      }
    }
  }

  this.text = function() {
    var rejected = consumed(this)
    if (rejected) {
      return rejected
    }

    if (this._bodyBlob) {
      return readBlobAsText(this._bodyBlob)
    } else if (this._bodyArrayBuffer) {
      return Promise.resolve(readArrayBufferAsText(this._bodyArrayBuffer))
    } else if (this._bodyFormData) {
      throw new Error('could not read FormData body as text')
    } else {
      return Promise.resolve(this._bodyText)
    }
  }

  if (support.formData) {
    this.formData = function() {
      return this.text().then(decode)
    }
  }

  this.json = function() {
    return this.text().then(JSON.parse)
  }

  return this
}

// HTTP methods whose capitalization should be normalized
var methods = ['DELETE', 'GET', 'HEAD', 'OPTIONS', 'POST', 'PUT']

function normalizeMethod(method) {
  var upcased = method.toUpperCase()
  return methods.indexOf(upcased) > -1 ? upcased : method
}

function Request(input, options) {
  options = options || {}
  var body = options.body

  if (input instanceof Request) {
    if (input.bodyUsed) {
      throw new TypeError('Already read')
    }
    this.url = input.url
    this.credentials = input.credentials
    if (!options.headers) {
      this.headers = new Headers(input.headers)
    }
    this.method = input.method
    this.mode = input.mode
    this.signal = input.signal
    if (!body && input._bodyInit != null) {
      body = input._bodyInit
      input.bodyUsed = true
    }
  } else {
    this.url = String(input)
  }

  this.credentials = options.credentials || this.credentials || 'same-origin'
  if (options.headers || !this.headers) {
    this.headers = new Headers(options.headers)
  }
  this.method = normalizeMethod(options.method || this.method || 'GET')
  this.mode = options.mode || this.mode || null
  this.signal = options.signal || this.signal
  this.referrer = null

  if ((this.method === 'GET' || this.method === 'HEAD') && body) {
    throw new TypeError('Body not allowed for GET or HEAD requests')
  }
  this._initBody(body)
}

Request.prototype.clone = function() {
  return new Request(this, {body: this._bodyInit})
}

function decode(body) {
  var form = new FormData()
  body
    .trim()
    .split('&')
    .forEach(function(bytes) {
      if (bytes) {
        var split = bytes.split('=')
        var name = split.shift().replace(/\+/g, ' ')
        var value = split.join('=').replace(/\+/g, ' ')
        form.append(decodeURIComponent(name), decodeURIComponent(value))
      }
    })
  return form
}

function parseHeaders(rawHeaders) {
  var headers = new Headers()
  // Replace instances of \r\n and \n followed by at least one space or horizontal tab with a space
  // https://tools.ietf.org/html/rfc7230#section-3.2
  var preProcessedHeaders = rawHeaders.replace(/\r?\n[\t ]+/g, ' ')
  preProcessedHeaders.split(/\r?\n/).forEach(function(line) {
    var parts = line.split(':')
    var key = parts.shift().trim()
    if (key) {
      var value = parts.join(':').trim()
      headers.append(key, value)
    }
  })
  return headers
}

Body.call(Request.prototype)

function Response(bodyInit, options) {
  if (!options) {
    options = {}
  }

  this.type = 'default'
  this.status = options.status === undefined ? 200 : options.status
  this.ok = this.status >= 200 && this.status < 300
  this.statusText = 'statusText' in options ? options.statusText : 'OK'
  this.headers = new Headers(options.headers)
  this.url = options.url || ''
  this._initBody(bodyInit)
}

Body.call(Response.prototype)

Response.prototype.clone = function() {
  return new Response(this._bodyInit, {
    status: this.status,
    statusText: this.statusText,
    headers: new Headers(this.headers),
    url: this.url
  })
}

Response.error = function() {
  var response = new Response(null, {status: 0, statusText: ''})
  response.type = 'error'
  return response
}

var redirectStatuses = [301, 302, 303, 307, 308]

Response.redirect = function(url, status) {
  if (redirectStatuses.indexOf(status) === -1) {
    throw new RangeError('Invalid status code')
  }

  return new Response(null, {status: status, headers: {location: url}})
}

var DOMException = self.DOMException
try {
  new DOMException()
} catch (err) {
  DOMException = function(message, name) {
    this.message = message
    this.name = name
    var error = Error(message)
    this.stack = error.stack
  }
  DOMException.prototype = Object.create(Error.prototype)
  DOMException.prototype.constructor = DOMException
}

function fetch(input, init) {
  return new Promise(function(resolve, reject) {
    var request = new Request(input, init)

    if (request.signal && request.signal.aborted) {
      return reject(new DOMException('Aborted', 'AbortError'))
    }

    var xhr = new XMLHttpRequest()

    function abortXhr() {
      xhr.abort()
    }

    xhr.onload = function() {
      var options = {
        status: xhr.status,
        statusText: xhr.statusText,
        headers: parseHeaders(xhr.getAllResponseHeaders() || '')
      }
      options.url = 'responseURL' in xhr ? xhr.responseURL : options.headers.get('X-Request-URL')
      var body = 'response' in xhr ? xhr.response : xhr.responseText
      resolve(new Response(body, options))
    }

    xhr.onerror = function() {
      reject(new TypeError('Network request failed'))
    }

    xhr.ontimeout = function() {
      reject(new TypeError('Network request failed'))
    }

    xhr.onabort = function() {
      reject(new DOMException('Aborted', 'AbortError'))
    }

    xhr.open(request.method, request.url, true)

    if (request.credentials === 'include') {
      xhr.withCredentials = true
    } else if (request.credentials === 'omit') {
      xhr.withCredentials = false
    }

    if ('responseType' in xhr && support.blob) {
      xhr.responseType = 'blob'
    }

    request.headers.forEach(function(value, name) {
      xhr.setRequestHeader(name, value)
    })

    if (request.signal) {
      request.signal.addEventListener('abort', abortXhr)

      xhr.onreadystatechange = function() {
        // DONE (success or failure)
        if (xhr.readyState === 4) {
          request.signal.removeEventListener('abort', abortXhr)
        }
      }
    }

    xhr.send(typeof request._bodyInit === 'undefined' ? null : request._bodyInit)
  })
}

fetch.polyfill = true

if (!self.fetch) {
  self.fetch = fetch
  self.Headers = Headers
  self.Request = Request
  self.Response = Response
}


/***/ }),

/***/ "../../packages/api-fetch/src/fetch.js":
/*!********************************************************************************************************!*\
  !*** /Users/leo/GitRepos/GitHub/Plugins/leoloso/graphql-api-wp-plugin/packages/api-fetch/src/fetch.js ***!
  \********************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var isomorphic_fetch__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! isomorphic-fetch */ "../../packages/api-fetch/node_modules/isomorphic-fetch/fetch-npm-browserify.js");
/* harmony import */ var isomorphic_fetch__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(isomorphic_fetch__WEBPACK_IMPORTED_MODULE_0__);
/**
 * External dependencies
 */


var fetchGraphQLQuery = function fetchGraphQLQuery(query) {
  var content = {
    query: query
  };
  return isomorphic_fetch__WEBPACK_IMPORTED_MODULE_0___default()("".concat(window.location.origin, "/api/graphql"), {
    method: 'post',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(content)
  }).then(function (response) {
    return response.json();
  });
};

/* harmony default export */ __webpack_exports__["default"] = (fetchGraphQLQuery);

/***/ }),

/***/ "../../packages/api-fetch/src/index.js":
/*!********************************************************************************************************!*\
  !*** /Users/leo/GitRepos/GitHub/Plugins/leoloso/graphql-api-wp-plugin/packages/api-fetch/src/index.js ***!
  \********************************************************************************************************/
/*! exports provided: fetchGraphQLQuery */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _fetch__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./fetch */ "../../packages/api-fetch/src/fetch.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "fetchGraphQLQuery", function() { return _fetch__WEBPACK_IMPORTED_MODULE_0__["default"]; });

/**
 * Exports
 */


/***/ }),

/***/ "../../packages/components/node_modules/@babel/runtime/helpers/arrayWithoutHoles.js":
/*!*****************************************************************************************************************************************************!*\
  !*** /Users/leo/GitRepos/GitHub/Plugins/leoloso/graphql-api-wp-plugin/packages/components/node_modules/@babel/runtime/helpers/arrayWithoutHoles.js ***!
  \*****************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _arrayWithoutHoles(arr) {
  if (Array.isArray(arr)) {
    for (var i = 0, arr2 = new Array(arr.length); i < arr.length; i++) {
      arr2[i] = arr[i];
    }

    return arr2;
  }
}

module.exports = _arrayWithoutHoles;

/***/ }),

/***/ "../../packages/components/node_modules/@babel/runtime/helpers/defineProperty.js":
/*!**************************************************************************************************************************************************!*\
  !*** /Users/leo/GitRepos/GitHub/Plugins/leoloso/graphql-api-wp-plugin/packages/components/node_modules/@babel/runtime/helpers/defineProperty.js ***!
  \**************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _defineProperty(obj, key, value) {
  if (key in obj) {
    Object.defineProperty(obj, key, {
      value: value,
      enumerable: true,
      configurable: true,
      writable: true
    });
  } else {
    obj[key] = value;
  }

  return obj;
}

module.exports = _defineProperty;

/***/ }),

/***/ "../../packages/components/node_modules/@babel/runtime/helpers/iterableToArray.js":
/*!***************************************************************************************************************************************************!*\
  !*** /Users/leo/GitRepos/GitHub/Plugins/leoloso/graphql-api-wp-plugin/packages/components/node_modules/@babel/runtime/helpers/iterableToArray.js ***!
  \***************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _iterableToArray(iter) {
  if (Symbol.iterator in Object(iter) || Object.prototype.toString.call(iter) === "[object Arguments]") return Array.from(iter);
}

module.exports = _iterableToArray;

/***/ }),

/***/ "../../packages/components/node_modules/@babel/runtime/helpers/nonIterableSpread.js":
/*!*****************************************************************************************************************************************************!*\
  !*** /Users/leo/GitRepos/GitHub/Plugins/leoloso/graphql-api-wp-plugin/packages/components/node_modules/@babel/runtime/helpers/nonIterableSpread.js ***!
  \*****************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _nonIterableSpread() {
  throw new TypeError("Invalid attempt to spread non-iterable instance");
}

module.exports = _nonIterableSpread;

/***/ }),

/***/ "../../packages/components/node_modules/@babel/runtime/helpers/toConsumableArray.js":
/*!*****************************************************************************************************************************************************!*\
  !*** /Users/leo/GitRepos/GitHub/Plugins/leoloso/graphql-api-wp-plugin/packages/components/node_modules/@babel/runtime/helpers/toConsumableArray.js ***!
  \*****************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var arrayWithoutHoles = __webpack_require__(/*! ./arrayWithoutHoles */ "../../packages/components/node_modules/@babel/runtime/helpers/arrayWithoutHoles.js");

var iterableToArray = __webpack_require__(/*! ./iterableToArray */ "../../packages/components/node_modules/@babel/runtime/helpers/iterableToArray.js");

var nonIterableSpread = __webpack_require__(/*! ./nonIterableSpread */ "../../packages/components/node_modules/@babel/runtime/helpers/nonIterableSpread.js");

function _toConsumableArray(arr) {
  return arrayWithoutHoles(arr) || iterableToArray(arr) || nonIterableSpread();
}

module.exports = _toConsumableArray;

/***/ }),

/***/ "../../packages/components/src/components/multi-select-control/category.js":
/*!********************************************************************************************************************************************!*\
  !*** /Users/leo/GitRepos/GitHub/Plugins/leoloso/graphql-api-wp-plugin/packages/components/src/components/multi-select-control/category.js ***!
  \********************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/toConsumableArray */ "../../packages/components/node_modules/@babel/runtime/helpers/toConsumableArray.js");
/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/compose */ "@wordpress/compose");
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _checklist__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./checklist */ "../../packages/components/src/components/multi-select-control/checklist.js");



/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */



/**
 * Internal dependencies
 */



function BlockManagerCategory(_ref) {
  var instanceId = _ref.instanceId,
      category = _ref.category,
      blockTypes = _ref.blockTypes,
      selectedFields = _ref.selectedFields,
      setAttributes = _ref.setAttributes;
  var checkedBlockNames = Object(lodash__WEBPACK_IMPORTED_MODULE_2__["intersection"])(Object(lodash__WEBPACK_IMPORTED_MODULE_2__["map"])(blockTypes, 'name'), selectedFields);

  var toggleVisible = function toggleVisible(blockName, nextIsChecked) {
    setAttributes({
      selectedFields: nextIsChecked ? [].concat(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0___default()(selectedFields), [blockName]) : Object(lodash__WEBPACK_IMPORTED_MODULE_2__["without"])(selectedFields, blockName)
    });
  };

  var toggleAllVisible = function toggleAllVisible(nextIsChecked) {
    var blockNames = Object(lodash__WEBPACK_IMPORTED_MODULE_2__["map"])(blockTypes, 'name');
    setAttributes({
      selectedFields: nextIsChecked ? [].concat(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0___default()(selectedFields), _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0___default()(blockNames)) : lodash__WEBPACK_IMPORTED_MODULE_2__["without"].apply(void 0, [selectedFields].concat(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0___default()(blockNames)))
    });
  };

  var titleId = 'edit-post-manage-blocks-modal__category-title-' + instanceId;
  var isAllChecked = checkedBlockNames.length === blockTypes.length;
  var ariaChecked;

  if (isAllChecked) {
    ariaChecked = 'true';
  } else if (checkedBlockNames.length > 0) {
    ariaChecked = 'mixed';
  } else {
    ariaChecked = 'false';
  }

  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__["createElement"])("div", {
    role: "group",
    "aria-labelledby": titleId,
    className: "edit-post-manage-blocks-modal__category"
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__["CheckboxControl"], {
    checked: isAllChecked,
    onChange: toggleAllVisible,
    className: "edit-post-manage-blocks-modal__category-title",
    "aria-checked": ariaChecked,
    label: Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__["createElement"])("span", {
      id: titleId
    }, category.title)
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__["createElement"])(_checklist__WEBPACK_IMPORTED_MODULE_5__["default"], {
    blockTypes: blockTypes,
    value: checkedBlockNames,
    onItemChange: toggleVisible
  }));
}

/* harmony default export */ __webpack_exports__["default"] = (Object(_wordpress_compose__WEBPACK_IMPORTED_MODULE_3__["compose"])([_wordpress_compose__WEBPACK_IMPORTED_MODULE_3__["withInstanceId"]])(BlockManagerCategory));

/***/ }),

/***/ "../../packages/components/src/components/multi-select-control/checklist.js":
/*!*********************************************************************************************************************************************!*\
  !*** /Users/leo/GitRepos/GitHub/Plugins/leoloso/graphql-api-wp-plugin/packages/components/src/components/multi-select-control/checklist.js ***!
  \*********************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);


/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */



function BlockTypesChecklist(_ref) {
  var blockTypes = _ref.blockTypes,
      value = _ref.value,
      onItemChange = _ref.onItemChange;
  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("ul", {
    className: "edit-post-manage-blocks-modal__checklist"
  }, blockTypes.map(function (blockType) {
    return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("li", {
      key: blockType.name,
      className: "edit-post-manage-blocks-modal__checklist-item"
    }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__["CheckboxControl"], {
      label: blockType.title,
      checked: value.includes(blockType.name),
      onChange: Object(lodash__WEBPACK_IMPORTED_MODULE_1__["partial"])(onItemChange, blockType.name)
    }));
  }));
}

/* harmony default export */ __webpack_exports__["default"] = (BlockTypesChecklist);

/***/ }),

/***/ "../../packages/components/src/components/multi-select-control/index.js":
/*!*****************************************************************************************************************************************!*\
  !*** /Users/leo/GitRepos/GitHub/Plugins/leoloso/graphql-api-wp-plugin/packages/components/src/components/multi-select-control/index.js ***!
  \*****************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/compose */ "@wordpress/compose");
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./style.scss */ "../../packages/components/src/components/multi-select-control/style.scss");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_style_scss__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var _category__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./category */ "../../packages/components/src/components/multi-select-control/category.js");


/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */




 // Addition by Leo


/**
 * Internal dependencies
 */



function MultiSelectControl(_ref) {
  var search = _ref.search,
      setState = _ref.setState,
      blockTypes = _ref.blockTypes,
      categories = _ref.categories,
      selectedFields = _ref.selectedFields,
      setAttributes = _ref.setAttributes,
      typeFields = _ref.typeFields,
      retrievedTypeFields = _ref.retrievedTypeFields,
      retrievingTypeFieldsErrorMessage = _ref.retrievingTypeFieldsErrorMessage,
      directives = _ref.directives,
      retrievedDirectives = _ref.retrievedDirectives,
      retrievingDirectivesErrorMessage = _ref.retrievingDirectivesErrorMessage;
  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", {
    className: "edit-post-manage-blocks-modal__content"
  }, retrievedTypeFields && retrievingTypeFieldsErrorMessage && Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("p", {
    className: "edit-post-manage-blocks-modal__error_message"
  }, retrievingTypeFieldsErrorMessage), retrievedTypeFields && !retrievingTypeFieldsErrorMessage && Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["Fragment"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__["TextControl"], {
    type: "search",
    label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__["__"])('Search'),
    value: search,
    onChange: function onChange(nextSearch) {
      return setState({
        search: nextSearch
      });
    },
    className: "edit-post-manage-blocks-modal__search"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", {
    tabIndex: "0",
    role: "region",
    "aria-label": Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__["__"])('Available block types'),
    className: "edit-post-manage-blocks-modal__results"
  }, blockTypes.length === 0 && Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("p", {
    className: "edit-post-manage-blocks-modal__no-results"
  }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__["__"])('No blocks found.')), categories.map(function (category) {
    return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_category__WEBPACK_IMPORTED_MODULE_7__["default"], {
      key: category.slug,
      category: category,
      blockTypes: Object(lodash__WEBPACK_IMPORTED_MODULE_1__["filter"])(blockTypes, {
        category: category.slug
      }),
      selectedFields: selectedFields,
      setAttributes: setAttributes
    });
  }))), !retrievedTypeFields && Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__["Spinner"], null));
}

/* harmony default export */ __webpack_exports__["default"] = (Object(_wordpress_compose__WEBPACK_IMPORTED_MODULE_3__["compose"])([Object(_wordpress_compose__WEBPACK_IMPORTED_MODULE_3__["withState"])({
  search: ''
}), Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_2__["withSelect"])(function (select) {
  var _select = select('core/blocks'),
      getBlockTypes = _select.getBlockTypes,
      getCategories = _select.getCategories;

  var _select2 = select('leoloso/graphql-api'),
      getTypeFields = _select2.getTypeFields,
      retrievedTypeFields = _select2.retrievedTypeFields,
      getRetrievingTypeFieldsErrorMessage = _select2.getRetrievingTypeFieldsErrorMessage,
      getDirectives = _select2.getDirectives,
      retrievedDirectives = _select2.retrievedDirectives,
      getRetrievingDirectivesErrorMessage = _select2.getRetrievingDirectivesErrorMessage; // console.log('receiveFieldsAndDirectives', getTypeFields(), getDirectives());
  // console.log('retrievedTypeFields', retrievedTypeFields());


  return {
    blockTypes: getBlockTypes(),
    categories: getCategories(),
    typeFields: getTypeFields(),
    retrievedTypeFields: retrievedTypeFields(),
    retrievingTypeFieldsErrorMessage: getRetrievingTypeFieldsErrorMessage(),
    directives: getDirectives(),
    retrievedDirectives: retrievedDirectives(),
    retrievingDirectivesErrorMessage: getRetrievingDirectivesErrorMessage()
  };
})])(MultiSelectControl));

/***/ }),

/***/ "../../packages/components/src/components/multi-select-control/style.scss":
/*!*******************************************************************************************************************************************!*\
  !*** /Users/leo/GitRepos/GitHub/Plugins/leoloso/graphql-api-wp-plugin/packages/components/src/components/multi-select-control/style.scss ***!
  \*******************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var api = __webpack_require__(/*! ../../../../../blocks/access-control-list/node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
            var content = __webpack_require__(/*! !../../../../../blocks/access-control-list/node_modules/css-loader/dist/cjs.js!../../../../../blocks/access-control-list/node_modules/sass-loader/dist/cjs.js!./style.scss */ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!../../packages/components/src/components/multi-select-control/style.scss");

            content = content.__esModule ? content.default : content;

            if (typeof content === 'string') {
              content = [[module.i, content, '']];
            }

var options = {};

options.insert = "head";
options.singleton = false;

var update = api(content, options);

var exported = content.locals ? content.locals : {};



module.exports = exported;

/***/ }),

/***/ "../../packages/components/src/index.js":
/*!*********************************************************************************************************!*\
  !*** /Users/leo/GitRepos/GitHub/Plugins/leoloso/graphql-api-wp-plugin/packages/components/src/index.js ***!
  \*********************************************************************************************************/
/*! exports provided: MultiSelectControl */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _store__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./store */ "../../packages/components/src/store/index.js");
/* harmony import */ var _components_multi_select_control__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./components/multi-select-control */ "../../packages/components/src/components/multi-select-control/index.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "MultiSelectControl", function() { return _components_multi_select_control__WEBPACK_IMPORTED_MODULE_1__["default"]; });

/**
 * Internal dependencies
 */

/**
 * Exports
 */



/***/ }),

/***/ "../../packages/components/src/store/action-creators.js":
/*!*************************************************************************************************************************!*\
  !*** /Users/leo/GitRepos/GitHub/Plugins/leoloso/graphql-api-wp-plugin/packages/components/src/store/action-creators.js ***!
  \*************************************************************************************************************************/
/*! exports provided: setTypeFields, receiveTypeFields, setDirectives, receiveDirectives */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "setTypeFields", function() { return setTypeFields; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "receiveTypeFields", function() { return receiveTypeFields; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "setDirectives", function() { return setDirectives; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "receiveDirectives", function() { return receiveDirectives; });
/**
 * Returns an action object used in setting the typeFields object in the state
 *
 * @param {Array} typeFields Array of typeField objects received, where each object has key "type" for the type name, and key "fields" with an array of the type's fields.
 * @param {string} errorMessage Error message if fetching the objects failed
 *
 * @return {Object} Action object.
 */
function setTypeFields(typeFields, errorMessage) {
  return {
    type: 'SET_TYPE_FIELDS',
    typeFields: typeFields,
    errorMessage: errorMessage
  };
}
;
/**
 * Returns an action object used in signalling that the typeFields object must be received.
 *
 * @param {string} query GraphQL query to execute
 *
 * @return {Object} Action object.
 */

function receiveTypeFields(query) {
  return {
    type: 'RECEIVE_TYPE_FIELDS',
    query: query
  };
}
;
/**
 * Returns an action object used in setting the directives in the state
 *
 * @param {Array} directives Array of directives received.
 * @param {string} errorMessage Error message if fetching the objects failed
 *
 * @return {Object} Action object.
 */

function setDirectives(directives, errorMessage) {
  return {
    type: 'SET_DIRECTIVES',
    directives: directives,
    errorMessage: errorMessage
  };
}
;
/**
 * Returns an action object used in signalling that the directives must be received.
 *
 * @param {string} query GraphQL query to execute
 *
 * @return {Object} Action object.
 */

function receiveDirectives(query) {
  return {
    type: 'RECEIVE_DIRECTIVES',
    query: query
  };
}
;

/***/ }),

/***/ "../../packages/components/src/store/controls.js":
/*!******************************************************************************************************************!*\
  !*** /Users/leo/GitRepos/GitHub/Plugins/leoloso/graphql-api-wp-plugin/packages/components/src/store/controls.js ***!
  \******************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _api_fetch_src__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../api-fetch/src */ "../../packages/api-fetch/src/index.js");
/**
 * External dependencies
 */

var controls = {
  RECEIVE_TYPE_FIELDS: function RECEIVE_TYPE_FIELDS(action) {
    return Object(_api_fetch_src__WEBPACK_IMPORTED_MODULE_0__["fetchGraphQLQuery"])(action.query);
  },
  RECEIVE_DIRECTIVES: function RECEIVE_DIRECTIVES(action) {
    return Object(_api_fetch_src__WEBPACK_IMPORTED_MODULE_0__["fetchGraphQLQuery"])(action.query);
  }
};
/* harmony default export */ __webpack_exports__["default"] = (controls);

/***/ }),

/***/ "../../packages/components/src/store/index.js":
/*!***************************************************************************************************************!*\
  !*** /Users/leo/GitRepos/GitHub/Plugins/leoloso/graphql-api-wp-plugin/packages/components/src/store/index.js ***!
  \***************************************************************************************************************/
/*! exports provided: storeConfig, default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "storeConfig", function() { return storeConfig; });
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _reducer__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./reducer */ "../../packages/components/src/store/reducer.js");
/* harmony import */ var _selectors__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./selectors */ "../../packages/components/src/store/selectors.js");
/* harmony import */ var _action_creators__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./action-creators */ "../../packages/components/src/store/action-creators.js");
/* harmony import */ var _resolvers__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./resolvers */ "../../packages/components/src/store/resolvers.js");
/* harmony import */ var _controls__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./controls */ "../../packages/components/src/store/controls.js");
/**
 * WordPress dependencies
 */

/**
 * Internal dependencies
 */






/**
 * Module Constants
 */

var MODULE_KEY = 'leoloso/graphql-api';
/**
 * Block editor data store configuration.
 *
 * @see https://github.com/WordPress/gutenberg/blob/master/packages/data/README.md#registerStore
 *
 * @type {Object}
 */

var storeConfig = {
  reducer: _reducer__WEBPACK_IMPORTED_MODULE_1__["default"],
  selectors: _selectors__WEBPACK_IMPORTED_MODULE_2__,
  actions: _action_creators__WEBPACK_IMPORTED_MODULE_3__,
  controls: _controls__WEBPACK_IMPORTED_MODULE_5__["default"],
  resolvers: _resolvers__WEBPACK_IMPORTED_MODULE_4__["default"]
};
var store = Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__["registerStore"])(MODULE_KEY, storeConfig);
/* harmony default export */ __webpack_exports__["default"] = (store);

/***/ }),

/***/ "../../packages/components/src/store/reducer.js":
/*!*****************************************************************************************************************!*\
  !*** /Users/leo/GitRepos/GitHub/Plugins/leoloso/graphql-api-wp-plugin/packages/components/src/store/reducer.js ***!
  \*****************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "../../packages/components/node_modules/@babel/runtime/helpers/defineProperty.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__);


function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0___default()(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

/**
 * The initial state of the store
 */
var DEFAULT_STATE = {
  typeFields: [],
  retrievedTypeFields: false,
  retrievingTypeFieldsErrorMessage: null,
  directives: [],
  retrievedDirectives: false,
  retrievingDirectivesErrorMessage: null
};
/**
 * Reducer returning an array of types and their fields, and directives.
 *
 * @param {Object} state  Current state.
 * @param {Object} action Dispatched action.
 *
 * @return {Object} Updated state.
 */

var schemaInstrospection = function schemaInstrospection() {
  var state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : DEFAULT_STATE;
  var action = arguments.length > 1 ? arguments[1] : undefined;

  switch (action.type) {
    case 'SET_TYPE_FIELDS':
      return _objectSpread({}, state, {
        typeFields: action.typeFields,
        retrievedTypeFields: true,
        retrievingTypeFieldsErrorMessage: action.errorMessage
      });

    case 'SET_DIRECTIVES':
      return _objectSpread({}, state, {
        directives: action.directives,
        retrievedDirectives: true,
        retrievingDirectivesErrorMessage: action.errorMessage
      });
  }

  return state;
};

/* harmony default export */ __webpack_exports__["default"] = (schemaInstrospection);

/***/ }),

/***/ "../../packages/components/src/store/resolvers.js":
/*!*******************************************************************************************************************!*\
  !*** /Users/leo/GitRepos/GitHub/Plugins/leoloso/graphql-api-wp-plugin/packages/components/src/store/resolvers.js ***!
  \*******************************************************************************************************************/
/*! exports provided: FETCH_TYPE_FIELDS_GRAPHQL_QUERY, FETCH_DIRECTIVES_GRAPHQL_QUERY, default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "FETCH_TYPE_FIELDS_GRAPHQL_QUERY", function() { return FETCH_TYPE_FIELDS_GRAPHQL_QUERY; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "FETCH_DIRECTIVES_GRAPHQL_QUERY", function() { return FETCH_DIRECTIVES_GRAPHQL_QUERY; });
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/regenerator */ "@babel/runtime/regenerator");
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _action_creators__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./action-creators */ "../../packages/components/src/store/action-creators.js");


/**
 * WordPress dependencies
 */

/**
 * External dependencies
 */


var FETCH_TYPE_FIELDS_GRAPHQL_QUERY = "\n\tquery GetTypeFields {\n\t\t__schema {\n\t\t\ttypes {\n\t\t\t\tname\n\t\t\t\tfields(includeDeprecated: true) {\n\t\t\t\t\tname\n\t\t\t\t}\n\t\t\t}\n\t\t}\n\t}\n";
var FETCH_DIRECTIVES_GRAPHQL_QUERY = "\n\tquery GetDirectives {\n\t\t__schema {\n\t\t\tdirectives {\n\t\t\t\tname\n\t\t\t}\n\t\t}\n\t}\n";

var maybeGetErrorMessage = function maybeGetErrorMessage(response) {
  if (response.errors && response.errors.length) {
    return Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["sprintf"])(Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])("There were errors when retrieving data: %s", 'graphql-api'), response.errors.map(function (error) {
      return error.message;
    }).join(';'));
  }

  return null;
};

/* harmony default export */ __webpack_exports__["default"] = ({
  getTypeFields: /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.mark(function getTypeFields() {
    var _response$data, _response$data$__sche, _response$data$__sche2;

    var keepScalarTypes,
        keepIntrospectionTypes,
        response,
        maybeErrorMessage,
        typeFields,
        _args = arguments;
    return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.wrap(function getTypeFields$(_context) {
      while (1) {
        switch (_context.prev = _context.next) {
          case 0:
            keepScalarTypes = _args.length > 0 && _args[0] !== undefined ? _args[0] : false;
            keepIntrospectionTypes = _args.length > 1 && _args[1] !== undefined ? _args[1] : false;
            _context.next = 4;
            return Object(_action_creators__WEBPACK_IMPORTED_MODULE_2__["receiveTypeFields"])(FETCH_TYPE_FIELDS_GRAPHQL_QUERY);

          case 4:
            response = _context.sent;

            /**
             * If there were erros when executing the query, return an empty list, and show the error
             */
            maybeErrorMessage = maybeGetErrorMessage(response);

            if (!maybeErrorMessage) {
              _context.next = 8;
              break;
            }

            return _context.abrupt("return", Object(_action_creators__WEBPACK_IMPORTED_MODULE_2__["setTypeFields"])([], maybeErrorMessage));

          case 8:
            /**
             * Convert the response to an array with this structure:
             * {
             * "type": string
             * "fields": array|null
             * }
             */
            typeFields = ((_response$data = response.data) === null || _response$data === void 0 ? void 0 : (_response$data$__sche = _response$data.__schema) === null || _response$data$__sche === void 0 ? void 0 : (_response$data$__sche2 = _response$data$__sche.types) === null || _response$data$__sche2 === void 0 ? void 0 : _response$data$__sche2.map(function (element) {
              return {
                type: element.name,
                fields: element.fields == null ? null : element.fields.map(function (subelement) {
                  return subelement.name;
                })
              };
            })) || [];
            return _context.abrupt("return", Object(_action_creators__WEBPACK_IMPORTED_MODULE_2__["setTypeFields"])(typeFields));

          case 10:
          case "end":
            return _context.stop();
        }
      }
    }, getTypeFields);
  }),
  getDirectives: /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.mark(function getDirectives() {
    var _response$data2, _response$data2$__sch, _response$data2$__sch2;

    var response, maybeErrorMessage, directives;
    return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.wrap(function getDirectives$(_context2) {
      while (1) {
        switch (_context2.prev = _context2.next) {
          case 0:
            _context2.next = 2;
            return Object(_action_creators__WEBPACK_IMPORTED_MODULE_2__["receiveDirectives"])(FETCH_DIRECTIVES_GRAPHQL_QUERY);

          case 2:
            response = _context2.sent;

            /**
             * If there were erros when executing the query, return an empty list, and show the error
             */
            maybeErrorMessage = maybeGetErrorMessage(response);

            if (!maybeErrorMessage) {
              _context2.next = 7;
              break;
            }

            Object(_action_creators__WEBPACK_IMPORTED_MODULE_2__["setDirectives"])([], maybeErrorMessage);
            return _context2.abrupt("return");

          case 7:
            /**
             * Convert the response to an array directly, removing the "name" key
             */
            directives = ((_response$data2 = response.data) === null || _response$data2 === void 0 ? void 0 : (_response$data2$__sch = _response$data2.__schema) === null || _response$data2$__sch === void 0 ? void 0 : (_response$data2$__sch2 = _response$data2$__sch.directives) === null || _response$data2$__sch2 === void 0 ? void 0 : _response$data2$__sch2.map(function (element) {
              return element.name;
            })) || [];
            return _context2.abrupt("return", Object(_action_creators__WEBPACK_IMPORTED_MODULE_2__["setDirectives"])(directives));

          case 9:
          case "end":
            return _context2.stop();
        }
      }
    }, getDirectives);
  })
});

/***/ }),

/***/ "../../packages/components/src/store/selectors.js":
/*!*******************************************************************************************************************!*\
  !*** /Users/leo/GitRepos/GitHub/Plugins/leoloso/graphql-api-wp-plugin/packages/components/src/store/selectors.js ***!
  \*******************************************************************************************************************/
/*! exports provided: getTypeFields, retrievedTypeFields, getRetrievingTypeFieldsErrorMessage, getDirectives, retrievedDirectives, getRetrievingDirectivesErrorMessage */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getTypeFields", function() { return getTypeFields; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "retrievedTypeFields", function() { return retrievedTypeFields; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getRetrievingTypeFieldsErrorMessage", function() { return getRetrievingTypeFieldsErrorMessage; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getDirectives", function() { return getDirectives; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "retrievedDirectives", function() { return retrievedDirectives; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getRetrievingDirectivesErrorMessage", function() { return getRetrievingDirectivesErrorMessage; });
function getTypeFields(state) {
  var keepScalarTypes = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
  var keepIntrospectionTypes = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : false;
  var typeFields = state.typeFields;
  /**
   * Each element in typeFields has this shape:
   * {
   * "type": string
   * "fields": array|null
   * }
   * Scalar types are those with no fields
   */

  if (!keepScalarTypes) {
    typeFields = typeFields.filter(function (element) {
      return element.fields != null;
    });
  }
  /**
   * Introspection types (eg: __Schema, __Directive, __Type, etc) start with "__"
   */


  if (!keepIntrospectionTypes) {
    typeFields = typeFields.filter(function (element) {
      return !element.type.startsWith('__');
    });
  }

  return typeFields;
}
;
function retrievedTypeFields(state) {
  return state.retrievedTypeFields;
}
;
function getRetrievingTypeFieldsErrorMessage(state) {
  return state.retrievingTypeFieldsErrorMessage;
}
;
function getDirectives(state) {
  return state.directives;
}
;
function retrievedDirectives(state) {
  return state.retrievedDirectives;
}
;
function getRetrievingDirectivesErrorMessage(state) {
  return state.retrievingDirectivesErrorMessage;
}
;

/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!../../packages/components/src/components/multi-select-control/style.scss":
/*!************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!/Users/leo/GitRepos/GitHub/Plugins/leoloso/graphql-api-wp-plugin/packages/components/src/components/multi-select-control/style.scss ***!
  \************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// Imports
var ___CSS_LOADER_API_IMPORT___ = __webpack_require__(/*! ../../../../../blocks/access-control-list/node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
exports = ___CSS_LOADER_API_IMPORT___(false);
// Module
exports.push([module.i, "/**\n * Colors\n */\n/**\n * Colors\n */\n/**\n * Often re-used variables\n */\n/**\n * Breakpoint mixins\n */\n/**\n * Long content fade mixin\n *\n * Creates a fading overlay to signify that the content is longer\n * than the space allows.\n */\n/**\n * Button states and focus styles\n */\n/**\n * Applies editor left position to the selector passed as argument\n */\n/**\n * Styles that are reused verbatim in a few places\n */\n/**\n * Allows users to opt-out of animations via OS-level preferences.\n */\n/**\n * Reset default styles for JavaScript UI based pages.\n * This is a WP-admin agnostic reset\n */\n/**\n * Reset the WP Admin page styles for Gutenberg-like pages.\n */\n/**\n * Breakpoints & Media Queries\n */\n.edit-post-manage-blocks-modal__content {\n  max-height: 500px; }\n\n.edit-post-manage-blocks-modal__error_message {\n  color: red; }\n\n@media (min-width: 600px) {\n  .edit-post-manage-blocks-modal {\n    height: calc(100% - 56px - 56px); } }\n\n.edit-post-manage-blocks-modal .components-modal__content {\n  padding-bottom: 0;\n  display: flex;\n  flex-direction: column; }\n\n.edit-post-manage-blocks-modal .components-modal__header {\n  flex-shrink: 0;\n  margin-bottom: 0; }\n\n.edit-post-manage-blocks-modal__content {\n  display: flex;\n  flex-direction: column;\n  flex: 0 1 100%;\n  min-height: 0; }\n\n.edit-post-manage-blocks-modal__no-results {\n  font-style: italic;\n  padding: 24px 0;\n  text-align: center; }\n\n.edit-post-manage-blocks-modal__search {\n  margin: 16px 0; }\n  .edit-post-manage-blocks-modal__search .components-base-control__field {\n    margin-bottom: 0; }\n  .edit-post-manage-blocks-modal__search .components-base-control__label {\n    margin-top: -4px; }\n  .edit-post-manage-blocks-modal__search input[type=\"search\"].components-text-control__input {\n    padding: 12px;\n    border-radius: 4px; }\n\n.edit-post-manage-blocks-modal__category {\n  margin: 0 0 2rem 0; }\n\n.edit-post-manage-blocks-modal__category-title {\n  position: sticky;\n  top: 0;\n  padding: 16px 0;\n  background-color: #fff;\n  z-index: 1; }\n  .edit-post-manage-blocks-modal__category-title .components-base-control__field {\n    margin-bottom: 0; }\n  .edit-post-manage-blocks-modal__category-title .components-checkbox-control__label {\n    font-size: 0.9rem;\n    font-weight: 600; }\n\n.edit-post-manage-blocks-modal__checklist {\n  margin-top: 0; }\n\n.edit-post-manage-blocks-modal__checklist-item {\n  margin-bottom: 0;\n  padding-left: 16px;\n  border-top: 1px solid #e2e4e7; }\n  .edit-post-manage-blocks-modal__checklist-item:last-child {\n    border-bottom: 1px solid #e2e4e7; }\n  .edit-post-manage-blocks-modal__checklist-item .components-base-control__field {\n    align-items: center;\n    display: flex;\n    margin: 0; }\n  .components-modal__content .edit-post-manage-blocks-modal__checklist-item.components-checkbox-control__input-container {\n    margin: 0 8px; }\n  .edit-post-manage-blocks-modal__checklist-item .components-checkbox-control__label {\n    display: flex;\n    align-items: center;\n    justify-content: space-between;\n    flex-grow: 1;\n    padding: 0.6rem 0 0.6rem 10px; }\n  .edit-post-manage-blocks-modal__checklist-item .block-editor-block-icon {\n    margin-right: 10px;\n    fill: #555d66; }\n\n.edit-post-manage-blocks-modal__results {\n  height: 100%;\n  overflow: auto;\n  margin-left: -24px;\n  margin-right: -24px;\n  padding-left: 24px;\n  padding-right: 24px;\n  border-top: 1px solid #e2e4e7; }\n", ""]);
// Exports
module.exports = exports;


/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./src/style.scss":
/*!*****************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./src/style.scss ***!
  \*****************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// Imports
var ___CSS_LOADER_API_IMPORT___ = __webpack_require__(/*! ../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
exports = ___CSS_LOADER_API_IMPORT___(false);
// Module
exports.push([module.i, "/**\n * Breakpoints & Media Queries\n */\n@media (min-width: 600px) {\n  .wp-block-graphql-api-access-control-list__item_data {\n    display: grid;\n    grid-template-columns: 50% 1fr;\n    grid-template-rows: auto;\n    grid-gap: 20px; }\n    .wp-block-graphql-api-access-control-list__item_data__title {\n      text-align: center; } }\n", ""]);
// Exports
module.exports = exports;


/***/ }),

/***/ "./node_modules/css-loader/dist/runtime/api.js":
/*!*****************************************************!*\
  !*** ./node_modules/css-loader/dist/runtime/api.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/*
  MIT License http://www.opensource.org/licenses/mit-license.php
  Author Tobias Koppers @sokra
*/
// css base code, injected by the css-loader
// eslint-disable-next-line func-names
module.exports = function (useSourceMap) {
  var list = []; // return the list of modules as css string

  list.toString = function toString() {
    return this.map(function (item) {
      var content = cssWithMappingToString(item, useSourceMap);

      if (item[2]) {
        return "@media ".concat(item[2], " {").concat(content, "}");
      }

      return content;
    }).join('');
  }; // import a list of modules into the list
  // eslint-disable-next-line func-names


  list.i = function (modules, mediaQuery, dedupe) {
    if (typeof modules === 'string') {
      // eslint-disable-next-line no-param-reassign
      modules = [[null, modules, '']];
    }

    var alreadyImportedModules = {};

    if (dedupe) {
      for (var i = 0; i < this.length; i++) {
        // eslint-disable-next-line prefer-destructuring
        var id = this[i][0];

        if (id != null) {
          alreadyImportedModules[id] = true;
        }
      }
    }

    for (var _i = 0; _i < modules.length; _i++) {
      var item = [].concat(modules[_i]);

      if (dedupe && alreadyImportedModules[item[0]]) {
        // eslint-disable-next-line no-continue
        continue;
      }

      if (mediaQuery) {
        if (!item[2]) {
          item[2] = mediaQuery;
        } else {
          item[2] = "".concat(mediaQuery, " and ").concat(item[2]);
        }
      }

      list.push(item);
    }
  };

  return list;
};

function cssWithMappingToString(item, useSourceMap) {
  var content = item[1] || ''; // eslint-disable-next-line prefer-destructuring

  var cssMapping = item[3];

  if (!cssMapping) {
    return content;
  }

  if (useSourceMap && typeof btoa === 'function') {
    var sourceMapping = toComment(cssMapping);
    var sourceURLs = cssMapping.sources.map(function (source) {
      return "/*# sourceURL=".concat(cssMapping.sourceRoot || '').concat(source, " */");
    });
    return [content].concat(sourceURLs).concat([sourceMapping]).join('\n');
  }

  return [content].join('\n');
} // Adapted from convert-source-map (MIT)


function toComment(sourceMap) {
  // eslint-disable-next-line no-undef
  var base64 = btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap))));
  var data = "sourceMappingURL=data:application/json;charset=utf-8;base64,".concat(base64);
  return "/*# ".concat(data, " */");
}

/***/ }),

/***/ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js":
/*!****************************************************************************!*\
  !*** ./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js ***!
  \****************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var isOldIE = function isOldIE() {
  var memo;
  return function memorize() {
    if (typeof memo === 'undefined') {
      // Test for IE <= 9 as proposed by Browserhacks
      // @see http://browserhacks.com/#hack-e71d8692f65334173fee715c222cb805
      // Tests for existence of standard globals is to allow style-loader
      // to operate correctly into non-standard environments
      // @see https://github.com/webpack-contrib/style-loader/issues/177
      memo = Boolean(window && document && document.all && !window.atob);
    }

    return memo;
  };
}();

var getTarget = function getTarget() {
  var memo = {};
  return function memorize(target) {
    if (typeof memo[target] === 'undefined') {
      var styleTarget = document.querySelector(target); // Special case to return head of iframe instead of iframe itself

      if (window.HTMLIFrameElement && styleTarget instanceof window.HTMLIFrameElement) {
        try {
          // This will throw an exception if access to iframe is blocked
          // due to cross-origin restrictions
          styleTarget = styleTarget.contentDocument.head;
        } catch (e) {
          // istanbul ignore next
          styleTarget = null;
        }
      }

      memo[target] = styleTarget;
    }

    return memo[target];
  };
}();

var stylesInDom = [];

function getIndexByIdentifier(identifier) {
  var result = -1;

  for (var i = 0; i < stylesInDom.length; i++) {
    if (stylesInDom[i].identifier === identifier) {
      result = i;
      break;
    }
  }

  return result;
}

function modulesToDom(list, options) {
  var idCountMap = {};
  var identifiers = [];

  for (var i = 0; i < list.length; i++) {
    var item = list[i];
    var id = options.base ? item[0] + options.base : item[0];
    var count = idCountMap[id] || 0;
    var identifier = "".concat(id, " ").concat(count);
    idCountMap[id] = count + 1;
    var index = getIndexByIdentifier(identifier);
    var obj = {
      css: item[1],
      media: item[2],
      sourceMap: item[3]
    };

    if (index !== -1) {
      stylesInDom[index].references++;
      stylesInDom[index].updater(obj);
    } else {
      stylesInDom.push({
        identifier: identifier,
        updater: addStyle(obj, options),
        references: 1
      });
    }

    identifiers.push(identifier);
  }

  return identifiers;
}

function insertStyleElement(options) {
  var style = document.createElement('style');
  var attributes = options.attributes || {};

  if (typeof attributes.nonce === 'undefined') {
    var nonce =  true ? __webpack_require__.nc : undefined;

    if (nonce) {
      attributes.nonce = nonce;
    }
  }

  Object.keys(attributes).forEach(function (key) {
    style.setAttribute(key, attributes[key]);
  });

  if (typeof options.insert === 'function') {
    options.insert(style);
  } else {
    var target = getTarget(options.insert || 'head');

    if (!target) {
      throw new Error("Couldn't find a style target. This probably means that the value for the 'insert' parameter is invalid.");
    }

    target.appendChild(style);
  }

  return style;
}

function removeStyleElement(style) {
  // istanbul ignore if
  if (style.parentNode === null) {
    return false;
  }

  style.parentNode.removeChild(style);
}
/* istanbul ignore next  */


var replaceText = function replaceText() {
  var textStore = [];
  return function replace(index, replacement) {
    textStore[index] = replacement;
    return textStore.filter(Boolean).join('\n');
  };
}();

function applyToSingletonTag(style, index, remove, obj) {
  var css = remove ? '' : obj.media ? "@media ".concat(obj.media, " {").concat(obj.css, "}") : obj.css; // For old IE

  /* istanbul ignore if  */

  if (style.styleSheet) {
    style.styleSheet.cssText = replaceText(index, css);
  } else {
    var cssNode = document.createTextNode(css);
    var childNodes = style.childNodes;

    if (childNodes[index]) {
      style.removeChild(childNodes[index]);
    }

    if (childNodes.length) {
      style.insertBefore(cssNode, childNodes[index]);
    } else {
      style.appendChild(cssNode);
    }
  }
}

function applyToTag(style, options, obj) {
  var css = obj.css;
  var media = obj.media;
  var sourceMap = obj.sourceMap;

  if (media) {
    style.setAttribute('media', media);
  } else {
    style.removeAttribute('media');
  }

  if (sourceMap && btoa) {
    css += "\n/*# sourceMappingURL=data:application/json;base64,".concat(btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap)))), " */");
  } // For old IE

  /* istanbul ignore if  */


  if (style.styleSheet) {
    style.styleSheet.cssText = css;
  } else {
    while (style.firstChild) {
      style.removeChild(style.firstChild);
    }

    style.appendChild(document.createTextNode(css));
  }
}

var singleton = null;
var singletonCounter = 0;

function addStyle(obj, options) {
  var style;
  var update;
  var remove;

  if (options.singleton) {
    var styleIndex = singletonCounter++;
    style = singleton || (singleton = insertStyleElement(options));
    update = applyToSingletonTag.bind(null, style, styleIndex, false);
    remove = applyToSingletonTag.bind(null, style, styleIndex, true);
  } else {
    style = insertStyleElement(options);
    update = applyToTag.bind(null, style, options);

    remove = function remove() {
      removeStyleElement(style);
    };
  }

  update(obj);
  return function updateStyle(newObj) {
    if (newObj) {
      if (newObj.css === obj.css && newObj.media === obj.media && newObj.sourceMap === obj.sourceMap) {
        return;
      }

      update(obj = newObj);
    } else {
      remove();
    }
  };
}

module.exports = function (list, options) {
  options = options || {}; // Force single-tag solution on IE6-9, which has a hard limit on the # of <style>
  // tags it will allow on a page

  if (!options.singleton && typeof options.singleton !== 'boolean') {
    options.singleton = isOldIE();
  }

  list = list || [];
  var lastIdentifiers = modulesToDom(list, options);
  return function update(newList) {
    newList = newList || [];

    if (Object.prototype.toString.call(newList) !== '[object Array]') {
      return;
    }

    for (var i = 0; i < lastIdentifiers.length; i++) {
      var identifier = lastIdentifiers[i];
      var index = getIndexByIdentifier(identifier);
      stylesInDom[index].references--;
    }

    var newLastIdentifiers = modulesToDom(newList, options);

    for (var _i = 0; _i < lastIdentifiers.length; _i++) {
      var _identifier = lastIdentifiers[_i];

      var _index = getIndexByIdentifier(_identifier);

      if (stylesInDom[_index].references === 0) {
        stylesInDom[_index].updater();

        stylesInDom.splice(_index, 1);
      }
    }

    lastIdentifiers = newLastIdentifiers;
  };
};

/***/ }),

/***/ "./src/EditBlock.js":
/*!**************************!*\
  !*** ./src/EditBlock.js ***!
  \**************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _packages_components_src__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../packages/components/src */ "../../packages/components/src/index.js");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./style.scss */ "./src/style.scss");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_style_scss__WEBPACK_IMPORTED_MODULE_3__);





var EditBlock = function EditBlock(props) {
  var className = props.className,
      setAttributes = props.setAttributes,
      selectedFields = props.attributes.selectedFields;
  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", {
    className: className
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", {
    className: className + '__items'
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", {
    className: className + '__item'
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", {
    className: className + '__item_data'
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", {
    className: className + '__item_data_for'
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("p", {
    className: className + '__item_data__title'
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("strong", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('Define access for:', 'graphql-api'))), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("p", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('Fields:', 'graphql-api')), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", {
    className: "edit-post-manage-blocks-modal"
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_packages_components_src__WEBPACK_IMPORTED_MODULE_2__["MultiSelectControl"], {
    selectedFields: selectedFields,
    setAttributes: setAttributes
  })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("p", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('Directives:', 'graphql-api'))), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", {
    className: className + '__item_data_who'
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("p", {
    className: className + '__item_data__title'
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("strong", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('Who can access:', 'graphql-api'))), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("p", null, "Who Lorem ipsum..."), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("p", null, "Who Lorem ipsum..."))), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("hr", null))));
};

/* harmony default export */ __webpack_exports__["default"] = (EditBlock);

/***/ }),

/***/ "./src/index.js":
/*!**********************!*\
  !*** ./src/index.js ***!
  \**********************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _EditBlock_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./EditBlock.js */ "./src/EditBlock.js");
/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/#registering-a-block
 */

/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */


/**
 * Application imports
 */


/**
 * Every block starts by registering a new block type definition.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/#registering-a-block
 */

Object(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__["registerBlockType"])('graphql-api/access-control-list', {
  /**
   * This is the display title for your block, which can be translated with `i18n` functions.
   * The block inserter will show this name.
   */
  title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('Access Control List', 'graphql-api'),

  /**
   * This is a short description for your block, can be translated with `i18n` functions.
   * It will be shown in the Block Tab in the Settings Sidebar.
   */
  description: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('Define an Access Control policy to the GraphQL schema\'s fields and directives', 'graphql-api'),

  /**
   * Blocks are grouped into categories to help users browse and discover them.
   * The categories provided by core are `common`, `embed`, `formatting`, `layout` and `widgets`.
   */
  category: 'widgets',

  /**
   * An icon property should be specified to make it easier to identify a block.
   * These can be any of WordPress’ Dashicons, or a custom svg element.
   */
  icon: 'admin-users',

  /**
   * Block default attributes.
   */
  attributes: {
    selectedFields: {
      type: 'array',
      default: []
    },
    // Make it wide alignment by default
    align: {
      type: 'string',
      default: 'wide'
    }
  },

  /**
   * Optional block extended support features.
   */
  supports: {
    // Alignment options
    align: ['center', 'wide', 'full'],
    // Remove the support for the custom className.
    customClassName: false,
    // Remove support for an HTML mode.
    html: false // Only insert block through a template
    // inserter: false,

  },

  /**
   * The edit function describes the structure of your block in the context of the editor.
   * This represents what the editor will render when the block is used.
   *
   * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
   *
   * @param {Object} [props] Properties passed from the editor.
   *
   * @return {WPElement} Element to render.
   */
  edit: _EditBlock_js__WEBPACK_IMPORTED_MODULE_2__["default"],

  /**
   * The save function defines the way in which the different attributes should be combined
   * into the final markup, which is then serialized by the block editor into `post_content`.
   *
   * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#save
   *
   * @return {WPElement} Element to render.
   */
  save: function save() {
    return null;
  }
});

/***/ }),

/***/ "./src/style.scss":
/*!************************!*\
  !*** ./src/style.scss ***!
  \************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var api = __webpack_require__(/*! ../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
            var content = __webpack_require__(/*! !../node_modules/css-loader/dist/cjs.js!../node_modules/sass-loader/dist/cjs.js!./style.scss */ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./src/style.scss");

            content = content.__esModule ? content.default : content;

            if (typeof content === 'string') {
              content = [[module.i, content, '']];
            }

var options = {};

options.insert = "head";
options.singleton = false;

var update = api(content, options);

var exported = content.locals ? content.locals : {};



module.exports = exported;

/***/ }),

/***/ "@babel/runtime/regenerator":
/*!**********************************************!*\
  !*** external {"this":"regeneratorRuntime"} ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = this["regeneratorRuntime"]; }());

/***/ }),

/***/ "@wordpress/blocks":
/*!*****************************************!*\
  !*** external {"this":["wp","blocks"]} ***!
  \*****************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["blocks"]; }());

/***/ }),

/***/ "@wordpress/components":
/*!*********************************************!*\
  !*** external {"this":["wp","components"]} ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["components"]; }());

/***/ }),

/***/ "@wordpress/compose":
/*!******************************************!*\
  !*** external {"this":["wp","compose"]} ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["compose"]; }());

/***/ }),

/***/ "@wordpress/data":
/*!***************************************!*\
  !*** external {"this":["wp","data"]} ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["data"]; }());

/***/ }),

/***/ "@wordpress/element":
/*!******************************************!*\
  !*** external {"this":["wp","element"]} ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["element"]; }());

/***/ }),

/***/ "@wordpress/i18n":
/*!***************************************!*\
  !*** external {"this":["wp","i18n"]} ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["i18n"]; }());

/***/ }),

/***/ "lodash":
/*!**********************************!*\
  !*** external {"this":"lodash"} ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = this["lodash"]; }());

/***/ })

/******/ });
//# sourceMappingURL=index.js.map
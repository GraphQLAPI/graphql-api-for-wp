# Changelog

All notable changes to `graphql-api` will be documented in this file.

Updates should follow the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

## 0.1.1 - 2020-07-30

### Fixed

- GraphiQL client retrieves domain using $_SERVER['HTTP_HOST'] instead of $_SERVER['SERVER_NAME'], for if configuration in server is not correct
- Ignore port 443 from the URL retrieved `fullUrl` for SSL
- Fixed issue to query users by email

## 0.1.0 - 2020-07-22

### Added

- Launched project

# Imbo adapters SDK

[![CI](https://github.com/imbo/imbo-adapters-sdk/workflows/CI/badge.svg)](https://github.com/imbo/imbo-adapters-sdk/actions?query=workflow%3ACI)

## Installation

    composer require imbo/imbo-adapters-sdk

## Usage

SDK for storage and database adapters used with Imbo. This package contains some abstract test cases **must** be used by all adapters for Imbo, making sure they all pass at least the common tests. Adapters should also add specific tests when needed.

## License

MIT, see [LICENSE](LICENSE).
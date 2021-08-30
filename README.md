# Imbo adapters SDK

[![CI](https://github.com/imbo/imbo-adapters-sdk/workflows/CI/badge.svg)](https://github.com/imbo/imbo-adapters-sdk/actions?query=workflow%3ACI)

## Installation

    composer require imbo/imbo-adapters-sdk

## Usage

SDK for storage and database adapters used with Imbo. This package contains some abstract integration test cases that **must** be used by all adapters for Imbo, making sure they all pass at least the common tests. Adapters should also add specific tests when needed.

The following table shows which test case you will need to extend to test your implementation of Imbos interfaces:

| SDK base test case | Imbo interface |
| ------------------ | -------------- |
| `Imbo\Database\DatabaseTests` | `Imbo\Database\DatabaseInterface` |
| `Imbo\Storage\StorageTests` | `Imbo\Storage\StorageInterface` |
| `Imbo\EventListener\ImageVariations\Storage\StorageTests` | `Imbo\EventListener\ImageVariations\Storage\StorageInterface` |
| `Imbo\Auth\AccessControl\Adapter\MutableAdapterTests` | `Imbo\Auth\AccessControl\Adapter\MutableAdapter` |

## License

MIT, see [LICENSE](LICENSE).

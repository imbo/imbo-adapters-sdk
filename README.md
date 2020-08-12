# Imbo adapters SDK

[![CI](https://github.com/imbo/imbo-adapters-sdk/workflows/CI/badge.svg)](https://github.com/imbo/imbo-adapters-sdk/actions?query=workflow%3ACI)

## Installation

    composer require imbo/imbo-adapters-sdk

## Usage

SDK for storage and database adapters used with Imbo. This package contains some abstract test cases that **must** be used by all adapters for Imbo, making sure they all pass at least the common tests. Adapters should also add specific tests when needed.

### Main database adapters

If you implement `Imbo\Database\DatabaseInterface`, you should include a test that looks something like this:

```php
<?php declare(strict_types=1);
namespace Imbo\Database;

class MyAdapterTest extends DatabaseTests {
    protected function getAdapter() : MyAdapter {
        return new MyAdapter(/* ... */);
    }

    public function setUp() : void {
        // Remember to execute the parent setUp() method to initialize the adapter
        parent::setUp();

        // Perform database-specific cleanup
        // ...
    }
}
```

This will make the adapter implementation run all relevant tests in the abstract test case.

### Main storage adapters

If you implement `Imbo\Storage\StorageInterface`, you should include a test that looks something like this:

```php
<?php declare(strict_types=1);
namespace Imbo\Storage;

class MyAdapterTest extends StorageTests {
    protected function getAdapter() : MyAdapter {
        return new MyAdapter(/* ... */);
    }

    public function setUp() : void {
        // Remember to execute the parent setUp() method to initialize the adapter
        parent::setUp();

        // Perform database-specific cleanup
        // ...
    }
}
```

This will make the adapter implementation run all relevant tests in the abstract test case.

## License

MIT, see [LICENSE](LICENSE).
<?php declare(strict_types=1);
namespace Imbo\Storage;

use Imbo\Exception\StorageException;
use PHPUnit\Framework\TestCase;

/**
 * Base test case for database adapters
 */
abstract class StorageTests extends TestCase {
    protected StorageInterface $adapter;
    protected string $user = 'user';
    protected string $imageIdentifier = 'imageIdentifier';
    protected string $fixturesDir  = __DIR__ . '/../Fixtures';
    protected string $imageData;

    /**
     * Get the adapter we want to test
     *
     * @return StorageInterface
     */
    abstract protected function getAdapter() : StorageInterface;

    public function setUp() : void {
        $this->imageData = (string) file_get_contents($this->fixturesDir . '/image.png');
        $this->adapter   = $this->getAdapter();
    }

    /**
     * @covers ::store
     */
    public function testStoreAndGetImage() : void {
        $this->assertTrue(
            $this->adapter->store($this->user, $this->imageIdentifier, $this->imageData),
            'Could not store initial image',
        );

        $this->assertSame(
            $this->imageData,
            $this->adapter->getImage($this->user, $this->imageIdentifier),
            'Image data is out of sync',
        );
    }

    /**
     * @covers ::store
     * @covers ::delete
     * @covers ::getImage
     */
    public function testStoreDeleteAndGetImage() : void {
        $this->assertTrue(
            $this->adapter->store($this->user, $this->imageIdentifier, $this->imageData),
            'Could not store initial image',
        );

        $this->assertTrue(
            $this->adapter->delete($this->user, $this->imageIdentifier),
            'Could not delete image',
        );

        $this->expectExceptionObject(new StorageException('File not found', 404));
        $this->adapter->getImage($this->user, $this->imageIdentifier);
    }

    /**
     * @covers ::delete
     */
    public function testDeleteImageThatDoesNotExist() : void {
        $this->expectExceptionObject(new StorageException('File not found', 404));
        $this->adapter->delete($this->user, $this->imageIdentifier);
    }

    /**
     * @covers ::getImage
     */
    public function testGetImageThatDoesNotExist() : void {
        $this->expectExceptionObject(new StorageException('File not found', 404));
        $this->adapter->getImage($this->user, $this->imageIdentifier);
    }

    /**
     * @covers ::getLastModified
     */
    public function testGetLastModifiedOfImageThatDoesNotExist() : void {
        $this->expectExceptionObject(new StorageException('File not found', 404));
        $this->adapter->getLastModified($this->user, $this->imageIdentifier);
    }

    /**
     * @covers ::store
     * @covers ::getLastModified
     */
    public function testGetLastModified() : void {
        $this->assertTrue(
            $this->adapter->store($this->user, $this->imageIdentifier, $this->imageData),
            'Could not store initial image',
        );

        $lastModified = $this->adapter->getLastModified($this->user, $this->imageIdentifier);

        $this->assertEqualsWithDelta(
            time(),
            $lastModified->getTimestamp(),
            1,
            'Last modification should be around now',
        );
    }

    /**
     * @covers ::imageExists
     * @covers ::store
     */
    public function testCanCheckIfImageAlreadyExists() : void {
        $this->assertFalse(
            $this->adapter->imageExists($this->user, $this->imageIdentifier),
            'Image is not supposed to exist',
        );

        $this->assertTrue(
            $this->adapter->store($this->user, $this->imageIdentifier, $this->imageData),
            'Could not store image',
        );

        $this->assertTrue(
            $this->adapter->imageExists($this->user, $this->imageIdentifier),
            'Image does not exist',
        );
    }
}

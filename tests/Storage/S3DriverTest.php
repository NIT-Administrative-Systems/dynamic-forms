<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Storage;

use Aws\CommandInterface;
use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Northwestern\SysDev\DynamicForms\Storage\S3Driver;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Storage\S3Driver
 */
final class S3DriverTest extends TestCase
{
    const DUMMY_S3_CONF = [
        'region' => 'us-east-1',
        'version' => 'latest',
    ];

    /**
     * @covers ::__construct
     * @covers ::storageClient
     * @covers ::setStorageClient
     */
    public function testConstructorAndAccessors(): void
    {
        $mock_s3 = $this->createStub(S3Client::class);
        $driver = new S3Driver(self::DUMMY_S3_CONF, 'test-bucket');

        $origClient = $driver->storageClient();
        $driver->setStorageClient($mock_s3);

        // Same checks for the same instance -- so this ensures the setter works.
        $this->assertNotSame($origClient, $driver->storageClient());
    }

    #[DataProvider('findObjectProvider')]
    public function testFindObject(S3Client $client, bool $expected): void
    {
        $driver = new S3Driver(self::DUMMY_S3_CONF, 'test');
        $driver->setStorageClient($client);

        $this->assertEquals($expected, $driver->findObject('foo'));
    }

    public static function findObjectProvider(): array
    {
        $cmd = $this->createStub(CommandInterface::class);

        $mock_s3_found = $this->createStub(S3Client::class);
        $mock_s3_found->method('getCommand')->willReturn($cmd);
        $mock_s3_found->method('execute')->willReturn(true);

        $mock_s3_fail = $this->createStub(S3Client::class);
        $mock_s3_fail->method('getCommand')->willReturn($cmd);
        $mock_s3_fail->method('execute')
            ->willThrowException($this->createStub(S3Exception::class));

        return [
            'found' => [$mock_s3_found, true],
            'not found' => [$mock_s3_fail, false],
        ];
    }

    /**
     * @covers ::getDirectDownloadLink
     */
    public function testGetDirectDownloadLink(): void
    {
        $driver = $this->mockPresignDriver();

        $this->assertEquals('https://test.com', $driver->getDirectDownloadLink('foo'));
    }

    /**
     * @covers ::getDownloadLink
     */
    public function testGetDownloadLink(): void
    {
        $driver = $this->mockPresignDriver();
        $expected = response()->json(['url' => 'https://test.com'], 201);

        $this->assertEquals($expected->getData(true), $driver->getDownloadLink('foo')->getData(true));
    }

    /**
     * @covers ::getUploadLink
     */
    public function testGetUploadLink(): void
    {
        $this->app['router']->get('/dynamic-forms/storage/s3')->name('dynamic-forms.S3-file-download');

        $driver = $this->mockPresignDriver();
        $expected = response()->json([
            'signed' => 'https://test.com',
            'headers' => ['X-AWS-Thing' => 'AMZ', 'Content-Type' => 'application/octet-stream'],
            'url' => 'http://localhost/dynamic-forms/storage/s3',
            'data' => ['fileName' => 'fooFile.docx'],
        ], 201);

        $this->assertEquals($expected, $driver->getUploadLink('fooFile.docx'));
    }

    /**
     * Mocks an S3Client for creating presigned requests (up & down).
     */
    private function mockPresignDriver(): S3Driver
    {
        $cmd = $this->createStub(CommandInterface::class);

        $mock_s3 = $this->createStub(S3Client::class);
        $mock_s3->method('getCommand')->willReturn($cmd);
        $mock_s3->method('createPresignedRequest')->willReturn(new class {
            public function getUri()
            {
                return 'https://test.com';
            }

            public function getHeaders()
            {
                return ['X-AWS-Thing' => 'AMZ'];
            }
        });

        $driver = new S3Driver(self::DUMMY_S3_CONF, 'test');
        $driver->setStorageClient($mock_s3);

        return $driver;
    }
}

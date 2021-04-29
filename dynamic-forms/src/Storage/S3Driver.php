<?php

namespace Northwestern\SysDev\DynamicForms\Storage;

use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Illuminate\Http\JsonResponse;

class S3Driver implements StorageInterface
{
    protected S3Client $client;
    protected string $bucket;

    /**
     * Makes an S3 client based on the S3 config.
     *
     * This is necessary because every aspect of Flysystem and Illuminate\Storage is
     * designed to stop you getting the underlying S3Client. There's no way to pull that
     * out from Storage::disk('s3') short of reflection hacks to mark several private props
     * as public (which is not a good move in production code).
     *
     * The Vapor controller does something very similar, but it's only passing a handful
     * of specific options, which does not include use_path_style_endpoint. If you want to use
     * Minio, you need to set that flag (or do some DNS tricks that wouldn't be very easy w/
     * Homestead).
     */
    public function __construct()
    {
        $config = [
            'region' => config('filesystems.disks.s3.region'),
            'version' => 'latest',
            // 'use_path_style_endpoint' => true, // minio thing
            'url' => config('filesystems.disks.s3.endpoint'),
            'endpoint' => config('filesystems.disks.s3.endpoint'),
            'credentials' => [
                'key' => config('filesystems.disks.s3.key'),
                'secret' => config('filesystems.disks.s3.secret'),
            ],
        ];
        $this->client = S3Client::factory($config);
        $this->bucket = config('filesystems.disks.s3.bucket');
    }

    /**
     *  Used to see if key is stored in our bucket.
     */
    public function findObject(string $key): bool
    {
        $client = $this->storageClient();
        try {
            $client->execute($client->getCommand('headObject', array_filter([
                'Bucket' => $this->bucket,
                'Key' => $key,
            ])));

            return true;
        } catch (S3Exception) {
            return false;
        }
    }

    public function storageClient(): S3Client
    {
        return $this->client;
    }

    public function getDirectDownloadLink(string $key, ?string $originalName = null): string
    {
        $client = $this->storageClient();
        $signedRequest =
            $client->createPresignedRequest($client->getCommand('getObject', array_filter([
                'Bucket' => $this->bucket,
                'Key' => $key,
                'ResponseContentDisposition' => 'attachment; filename ="'.$originalName.'"',
            ])),
                '+50 minutes'
            );

        return  $signedRequest->getUri();
    }

    public function getDownloadLink(string $key, ?string $originalName = null): JsonResponse
    {
        return response()->json([
            'url' => $this->getDirectDownloadLink($key, $originalName),
        ], 201);
    }

    public function getUploadLink(string $key): JsonResponse
    {
        $client = $this->storageClient();
        $signedRequest = $client->createPresignedRequest(
            $client->getCommand('putObject', array_filter([
                'Bucket' => $this->bucket,
                'Key' => $key,
                'ACL' => 'private',
                'ContentType' => 'application/octet-stream',
            ])),
            '+5 minutes'
        );

        return response()->json([
            'signed' => (string) $signedRequest->getUri(),
            'headers' => array_merge(
                $signedRequest->getHeaders(),
                [
                    'Content-Type' => 'application/octet-stream',
                ]
            ),
            'url' => url('/storage/s3/'),
            'data' => [
                'fileName' => $key,
            ],
        ], 201);
    }
}

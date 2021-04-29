<?php

namespace Northwestern\SysDev\DynamicForms\Storage;

use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Illuminate\Http\JsonResponse;

class S3Driver implements StorageInterface
{
    protected S3Client $client;
    protected string $bucket;

    public function __construct(array $clientConfig, string $bucketName)
    {
        $this->client = new S3Client($clientConfig);
        $this->bucket = $bucketName;
    }

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

        return $signedRequest->getUri();
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

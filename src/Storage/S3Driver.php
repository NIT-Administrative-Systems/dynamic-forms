<?php

namespace Northwestern\SysDev\DynamicForms\Storage;

use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Illuminate\Http\JsonResponse;

class S3Driver implements StorageInterface
{
    /** @var string */
    const STORAGE_S3 = 's3';

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

    public function setStorageClient(S3Client $client): void
    {
        $this->client = $client;
    }

    /**
     * @param string $urlValidityPeriod The time at which the URL should expire. This can be a Unix timestamp, a PHP DateTime object, or a string that can be evaluated by strtotime().
     */
    public function getDirectDownloadLink(
        string $key,
        ?string $originalName = null,
        bool $forceDownload = true,
        string $urlValidityPeriod = '+5 minutes',
        array $additionalCommandParameters = []
    ): string {
        $client = $this->storageClient();
        $disposition = [$forceDownload ? 'attachment' : 'inline'];

        if ($originalName) {
            $disposition[] = sprintf('filename="%s"', str_replace('"', '\"', $originalName));
        }

        $parameters = array_merge([
            'Bucket' => $this->bucket,
            'Key' => $key,
            'ResponseContentDisposition' => implode('; ', $disposition),
        ], $additionalCommandParameters);

        $signedRequest =
            $client->createPresignedRequest(
                $client->getCommand('getObject', array_filter($parameters)),
                $urlValidityPeriod
            );

        return $signedRequest->getUri();
    }

    public function getDownloadLink(
        string $key,
        ?string $originalName = null,
        string $urlValidityPeriod = '+5 minutes',
        array $additionalCommandParameters = []
    ): JsonResponse
    {
        return response()->json([
            'url' => $this->getDirectDownloadLink($key, $originalName, true, $urlValidityPeriod, $additionalCommandParameters),
        ], 201);
    }

    /**
     * @param string $urlValidityPeriod The time at which the URL should expire. This can be a Unix timestamp, a PHP DateTime object, or a string that can be evaluated by strtotime().
     */
    public function getUploadLink(
        string $key,
        string $urlValidityPeriod = '+5 minutes',
        array $additionalCommandParameters = []
    ): JsonResponse
    {
        $client = $this->storageClient();
        $parameters = array_merge(
            [
                'Bucket' => $this->bucket,
                'Key' => $key,
                'ACL' => 'private',
                'ContentType' => 'application/octet-stream',
            ],
            $additionalCommandParameters
        );

        $signedRequest = $client->createPresignedRequest(
            $client->getCommand('putObject', array_filter($parameters)),
            $urlValidityPeriod
        );

        return response()->json([
            'signed' => (string) $signedRequest->getUri(),
            'headers' => array_merge(
                $signedRequest->getHeaders(),
                [
                    'Content-Type' => 'application/octet-stream',
                ]
            ),
            'url' => route('dynamic-forms.S3-file-download'),
            'data' => [
                'fileName' => $key,
            ],
        ], 201);
    }

    public function isValid(mixed $value): bool
    {
        // Check if all fields exist
        if (! isset($value) || ! isset($value['name']) || ! isset($value['key']) || ! isset($value['url'])) {
            return false;
        }

        /**
         * Check consistency of fields.
         *
         * Laravel's route() helper will provide a URL-encoded URL, but Formio will not. Rectify the difference
         * before trying to validate.
         */
        $expectedUrl = urldecode(route('dynamic-forms.S3-file-redirect', ['fileKey' => $value['name']]));
        if ($value['name'] != $value['key'] || $expectedUrl != $value['url']) {
            return false;
        }

        return $this->findObject($value['name']);
    }

    public static function getStorageMethod(): string
    {
        return self::STORAGE_S3;
    }
}

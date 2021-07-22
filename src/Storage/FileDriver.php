<?php


namespace Northwestern\SysDev\DynamicForms\Storage;

class FileDriver implements StorageInterface
{
    /** @var string */
    const STORAGE_URL = 'url';

    /**
     * @inheritDoc
     */
    public function findObject(string $key): bool
    {
        return \File::exists(storage_path('app/uploaded/'.$key));
    }

    public function isValid(mixed $value): bool
    {
        // Check if all fields exist
        if (! isset($value) || ! isset($value['name']) || ! isset($value['url']) || ! isset($value['data']) ||
            ! isset($value['data']['baseUrl']) || ! isset($value['data']['project']) || ! isset($value['data']['form'])) {
            return false;
        }

        // Check consistency of fields
        $expectedUrl = route('dynamic-forms.url-file-download').'?baseUrl='.urlencode($value['data']['baseUrl']).'&project=&form=/'.$value['name'];
        if ($expectedUrl != $value['url']) {
            return false;
        }

        return $this->findObject($value['name']);
    }

    public static function getStorageMethod(): string
    {
        return self::STORAGE_URL;
    }
}

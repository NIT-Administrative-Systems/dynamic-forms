# File Uploads
File uploads are supported via Amazon S3 or directly to local storage. 

## S3 Uploads

S3 Uploads are done in a multi-step process: 

1. When a user tries to upload a file, Formiojs submits an AJAX request to `App\Http\Controllers\DynamicFormsStorageController` asking for a presigned S3 upload URL.
1. The controller communicates with AWS to create a presigned upload URL that is only valid for a few minutes, then sends this URL to Formiojs.
1. Formiojs then submits an HTTP POST to the presigned S3 URL with the file data.

The uploaded file is never sent through your Laravel application -- it goes directly from the browser to S3. After the upload, your application can move or download the file and process it.

Requests to download or view files follows a similar multi-step approach: when a user clicks a download link, Formiojs is asking the controller for a presigned download URL that only remains valid for a few minutes, and then the browser is sent there.

The AWS S3 bucket itself should be private. The presigned URLs are what provide security for objects: the Laravel app gets a request for a resource, performs its own authorization checks, and then tells S3 to prepare a link for an authorized user.

Local file upload does not use presigned URL but directly uploads and downloads from local storage.


### Buckets & CORS Policy
Since the end-user's browser is doing requests directly to your S3 bucket to upload and download files, you will need to set an appropriate CORS policy on the bucket.

The below wildcard policy will work, but you may wish to add your domain names for the `AllowedOrigin`.

```xml
<?xml version="1.0" encoding="UTF-8"?>
<CORSConfiguration xmlns="http://s3.amazonaws.com/doc/2006-03-01/">
<CORSRule>
    <AllowedOrigin>*</AllowedOrigin>
    <AllowedMethod>PUT</AllowedMethod>
    <AllowedMethod>GET</AllowedMethod>
    <AllowedHeader>*</AllowedHeader>
</CORSRule>
</CORSConfiguration>
```

## Local Storage Uploads

Local file handling is done simply via AJAX requests to `App\Http\Controllers\DynamicFormsStorageController`
Files are stored at Laravel's storage_path('app/uploaded') with a keyed filename, similarly downloading and delete requests are also handled by Form.js with the keyed file

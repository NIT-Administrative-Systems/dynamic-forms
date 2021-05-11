# File Uploads
File uploads are supported via Amazon S3 or direct uploading. 

S3 Uploads are done in a multi-step process: 

1. When a user tries to upload a file, Formiojs submits an AJAX request to `App\Http\Controllers\DynamicFormsStorageController` asking for a presigned S3 upload URL.
1. The controller communicates with AWS to create a presigned upload URL that is only valid for a few minutes, then sends this URL to Formiojs.
1. Formiojs then submits an HTTP POST to the presigned S3 URL with the file data.

The uploaded file is never sent through your Laravel application -- it goes directly from the browser to S3. After the upload, your application can move or download the file and process it.

Requests to download or view files follows a similar multi-step approach: when a user clicks a download link, Formiojs is asking the controller for a presigned download URL that only remains valid for a few minutes, and then the browser is sent there.

The AWS S3 bucket itself should be private. The presigned URLs are what provide security for objects: the Laravel app gets a request for a resource, performs its own authorization checks, and then tells S3 to prepare a link for an authorized user.

Local file upload does not use presigned URL but directly uploads and downloads from local storage.

## Temporary Files
Since file transfers are done before a form is submitted, it is possible for a user to abandon their form and leave what is essentially junk data in the S3 bucket.

There are techniques to deal with this problem. For example, an S3 lifecycle policy to delete objects under `tmp/` after three days. When a form is filled out and submitted, you can move the object out of `tmp/`, and then update the submission JSON to reflect the new path.

This becomes a more complicated issue if you are doing auto-saves for forms as users fill them out.

## Buckets & CORS Policy
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

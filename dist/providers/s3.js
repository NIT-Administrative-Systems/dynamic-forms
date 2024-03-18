/* Ejected from https://raw.githubusercontent.com/formio/formio.js/master/src/providers/storage/s3.js */
import XHR from 'formiojs/providers/storage/xhr';

/**
 * NU: Changes this to a class, because it gets new'd by the consuming code.
 * See <https://github.com/formio/formio.js/issues/5230>.
 */
class S3Provider {
    constructor(formio) {
        this.formio = formio;
        this.title = 'S3';
    }

    uploadFile(file, fileName, dir, progressCallback, url, options, fileKey, groupPermissions, groupId, abortCallback) {
        return XHR.upload(this.formio, 's3', (xhr, response) => {
            response.data.fileName = fileName;
            response.data.key = XHR.path([response.data.key, dir, fileName]);
            if (response.signed) {
                xhr.openAndSetHeaders('PUT', response.signed);
                xhr.setRequestHeader('Content-Type', file.type);

                /** --- Begin NU customization
                 * We need to send the x-amz headers that the server indicates, or we cannot have a bucket policy.
                 * Bucket policies can ONLY read the headers that are real headers, and not the presigned faux-header
                 * values included in the URL.
                 */
                if (response.headers) {
                    for (const header in response.headers) {
                        if (header.toLowerCase().startsWith('x-amz')) {
                            xhr.setRequestHeader(header, response.headers[header][0]);
                        }
                    }
                }
                /** --- End NU customization */

                return file;
            }
            else {
                const fd = new FormData();
                for (const key in response.data) {
                    fd.append(key, response.data[key]);
                }
                fd.append('file', file);
                xhr.openAndSetHeaders('POST', response.url);
                return fd;
            }
        }, file, fileName, dir, progressCallback, groupPermissions, groupId, abortCallback).then((response) => {
            return {
                storage: 's3',
                name: fileName,
                bucket: response.bucket,
                key: response.data.key,
                url: XHR.path([response.url, response.data.key]),
                acl: response.data.acl,
                size: file.size,
                type: file.type
            };
        });
    }

    downloadFile(file) {
        if (file.acl !== 'public-read') {
            return this.formio.makeRequest('file', `${this.formio.formUrl}/storage/s3?bucket=${XHR.trim(file.bucket)}&key=${XHR.trim(file.key)}`, 'GET');
        }
        else {
            return Promise.resolve(file);
        }
    }
}

export default S3Provider;

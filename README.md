# File Uploader
Simple File Uploader helper class.

### Example of how to use
```php
if (isset($_FILES)) {
    try {
        $f = fileUpload::upload(
            $_FILES,
            'path/wher/to/save/file', 
            array('jpeg')
        );
    } catch (Exception $e) {
        //manage exception
    }
```

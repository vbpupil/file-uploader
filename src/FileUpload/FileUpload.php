<?php
/**
 * FileUpload.php Class
 *
 * @author    Dean Haines
 * @copyright: Dean Haines, 2018, UK
 * @license: GPL V3.0+ See LICENSE.md
 */

namespace vbpupil\FileUpload;


use vbpupil\Exceptions\FileUploadException;

class FileUpload
{
    /**
     * Takes a fileDetails array and destination
     *
     * @param array $fileDetails
     * @param string $destination
     * @param array $allowedExtensions
     * @return string
     * @throws FileUploadException
     */
    public static function upload(array $fileDetails, $destination, $allowedExtensions = array('jpeg', 'jpg', 'png'))
    {
        if (
            (is_array($fileDetails) && !empty($fileDetails)) &&
            (!empty($destination)) &&
            (is_array($allowedExtensions) && !empty($allowedExtensions))
        ) {
            $upload = pathinfo($fileDetails['name']);

            if (self::extensionCheck($upload['extension'], $allowedExtensions) !== true) {
                $tmp_allowed_exts = implode(',', $allowedExtensions);
                throw new FileUploadException("File type: '{$upload['extension']}' is not an allowed format. Please supply in the following format(s): {$tmp_allowed_exts}");
            }

            $cleanedFilename = preg_replace('/[^a-zA-Z0-9\-\._]/', '', $upload['filename']);
            $cleanedFilename .= "_" . time();

            if (move_uploaded_file($fileDetails['tmp_name'], "{$destination}/{$cleanedFilename}.{$upload['extension']}")) {
                return "{$cleanedFilename}.{$upload['extension']}";
            } else {
                throw new FileUploadException("Unable to move file to: {$destination}/{$cleanedFilename}.{$upload['extension']}. PERMISSIONS?");
            }
        } else {
            throw new FileUploadException('$_FILES array, Destination string & AllowedExstensions Array are required.');
        }
    }

    /**
     * Lets check that we allow this file type
     *
     * @param $ext
     * @param $allowedExtensions
     * @return bool
     */
    public static function extensionCheck($ext, $allowedExtensions)
    {
        if (is_string($ext) && !empty($ext)) {
            if (in_array(strtolower($ext), $allowedExtensions)) {
                return true;
            }
        }
    }
}
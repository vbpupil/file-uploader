<?php
/**
 * fileUpload.php Class
 *
 * @author    Dean Haines
 * @copyright: Dean Haines, 2018, UK
 * @license: GPL V3.0+ See LICENSE.md
 */

namespace vbpupil;


class fileUpload
{
    /**
     * @var array
     */
    protected static $allowed = array('jpeg', 'jpg', 'png');

    /**
     * takes a fileDetails array and destination
     *
     * @param $fileDetails
     * @param $destination
     * @return string
     * @throws \Exception
     */
    public static function upload($fileDetails, $destination)
    {
        if (is_array($fileDetails) && !empty($fileDetails) && !empty($destination)) {
            $upload = pathinfo($fileDetails['name']);

            if(self::extensionCheck($upload['extension']) !== true){
                throw new \Exception("File type: {$upload['extension']} is not allowed.");
            }

            $cleanedFilename = preg_replace('/[^a-zA-Z0-9\-\._]/', '', $upload['filename']);
            $cleanedFilename .= "_" . time();

            $b = "{$destination}/{$cleanedFilename}.{$upload['extension']}";

            if (move_uploaded_file($fileDetails['tmp_name'], "{$destination}/{$cleanedFilename}.{$upload['extension']}")) {
                return "{$cleanedFilename}.{$upload['extension']}";
            }else{
                throw new \Exception("Unable to move file to: {$destination}/{$cleanedFilename}.{$upload['extension']}. PERMISSIONS?");
            }
        }
    }

    /**
     * lets chack that we allow this file type
     *
     * @param $ext
     * @return bool
     */
    protected static function extensionCheck($ext)
    {
        if(is_string($ext) && !empty($ext)){
            if(in_array(strtolower($ext),self::$allowed)){
                return true;
            }
        }
    }
}
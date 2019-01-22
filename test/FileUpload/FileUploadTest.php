<?php
/**
 * Filename FileUploadTest.php.
 * Version: 1.0.0 (22/01/19)
 * Copyright:        Freetimers Internet
 */

namespace Test\vbpupil\FileUpload;


use PHPUnit\Framework\TestCase;
use vbpupil\Exceptions\FileUploadException;
use vbpupil\FileUpload\FileUpload;

class FileUploadTest extends TestCase
{
    protected $sut;

    public function testFailingWhenGivenInsufficientDetails()
    {
        try {
            $f = FileUpload::upload([], '', []);
        } catch (\Exception $e) {
            $this->assertTrue($e instanceof FileUploadException);
            $this->assertEquals(
                '$_FILES array, Destination string & AllowedExstensions Array are required.',
                $e->getMessage()
            );
        }
    }

    public function testExceptionIsThrownWhenExtenxionCheckFails()
    {
        try {
            $fileDetailsArr = [
                'name' => 'test.jpg',

            ];
            $allowedExtensions = ['jpeg'];

            $f = FileUpload::upload($fileDetailsArr, '/test/dir/', $allowedExtensions);
        } catch (\Exception $e) {
            $this->assertTrue($e instanceof FileUploadException);
            $this->assertEquals(
                'File type: jpg is not an allowedExtension.',
                $e->getMessage()
            );
        }
    }


    public function testExceptionIsThrownWhenAttemptingToSaveFile()
    {
        try {
            $fileDetailsArr = [
                'name' => 'test.jpg',
                'tmp_name' => 'xp/tst.jpg'
            ];
            $allowedExtensions = ['jpg'];

            $f = FileUpload::upload($fileDetailsArr, '/test/dir/', $allowedExtensions);
        } catch (\Exception $e) {
            $this->assertTrue($e instanceof FileUploadException);
            $this->assertTrue(
                strpos($e->getMessage(), 'Unable to move file to:') !== false
            );
        }
    }


    public function testExtensioCheckMethod()
    {
        $f = FileUpload::extensionCheck(
            'jpg',
            ['png']
        );

        $this->assertNull($f);

        $f = FileUpload::extensionCheck(
            'jpg',
            ['jpg']
        );

        $this->assertTrue($f);
    }
}

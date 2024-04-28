<?php
// Include PHPUnit framework
require_once 'vendor/autoload.php';

// Include the class to be tested
require_once 'ImageUploader.php';

use PHPUnit\Framework\TestCase;

class ImageUploaderTest extends TestCase
{
    public function testUploadImage()
    {
        // Mock the session
        $_SESSION['user_id'] = 123;

        // Mock database connection
        $dbMock = $this->getMockBuilder(PDO::class)
                       ->disableOriginalConstructor()
                       ->getMock();

        // Mock the PDOStatement
        $stmtMock = $this->getMockBuilder(PDOStatement::class)
                         ->getMock();

        // Mock the getQuestionOwnerUserID method
        $uploaderMock = $this->getMockBuilder(ImageUploader::class)
                             ->setConstructorArgs(["/path/to/target/dir/", "avatar_field", $dbMock])
                             ->onlyMethods(['getQuestionOwnerUserID'])
                             ->getMock();

        // Set up expectations for getQuestionOwnerUserID method
        $uploaderMock->expects($this->once())
                     ->method('getQuestionOwnerUserID')
                     ->with(1, 'table_name')
                     ->willReturn(123);

        // Mock the superglobal $_FILES
        $_FILES['image'] = [
            'name' => 'test_image.jpg',
            'type' => 'image/jpeg',
            'size' => 1000,
            'tmp_name' => 'tmp/test_image.jpg',
            'error' => 0
        ];

        // Mock the move_uploaded_file function
        $this->assertTrue(function_exists('move_uploaded_file'));
        $this->assertTrue(override_function('move_uploaded_file', 'return true;'));

        // Mock database insert statement
        $dbMock->expects($this->once())
               ->method('prepare')
               ->willReturn($stmtMock);

        $stmtMock->expects($this->once())
                 ->method('bindParam')
                 ->withConsecutive(
                     [':avatarPath', $this->equalTo('/GenralFunction/images/test_image.jpg')],
                     [':questionId', $this->equalTo(1)]
                 );

        $stmtMock->expects($this->once())
                 ->method('execute')
                 ->willReturn(true);

        // Set up expectations for header function
        $this->expectOutputString(''); // Suppress header output

        // Instantiate ImageUploader and call uploadImage method
        $uploaderMock->uploadImage('table_name', 1);

        // Reset overridden function
        override_function('move_uploaded_file', '');
    }
}

// Run the test case
PHPUnit\Framework\TestCase::runTest();
?>

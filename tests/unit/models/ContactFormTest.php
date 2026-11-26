<?php

namespace tests\unit\models;

use app\models\ContactForm;
use tests\unit\BaseTestCase;
use Yii;

class ContactFormTest extends BaseTestCase
{
    public function testValidationRequiresFieldsAndValidEmail()
    {
        $model = new ContactForm([
            'name' => '',
            'email' => 'not-an-email',
            'subject' => '',
            'body' => '',
        ]);

        $this->assertFalse($model->validate(['name', 'email', 'subject', 'body']));
        $this->assertArrayHasKey('name', $model->errors);
        $this->assertArrayHasKey('email', $model->errors);
        $this->assertArrayHasKey('subject', $model->errors);
        $this->assertArrayHasKey('body', $model->errors);
    }

    public function testEmailIsSentOnContact()
    {
        /** @var ContactForm|\PHPUnit\Framework\MockObject\MockObject $model */
        $model = $this->getMockBuilder(ContactForm::class)
            ->onlyMethods(['validate'])
            ->getMock();

        $model->expects($this->once())
            ->method('validate')
            ->willReturn(true);

        $model->attributes = [
            'name' => 'Tester',
            'email' => 'tester@example.com',
            'subject' => 'very important letter subject',
            'body' => 'body of current message',
        ];

        $mailDir = Yii::getAlias('@runtime/mail');
        if (is_dir($mailDir)) {
            foreach (glob($mailDir . '/*.eml') as $eml) {
                @unlink($eml);
            }
        }

        $this->assertTrue($model->contact('admin@example.com'));

        $files = glob($mailDir . '/*.eml');
        $this->assertNotEmpty($files);
        $content = file_get_contents(end($files));
        $this->assertStringContainsString('To: admin@example.com', $content);
        $this->assertStringContainsString('Subject: very important letter subject', $content);
        $this->assertStringContainsString('body of current message', $content);
    }
}

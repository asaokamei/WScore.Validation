<?php
declare(strict_types=1);

namespace tests\Validation;

use tests\Validation\Filters\AddPostfix;
use WScore\Validation\Locale\Messages;
use WScore\Validation\Validators\ValidationChain;
use PHPUnit\Framework\TestCase;

class ValidationMultipleTest extends TestCase
{
    /**
     * @param string $locale
     * @return ValidationChain
     */
    public function buildValidationMultiple($locale = 'en')
    {
        $messages = Messages::create($locale);
        $chain = new ValidationChain($messages);
        $chain->setMultiple();

        return $chain;
    }

    public function testVerify()
    {
        $list = $this->buildValidationMultiple();
        $list->addFilters(
            new AddPostfix('-multi')
        );
        $input = ['test' => 'test1', 'more' => 'test2'];
        $result = $list->verify($input);

        $this->assertEquals($input, $result->getOriginalValue());
        $this->assertTrue($result->hasChildren());
        $this->assertEquals('test1-multi', $result->getChild('test')->value());
        $this->assertEquals('test2-multi', $result->getChild('more')->value());
    }

}

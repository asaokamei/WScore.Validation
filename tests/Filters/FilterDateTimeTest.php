<?php

namespace tests\Filters;

use DateTimeImmutable;
use WScore\Validator\Filters\ValidateDateTime;
use PHPUnit\Framework\TestCase;
use WScore\Validator\Locale\Messages;
use WScore\Validator\Validators\Result;

class FilterDateTimeTest extends TestCase
{

    public function test__invoke()
    {
        $filter = new ValidateDateTime();
        $input = new Result('2019-04-01');
        $return = $filter->apply($input);
        $this->assertNull($return);
        $this->assertTrue($input->isValid());
        $this->assertEquals(DateTimeImmutable::class, get_class($input->value()));
        $this->assertEquals('2019/04/01', $input->value()->format('Y/m/d'));
    }

    /**
     * @dataProvider dateValueProvider
     * @param $value
     */
    public function testInputs($value)
    {
        $filter = new ValidateDateTime();
        $input = new Result($value);
        $return = $filter->apply($input);
        $this->assertNull($return);
        $this->assertTrue($input->isValid());
        $this->assertEquals(DateTimeImmutable::class, get_class($input->value()));
        $this->assertEquals('2019/04/01', $input->value()->format('Y/m/d'));
    }

    public function dateValueProvider()
    {
        return [
            ['2019-04'],
            ['2019-04-01'],
            ['2019/04/01'],
            ['20190401'],
            ['2019-04-01 01:00:00'],
            ['2019-04-01T01:00:00'],
        ];
    }

    public function testCreateDateTimeUsingFormat()
    {
        $filter = new ValidateDateTime(['format' =>'m/d/Y']);
        $input = new Result('04/01/2019');
        $return = $filter->apply($input);
        $this->assertNull($return);
        $this->assertTrue($input->isValid());
        $this->assertEquals(DateTimeImmutable::class, get_class($input->value()));
        $this->assertEquals('2019/04/01', $input->value()->format('Y/m/d'));
    }

    public function testInvalidUtf8ValueReturnsError()
    {
        $filter = new ValidateDateTime();
        $input = new Result(mb_convert_encoding('日本語','SJIS', 'UTF-8'));
        $return = $filter->apply($input);
        $this->assertFalse($return->isValid());
        $this->assertNull($input->value());

        $input->finalize(Messages::create());
        $this->assertEquals(['The input is invalid UTF-8 character.'], $input->getErrorMessage());
    }
}

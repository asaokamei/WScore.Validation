<?php
namespace WStest02\Validation;

use WScore\Validation\Rules;
use WScore\Validation\Validate;
use WScore\Validation\Validation;

require_once( dirname( dirname( __DIR__ ) ) . '/autoloader.php' );

class ValidationJa_Test extends \PHPUnit_Framework_TestCase
{
    /** @var \WScore\Validation\Validation */
    var $validate;

    /** @var  Rules */
    var $rules;

    /**
     * @var array
     */
    var $msg;

    public function setUp()
    {
        $this->validate = Validation::getInstance( 'ja' );
        $this->rules = new Rules();
        $this->msg     = include( __DIR__ . '/../../../src/WScore/Validation/Locale/Lang.ja.php' );
    }

    // +----------------------------------------------------------------------+
    /**
     * @test
     */
    function basic_class_type()
    {
        $this->assertEquals( 'WScore\Validation\Validation', get_class( $this->validate ) );
    }

    /**
     * @test
     */
    function find_a_single_value()
    {
        $rule = $this->rules;
        $source = array( 'test' => 'tested' );
        $this->validate->source( $source);
        $got = $this->validate->push( 'test', $rule('text') );

        $this->assertEquals( 'tested', $got );
        $this->assertEquals( 'tested', $this->validate->pop( 'test' ) );
        $this->assertEquals( $source, $this->validate->pop() );
        $this->assertEquals( true, $this->validate->isValid() );
        $this->assertEquals( array(), $this->validate->popError() );
    }

    // +----------------------------------------------------------------------+
    //  test for array input
    // +----------------------------------------------------------------------+
    /**
     * @test
     */
    function find_a_single_array_of_value()
    {
        $rule = $this->rules;
        $test = array( 'tested', 'more test' );
        $source = array( 'test' => $test );
        $this->validate->source( $source );
        $got = $this->validate->push( 'test', $rule('text') );

        $this->assertEquals( $test, $got );
        $this->assertEquals( $test, $this->validate->pop( 'test' ) );
        $this->assertEquals( $source, $this->validate->pop() );
        $this->assertEquals( true, $this->validate->isValid() );
        $this->assertEquals( array(), $this->validate->popError() );
    }

    /**
     * @test
     */
    function find_will_result_inValid_if_required_data_is_not_set()
    {
        $rule = $this->rules;
        $source = array( 'test' => '' );
        $this->validate->source( $source );
        $got = $this->validate->push( 'test', $rule('text')->required() );

        $this->assertEquals( '', $got );
        $this->assertEquals( '', $this->validate->pop( 'test' ) );
        $this->assertEquals( $source, $this->validate->pop() );
        $this->assertEquals( array(), $this->validate->popSafe() );
        $this->assertEquals( false, $this->validate->isValid() );
        $errors =  $this->validate->popError();
        $this->assertEquals( $this->msg['required'], $errors[ 'test' ] );
    }

    /**
     * @test
     */
    function find_will_return_array_of_errors_if_input_is_an_array()
    {
        $rule = $this->rules;
        $test = array( '123', 'more test', '456' );
        $source = array( 'test' => $test );
        $collect = array( 'test' => array( 0=>'123', 2=>'456') );
        $this->validate->source( $source );
        $got = $this->validate->push( 'test', $rule('number') );

        // should return the input
        $this->assertEquals( false, $got );
        $this->assertEquals( $test, $this->validate->pop( 'test' ) );
        $this->assertEquals( $source, $this->validate->pop() );

        // validation should become inValid.
        $this->assertEquals( false, $this->validate->isValid() );
        $errors =  $this->validate->popError();
        $this->assertEquals( $this->validate->validate->message->messages['matches']['number'], $errors[ 'test' ][1] );

        // popSafe returns data without error value.
        $this->assertEquals( $collect, $this->validate->popSafe() );
    }

    // +----------------------------------------------------------------------+
    //  test for multiple input
    // +----------------------------------------------------------------------+
    /**
     * @test
     */
    function find_multiple_date_value()
    {
        $rule = $this->rules;
        $source = array( 'test_y'=>'2013', 'test_m'=>'11', 'test_d'=>'08' );
        $this->validate->source( $source );
        $got = $this->validate->push( 'test', $rule('date') );
        $this->assertEquals( '2013-11-08', $got );
        $this->assertEquals( '2013-11-08', $this->validate->pop( 'test' ) );
        $this->assertEquals( array( 'test' => '2013-11-08'), $this->validate->pop() );
        $this->assertEquals( true, $this->validate->isValid() );
        $this->assertEquals( array(), $this->validate->popError() );
    }

    /**
     * @test
     */
    function find_multiple_datetime_value()
    {
        $rule = $this->rules;
        $source = array( 'test_y'=>'2013', 'test_m'=>'11', 'test_d'=>'08', 'test_h'=>'15', 'test_i'=>'13', 'test_s'=>'59' );
        $this->validate->source( $source );
        $got = $this->validate->push( 'test', $rule('datetime') );
        $this->assertEquals( '2013-11-08 15:13:59', $got );
        $this->assertEquals( '2013-11-08 15:13:59', $this->validate->pop( 'test' ) );
        $this->assertEquals( array( 'test' => '2013-11-08 15:13:59'), $this->validate->pop() );
        $this->assertEquals( true, $this->validate->isValid() );
        $this->assertEquals( array(), $this->validate->popError() );
    }

    // +----------------------------------------------------------------------+
    //  test for sameWith rule
    // +----------------------------------------------------------------------+
    /**
     * @test
     */
    function sameWith_checks_for_another_input()
    {
        $rule = $this->rules;
        $source = array(
            'mail1' => 'Email@Example.com',
            'mail2' => 'email＠Ｅｘａｍｐｌｅ.com'
        );
        $mail = 'email@example.com';
        $this->validate->source( $source );
        $got = $this->validate->push( 'mail1', $rule('mail')->sameWith( 'mail2') );

        $this->assertEquals( $mail, $got );
        $this->assertEquals( $mail, $this->validate->pop( 'mail1' ) );
        $this->assertEquals( array( 'mail1'=>$mail ), $this->validate->pop() );
        $this->assertEquals( true, $this->validate->isValid() );
        $this->assertEquals( array(), $this->validate->popError() );
    }

}
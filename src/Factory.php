<?php
namespace WScore\Validation;

class Factory
{
    static $locale = 'en';
    
    static $dir = null;

    static $message = '\WScore\Validation\Message';

    static $validate = '\WScore\Validation\Validate';

    static $validation = '\WScore\Validation\Validation';

    static $filter = '\WScore\Validation\Filter';

    static $valueTO = '\WScore\Validation\ValueTO';
    
    static $rules = '\WScore\Validation\Rules';

    /**
     * @param string $locale
     * @internal param string $dir
     */
    public static function setLocale( $locale )
    {
        static::$locale = strtolower( locale_get_primary_language( $locale ) );
        if( func_num_args() > 1 ) {
            static::$dir = func_get_arg(1);
        }
        /*
         * set up locale for Rules, which is often called by static. 
         */
        /** @var Rules $class */
        $class = static::$rules;
        $class::locale( $locale, static::getDir() );
    }
    
    /**
     * @return string
     */
    public static function getDir()
    {
        return static::$dir;
    }

    /**
     * @return string
     */
    public static function getLocale()
    {
        return static::$locale;
    }

    /**
     * @param string $locale
     * @param string $dir
     * @return Message
     */
    public static function buildMessage( $locale=null, $dir=null )
    {
        if( !$locale ) $locale = static::getLocale();
        if( !$dir ) $dir = static::getDir();
        /** @var Message $class */
        $class = static::$message;
        return new $class( $locale, $dir );
    }

    /**
     * @return ValueTO
     */
    public static function buildValueTO()
    {
        /** @var ValueTO $class */
        $class = static::$valueTO;
        return new $class( static::buildMessage() );
    }

    /**
     * @return Filter
     */
    public static function buildFilter()
    {
        /** @var Filter $class */
        $class = static::$filter;
        return new $class();
    }

    /**
     * @return Validate
     */
    public static function buildValidate()
    {
        /** @var Validate $class */
        $class = static::$validate;
        return new $class( static::buildFilter(), static::buildValueTO() );
    }

    /**
     * @return Validation
     */
    public static function buildValidation()
    {
        /** @var Validation $class */
        $class = static::$validation;
        return new $class( static::buildValidate() );
    }

    /**
     * @return Rules
     */
    public static function buildRules()
    {
        /** @var Rules $class */
        $class = static::$rules;
        return $class::getInstance( static::getLocale(), static::getDir() );
    }
}
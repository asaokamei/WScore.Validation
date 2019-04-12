<?php
declare(strict_types=1);

namespace WScore\Validation;

use WScore\Validation\Interfaces\FilterInterface;
use WScore\Validation\Interfaces\ValidationInterface;
use WScore\Validation\Locale\Messages;
use WScore\Validation\Locale\TypeFilters;
use WScore\Validation\Validators\ValidationChain;
use WScore\Validation\Validators\ValidationList;
use WScore\Validation\Validators\ValidationRepeat;

class ValidatorBuilder
{
    /**
     * @var Messages
     */
    private $messages;

    /**
     * @var TypeFilters
     */
    private $typeFilter;

    /**
     * @var bool
     */
    private $multiple = false;

    /**
     * @var null|string
     */
    private $name = null;

    /**
     * @var FilterInterface[]
     */
    private $filters = [];

    /**
     * ValidatorBuilder constructor.
     * @param string $locale
     */
    public function __construct($locale = 'en')
    {
        $this->messages = Messages::create($locale);
        $this->typeFilter = TypeFilters::create($locale);
    }

    private function prepareOptions(array $options): self
    {
        $self = clone($this);
        $self->multiple = $options['multiple'] ?? false;
        unset($options['multiple']);
        $self->name = $options['name'] ?? null;
        unset($options['name']);
        $filters = $options['filters']??[];
        unset($options['filters']);
        $self->filters = array_merge($filters, $options);

        return $self;
    }

    public function email(array $options = [])
    {
        return $this->prepareOptions($options)
            ->buildByType('email');
    }

    /**
     * @param array $list
     * @return FilterInterface[]
     */
    private function prepareFilters(array $list)
    {
        $filters = [];
        foreach ($list as $class => $option) {
            $filter = $option instanceof FilterInterface
                ? $option
                : new $class($option);
            $filters[] = $filter;
        }
        return $filters;
    }

    public function form(array $options = []): ValidationList
    {
        $builder = $this->prepareOptions($options);
        $v = new ValidationList($this->messages, $builder->name);
        $v->addFilters($builder->filters);
        return $v;
    }

    public function repeat(array $options = []): ValidationRepeat
    {
        $builder = $this->prepareOptions($options);
        $v = new ValidationRepeat($this->messages, $builder->name);
        $v->addFilters($builder->filters);
        return $v;
    }

    public function text(array $options = []): ValidationInterface
    {
        $builder = $this->prepareOptions($options);
        return $builder->buildByType('text');
    }

    private function buildByType(string $type = null): ValidationInterface
    {
        $v = new ValidationChain($this->messages, $this->name);
        if ($this->multiple) {
            $v = $v->setMultiple(true);
        }
        if ($type) {
            $filters = $this->typeFilter->getFilters($type);
            $v->addFilters($filters);
        }
        $v->addFilters($this->filters);
        return $v;
    }

    public function chain(array $options = []): ValidationInterface
    {
        return $this->prepareOptions($options)
            ->buildByType(null);
    }

    public function integer(array $options = []): ValidationInterface
    {
        return $this->prepareOptions($options)
            ->buildByType('integer');
    }
}
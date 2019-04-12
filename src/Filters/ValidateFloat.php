<?php
declare(strict_types=1);

namespace WScore\Validation\Filters;

use WScore\Validation\Interfaces\FilterInterface;
use WScore\Validation\Interfaces\ResultInterface;

class ValidateFloat extends AbstractFilter
{
    const INVALID_CHAR = __CLASS__ . '::INVALID_CHAR';
    const ARRAY_INPUT = __CLASS__ . '::ARRAY_INPUT';

    public function __construct()
    {
        $this->setPriority(FilterInterface::PRIORITY_FILTER_SANITIZE);
    }

    /**
     * @param ResultInterface $input
     * @return ResultInterface|null
     */
    public function __invoke(ResultInterface $input): ?ResultInterface
    {
        $value = $input->value();
        if (!is_numeric($value)) {
            $input->setValue(null);
            return $input->failed(__CLASS__);
        }
        $input->setValue((float)$value);
        return null;
    }
}
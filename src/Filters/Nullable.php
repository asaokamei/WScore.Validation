<?php
declare(strict_types=1);

namespace WScore\Validator\Filters;

use WScore\Validator\Interfaces\ResultInterface;

/**
 * Class Nullable
 *
 * breaks validation chain/loop if the input value is empty.
 * stops further validations which may fail if the input is an empty value.
 * thus, this filter allows NULLABLE value, regardless of the subsequent filter.
 *
 * @package WScore\Validation\Filters
 */
final class Nullable extends AbstractFilter
{
    public function __construct()
    {
    }

    /**
     * @param ResultInterface $input
     * @return ResultInterface|null
     */
    public function apply(ResultInterface $input): ?ResultInterface
    {
        if ($this->isEmpty($input->value())) {
            return $input;
        }
        return null;
    }
}
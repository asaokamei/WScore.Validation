<?php
namespace WScore\Validation;

use WScore\Validation\Interfaces\ResultInterface;

class ValidationChain extends AbstractValidation
{
    /**
     * @param string|string[] $value
     * @return ResultInterface
     */
    public function initialize($value)
    {
        $result = new Result();
        $result->setValue($value);
        return $result;
    }

    /**
     * @param ResultInterface $result
     * @param ResultInterface $rootResults
     * @return ResultInterface
     */
    public function validate($result, $rootResults = null)
    {
        foreach ($this->filters as $filter) {
            if ($result = $filter->__invoke($result, $rootResults)) {
                return $result;
            }
        }
        foreach ($this->validators as $name => $validator) {
            if ($result = $validator->__invoke($result, $rootResults)) {
                return $result;
            }
        }
        return $result;
    }

    /**
     * @param string|array $value
     * @return ResultInterface|null
     */
    public function verify($value)
    {
        $result = $this->initialize($value);
        return $this->validate($result);
    }
}
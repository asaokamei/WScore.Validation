<?php

namespace WScore\Validation\Interfaces;

interface ValidationInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param string|null $name
     * @return $this
     */
    public function setName(string $name);

    /**
     * @param string $message
     * @return void
     */
    public function setErrorMessage(string $message);

    /**
     * @param FilterInterface[] $filters
     * @return ValidationInterface
     */
    public function addFilters(FilterInterface ...$filters): ValidationInterface;

    /**
     * @param string $name
     * @param ValidationInterface $validation
     * @return ValidationInterface
     */
    public function add(string $name, ValidationInterface $validation): ValidationInterface;

    /**
     * @param string $name
     * @return ValidationInterface
     */
    public function get(string $name): ?ValidationInterface;

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool;

    /**
     * @param string $name
     * @return ValidationInterface
     */
    public function remove(string $name): ValidationInterface;

    /**
     * @return ValidationInterface[]
     */
    public function all(): array;

    /**
     * @param string|array $value
     * @return ResultInterface
     */
    public function verify($value);
}
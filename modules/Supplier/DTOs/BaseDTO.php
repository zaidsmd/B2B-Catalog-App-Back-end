<?php

namespace Modules\Supplier\DTOs;

/**
 * Base Data Transfer Object.
 */
abstract class BaseDTO
{
    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): static
    {
        $dto = new static;

        foreach ($data as $key => $value) {
            if (property_exists($dto, (string) $key)) {
                $dto->{$key} = $value;
            }
        }

        return $dto;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

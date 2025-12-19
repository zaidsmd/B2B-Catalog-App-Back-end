<?php

namespace Modules\Supplier\Enums;

enum SupplierType: string
{
    case PERSONAL = 'personal';
    case BUSINESS = 'business';

    public function isBusiness(): bool
    {
        return $this === self::BUSINESS;
    }

    public function isPersonal(): bool
    {
        return $this === self::PERSONAL;
    }

    public function getLabel(): string
    {
        return __('supplier.type.'.$this->value);
    }
}

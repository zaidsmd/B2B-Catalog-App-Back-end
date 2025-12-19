<?php

namespace Modules\Supplier\DTOs;

/**
 * @phpstan-extends BaseDTO<array{
 *  name: string,
 *  type: string,
 *  default_reduction: float,
 *  email: string,
 *  phone_number: string,
 *  address: string,
 *  tax_id: string,
 *  rib: string,
 *  rc: string,
 *  vat_number: string,
 *  ice: string,
 *  iban: string,
 *  swift_bic: string,
 *  account_number: string,
 *  routing_number: string,
 *  credit_limit: float
 * }
 *
 * @property string $full_name
 * @property string $type
 * @property float $default_reduction
 * @property float $credit_limit
 * @property string $email
 * @property string $phone
 * @property string $address
 * @property string $tax_id
 * @property string $rib
 * @property string $rc
 * @property string $vat_number
 * @property string $ice
 * @property string $iban
 * @property string $swift_bic
 * @property string $account_number
 * @property string $routing_number
 */
class CreateSupplierDTO extends BaseDTO
{
    public string $full_name;

    public string $type;

    public ?float $default_reduction = null;

    public ?float $credit_limit = null;

    public ?string $email = null;

    public ?string $phone = null;

    public ?string $address = null;

    public ?string $tax_id = null;

    public ?string $rib = null;

    public ?string $rc = null;

    public ?string $vat_number = null;

    public ?string $ice = null;

    public ?string $iban = null;

    public ?string $swift_bic = null;

    public ?string $account_number = null;

    public ?string $routing_number = null;

    public static function fromArray(array $data): static
    {
        return parent::fromArray($data);
    }
}

<?php

namespace Modules\Supplier\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'full_name',
        'type',
        'default_reduction',
        'credit_limit',
        'email',
        'phone',
        'address',
        'ice',
        'vat_number',
        'rc',
        'tax_id',
        'rib',
        'iban',
        'swift_bic',
        'account_number',
        'routing_number',
    ];
}

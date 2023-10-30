<?php

namespace App\Models\Types;

Enum SaleInvoiceTypesEnum {
    case PENDING;
    case REJECTED;
    case APPROVED;
}
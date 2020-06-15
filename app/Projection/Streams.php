<?php

namespace App\Projection;

interface Streams
{
    public const CUSTOMER = 'customer-stream';
    public const CUSTOMER_SNAPSHOT = 'customer-stream-snapshots';

    public const BANK_ACCOUNT = 'bank_account-stream';
    public const BANK_ACCOUNT_SNAPSHOT = 'bank_account-stream-snapshots';
}

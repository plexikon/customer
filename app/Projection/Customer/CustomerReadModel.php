<?php
declare(strict_types=1);

namespace App\Projection\Customer;

use App\Projection\Tables;
use Illuminate\Database\Schema\Blueprint;
use Plexikon\Chronicle\Support\ReadModel\ConnectionReadModel;
use Plexikon\Chronicle\Support\ReadModel\HasConnectionOperation;

final class CustomerReadModel extends ConnectionReadModel
{
    use HasConnectionOperation;

    protected function up(): callable
    {
        return function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('email')->unique();
            $table->string('name');
        };
    }

    protected function tableName(): string
    {
        return Tables::CUSTOMER;
    }
}

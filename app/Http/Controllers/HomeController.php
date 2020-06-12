<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Model\Customer\Command\ChangeCustomerEmail;
use Faker\Factory;
use Plexikon\Reporter\Support\Publisher\LazyPublisher;

final class HomeController
{
    private string $id = 'd760ab99-155b-3a44-9caa-649c71b90f3d';

    public function __invoke(LazyPublisher $publisher)
    {
//        $this->changeEmail($publisher);

        return 'ok';
    }

    protected function changeEmail(LazyPublisher $publisher): void
    {
        $faker = Factory::create();

        $i = 20;
        while ($i !== 0) {
            $publisher->publishCommand(
                ChangeCustomerEmail::withData(
                    $this->id,
                    $faker->email
                )
            );
            $i--;
        }
    }
}

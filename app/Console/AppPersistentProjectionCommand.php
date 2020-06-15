<?php
declare(strict_types=1);

namespace App\Console;

use Illuminate\Console\Command;
use Plexikon\Chronicle\Support\Contracts\Chronicler\Chronicle;
use Plexikon\Chronicle\Support\Contracts\Projector\ProjectionProjector;
use Plexikon\Chronicle\Support\Contracts\Projector\ProjectorFactory;
use Plexikon\Chronicle\Support\Contracts\Projector\ProjectorManager;
use Plexikon\Chronicle\Support\Contracts\Projector\ReadModel;
use Plexikon\Reporter\Contracts\Message\Messaging;

/**
 * @method ReadModel readModel()
 * @method ProjectionProjector emit(Messaging $event)
 * @method ProjectionProjector linkTo(string $streamName, Messaging $event)
 */
class AppPersistentProjectionCommand extends Command
{
    protected function projectorManager(): ProjectorManager
    {
        return $this->getLaravel()->get(ProjectorManager::class);
    }

    protected function chronicle(): Chronicle
    {
        return $this->getLaravel()->get(Chronicle::class);
    }

    protected function withProjection(string $streamName, ?string $readModel): ProjectorFactory
    {
        pcntl_async_signals(true);

        $projectorManager = $this->projectorManager();

        $projection = $readModel
            ? $projectorManager->createReadModelProjection($streamName, $this->getLaravel()->make($readModel))
            : $projectorManager->createProjection($streamName);

        pcntl_signal(SIGINT, function () use ($projection, $streamName): void {
            $this->warn("Stopping projection $streamName");
            $projection->stop();
        });

        return $projection->withQueryFilter($projectorManager->projectionQueryFilter());
    }
}

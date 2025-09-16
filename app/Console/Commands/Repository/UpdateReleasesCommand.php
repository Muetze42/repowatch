<?php

namespace App\Console\Commands\Repository;

use App\Jobs\Repository\UpdateReleasesJob;
use App\Models\Repository;
use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

use function Laravel\Prompts\multiselect;

#[AsCommand(name: 'app:repository:update-releases')]
class UpdateReleasesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:repository:update-releases {repositoryIds?* : Optional list of repository IDs to update}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch job to update repository releases';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $repositoryIds = $this->argument('repositoryIds');

        if (empty($this->argument('repositoryIds'))) {
            $repositoryIds = multiselect(
                label: 'Which repositories should be updated?',
                options: Repository::pluck('package_name', 'id')
            );
        }

        Repository::whereIn('id', $repositoryIds)->get()->each(function (Repository $repository): void {
            $this->info(sprintf('Dispatching job for repository `%s`', $repository->package_name));

            UpdateReleasesJob::dispatch($repository);
        });
    }
}

<flux:main container>
  <flux:card class="space-y-theme">
    <flux:text>
      Select a package and two different releases to compare file changes. The tool will provide a clear overview of all created, deleted, and modified files.
    </flux:text>
    @foreach($repositories as $repository)
      <flux:card size="sm" wire-key="repository-{{ $repository->id }}">
        <div class="flex-1 flex gap-4">
          <flux:icon :icon="$repository->package_name" />
          <div class="flex-1">
            <flux:heading>{{ $repository->display_name }}</flux:heading>
            <flux:subheading size="sm">{{ $repository->description }}</flux:subheading>
            <flux:link :href="$repository->website_url" external>
              {{ $repository->website_url }}
            </flux:link>
            <div class="flex flex-col gap-0.5 mt-2" wire-key="repository-div-{{ $repository->id }}">
              <flux:text>Releases to compare: {{ number_format($repository->releases->count()) }}</flux:text>
              <flux:text>
                Latest Release:
              </flux:text>
              <x-tooltip-date :date="$repository->latest_release_date" wire-key="repository-tooltip-{{ $repository->id }}" />
            </div>
            <div class="mt-2">
              <flux:button variant="primary" icon="git-branch" :href="route('repositories.index', $repository->only(['vendor', 'name']))" wire:navigate>
                Compare <span class="font-mono">{{ $repository->package_name }}</span> releases
              </flux:button>
            </div>
          </div>
        </div>
      </flux:card>
    @endforeach
  </flux:card>
</flux:main>

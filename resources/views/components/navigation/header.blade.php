<flux:header container sticky class="bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
  <flux:brand :href="route('home')" :name="config('app.name')" class="max-sm:hidden" wire:navigate>
    <x-slot name="logo" class="bg-accent text-accent-foreground">
      <flux:icon.git-compare-arrows />
    </x-slot>
  </flux:brand>
  <flux:brand :href="route('home')" wire:navigate class="sm:hidden">
    <x-slot name="logo" class="bg-accent text-accent-foreground">
      <flux:icon.git-compare-arrows />
    </x-slot>
  </flux:brand>
  <flux:spacer />
  <flux:button x-data x-show="! $flux.dark" x-on:click="$flux.dark = ! $flux.dark" icon="moon" variant="subtle" aria-label="Toggle to light mode" />
  <flux:button x-data x-show="$flux.dark" x-on:click="$flux.dark = ! $flux.dark" icon="sun" variant="subtle" aria-label="Toggle to dark mode" />
  @auth
    <x-account.user-dropdown />
  @else
    <x-account.guest-dropdown />
  @endauth
</flux:header>

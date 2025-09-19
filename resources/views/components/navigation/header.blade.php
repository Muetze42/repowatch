<flux:header container sticky class="bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
  <flux:brand :href="route('home')" :name="config('app.name')" wire:navigate>
    <x-slot name="logo" class="bg-accent text-accent-foreground">
      <flux:icon.git-compare-arrows />
    </x-slot>
  </flux:brand>
  <flux:spacer />
  <flux:dropdown position="top" align="start">
    <flux:profile avatar="https://fluxui.dev/img/demo/user.png" />
    <flux:menu>
      <flux:menu.radio.group>
        <flux:menu.radio checked>Olivia Martin</flux:menu.radio>
        <flux:menu.radio>Truly Delta</flux:menu.radio>
      </flux:menu.radio.group>
      <flux:menu.separator />
      <flux:menu.item icon="arrow-right-start-on-rectangle">Logout</flux:menu.item>
    </flux:menu>
  </flux:dropdown>
</flux:header>

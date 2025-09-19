@php
  $classes = Flux::classes('[grid-area:header]')
      ->add('z-10 min-h-12')
      ->add('flex items-center px-6 lg:px-8')
      ->add('fixed bottom-0 inset-x-0')
      ->add('bg-zinc-50 dark:bg-zinc-900 border-t border-zinc-200 dark:border-zinc-700');
@endphp

<footer {{ $attributes->class($classes) }}>
  <div class="mx-auto w-full h-full [:where(&)]:max-w-7xl px-6 lg:px-8 flex items-center max-sm:flex-col justify-center sm:justify-between gap-2">
    <div>
      Built with ❤️ by
      <flux:link href="https://huth.it" external>Norman Huth</flux:link>
    </div>
    <div>
      <flux:button size="sm" icon="github" :href="config('project.repository')" icon:trailing="arrow-up-right" target="_blank">Source Code</flux:button>
    </div>
  </div>
</footer>

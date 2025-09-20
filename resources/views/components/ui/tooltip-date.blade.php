@props(['date'])

@php
if (! $date instanceof \Carbon\Carbon) {
  throw new \InvalidArgumentException(sprintf(
      'The date for the tooltip-date component must be an instance of Carbon\Carbon. %s was given.',
      gettype($date)
  ));
}
@endphp

<span
  x-data="{ localizedTime: '{{ $date->toDateTimeLocalString() }}' }"
  x-init="
        const dateObject = new Date('{{ $date->toIso8601String() }}');
        localizedTime = dateObject.toLocaleString(undefined, {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            timeZoneName: 'short'
        });
    "
  class="inline-flex items-center gap-1"
>
  {{ $date->toDateTimeString() }}
  {{ config('app.timezone') }}
  <flux:tooltip>
    <span class="text-accent">
      <flux:icon.fa.circle-info class="text-accent size-5" />
    </span>
    <flux:tooltip.content x-text="localizedTime" />
  </flux:tooltip>
</span>

<x-layouts.app.sidebar :title="$title ?? 'Miriam'">
    <flux:main>
        {{ $slot }}
    </flux:main>
   <livewire:styles />
   <livewire:scripts />

</x-layouts.app.sidebar>

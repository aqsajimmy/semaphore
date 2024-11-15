@props(['loading' => false])

<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150']) }}>
    @if ($loading && ($target = $attributes->wire('click')->value()))
    <div class="inline-flex items-center gap-2" wire:loading.remove wire:target="{{ $target }}">{{ $slot }}</div>
    <div class="inline-flex items-center" wire:loading wire:target="{{ $target }}">{{ $loading }}</div>
    @else
        {{ $slot }}
    @endif
</button>

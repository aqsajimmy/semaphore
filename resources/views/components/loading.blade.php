@props(['value'])
<div>
    <!-- People find pleasure in different ways. I find it in keeping my mind clear. - Marcus Aurelius -->
    <div wire:loading.delay wire:loading wire:target="{{ $attributes->get('wire:target') }}" wire:loading.class="dark:text-dark ps-1">
        {{ $value ?? $slot }}
    </div>
</div>

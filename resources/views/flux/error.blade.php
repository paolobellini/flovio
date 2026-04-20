@blaze

@props([
    'icon' => null,
    'bag' => 'default',
    'message' => null,
    'deep' => true,
    'nested' => true,
    'name' => null,
])

@php
$errorBag = $errors->getBag($bag);
$message ??= $name ? $errorBag->first($name) : null;

if ($nested === false) {
    $deep = false;
}

if ($name && (is_null($message) || $message === '') && filter_var($deep, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) !== false) {
    $message = $errorBag->first($name . '.*');
}

$classes = Flux::classes('mt-1 text-xs text-red-500 dark:text-red-400')
    ->add($message ? '' : 'hidden');
@endphp

<div role="alert" aria-live="polite" aria-atomic="true" {{ $attributes->class($classes) }} data-flux-error>
    <?php if ($message) : ?>
        {{ $message }}
    <?php endif; ?>
</div>

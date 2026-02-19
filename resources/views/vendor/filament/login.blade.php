<form wire:submit.prevent="authenticate" class="space-y-8">
    {{ $this->form }}

    <x-filament::button type="submit" form="authenticate" class="w-full">
        {{ __('filament::login.buttons.submit.label') }}
    </x-filament::button>

    <p class="text-center text-sm text-gray-500 dark:text-gray-400">
        Don't have an account? <a href="{{ url('/register') }}" class="text-primary-500 hover:underline font-medium dark:text-primary-400">Sign up</a>
    </p>
</form>

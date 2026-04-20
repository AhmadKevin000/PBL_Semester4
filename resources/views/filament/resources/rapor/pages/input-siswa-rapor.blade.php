<x-filament-panels::page>
    <form wire:submit.prevent="save" class="fi-form space-y-6">
        {{ $this->form }}

        <div class="fi-form-actions mt-6 flex flex-wrap items-center gap-3">
            @foreach($this->getFormActions() as $action)
                {{ $action }}
            @endforeach
        </div>
    </form>
</x-filament-panels::page>

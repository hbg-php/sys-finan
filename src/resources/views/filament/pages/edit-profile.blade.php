<x-filament-panels::page>
    <x-filament-panels::form>
        {{ $this->form }}
        <x-filament::button type="submit">
            Salvar
        </x-filament::button>

        <x-filament::button
            color="gray"
            type="button"
            onclick="history.back()"
        >
            Voltar
        </x-filament::button>
    </x-filament-panels::form>
</x-filament-panels::page>

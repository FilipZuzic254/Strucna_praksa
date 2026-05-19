<x-filament-widgets::widget>
    <x-filament::section>
        {{-- Widget content --}}
        <h2 class="fi-section-header-heading">Available Documents</h2>
        <div>
            @if ($this->documents->isEmpty())
                <p class="text-sm text-gray-500">No documents available for this item.</p>
            @else
                <ul class="space-y-2">
                    @foreach ($this->documents as $document)
                        <li>
                            <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="text-blue-600 hover:underline">
                                {{ $document->file_name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

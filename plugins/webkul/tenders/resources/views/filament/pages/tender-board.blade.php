<x-filament-panels::page>
    <div class="flex overflow-x-auto space-x-4 pb-4" x-data="{
        updateStatus(recordId, status) {
            $wire.updateStatus(recordId, status);
        }
    }">
        @foreach($this->getStatuses() as $status)
            <div class="flex-shrink-0 w-80 bg-gray-100 dark:bg-gray-900 rounded-lg p-4 flex flex-col h-full"
                 x-on:drop="
                    let recordId = $event.dataTransfer.getData('recordId');
                    let newStatus = '{{ $status->value }}';
                    updateStatus(recordId, newStatus);
                 "
                 x-on:dragover.prevent>
                
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-700 dark:text-gray-200">{{ $status->getLabel() }}</h3>
                    <span class="bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs font-medium px-2.5 py-0.5 rounded-full">
                        {{ $this->getRecords()->get($status->value)?->count() ?? 0 }}
                    </span>
                </div>

                <div class="flex-1 space-y-3 overflow-y-auto min-h-[200px]">
                    @foreach($this->getRecords()->get($status->value) ?? [] as $record)
                        <div class="bg-white dark:bg-gray-800 p-3 rounded shadow cursor-move hover:shadow-md transition-shadow duration-200 border border-gray-200 dark:border-gray-700"
                             draggable="true"
                             x-on:dragstart="$event.dataTransfer.setData('recordId', '{{ $record->id }}')">
                            
                            <div class="flex justify-between items-start mb-2">
                                <a href="{{ \Webkul\Tender\Filament\Resources\TenderOpportunityResource::getUrl('view', ['record' => $record]) }}" class="text-sm font-medium text-primary-600 hover:underline line-clamp-2">
                                    {{ $record->title }}
                                </a>
                            </div>

                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-2">
                                {{ $record->client_name }}
                            </div>

                            <div class="flex justify-between items-center text-xs text-gray-400 dark:text-gray-500">
                                <span>{{ $record->tender_number }}</span>
                                @if($record->submission_deadline)
                                    <span class="{{ $record->submission_deadline->isPast() ? 'text-danger-500' : '' }}">
                                        {{ $record->submission_deadline->format('M d') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>

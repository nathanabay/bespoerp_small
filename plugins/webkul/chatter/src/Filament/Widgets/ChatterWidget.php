<?php

namespace Webkul\Chatter\Filament\Widgets;

use Filament\Widgets\Widget;

class ChatterWidget extends Widget
{
    protected string $view = 'chatter::filament.widgets.chatter';

    protected int|string|array $columnSpan = 'full';

    public $record = null;

    protected static string $type = 'footer';

    public function mount($record = null)
    {
        $this->record = $record;
    }

    public static function canView(): bool
    {
        return true;
    }

    public function getRecord()
    {
        return $this->record;
    }

    protected function getViewData(): array
    {
        return [
            'record' => $this->record,
            'resourceClass' => \Filament\Facades\Filament::getModelResource($this->record::class) ?? '',
            'messageMailViewPath' => 'chatter::mail.message', // Default or config
            'followerMailViewPath' => 'chatter::mail.follower', // Default or config
            'isMessageActionVisible' => true,
            'isLogActionVisible' => true,
            'isActivityActionVisible' => true,
            'isFileActionVisible' => true,
            'isFollowerActionVisible' => true,
            'activityPlans' => collect(),
        ];
    }
}

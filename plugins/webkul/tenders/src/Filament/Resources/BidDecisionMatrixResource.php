<?php

namespace Webkul\Tender\Filament\Resources;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Slider;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Tender\Filament\Resources\BidDecisionMatrixResource\Pages;
use Webkul\Tender\Models\BidDecisionMatrix;
use Webkul\Chatter\Filament\Pages\Concerns\HasChatter;
use UnitEnum;
use BackedEnum;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Utilities\Get;

class BidDecisionMatrixResource extends Resource
{
    protected static ?string $model = BidDecisionMatrix::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-presentation-chart-line';

    protected static string|UnitEnum|null $navigationGroup = 'Tenders';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Tender Context')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('series')
                                    ->disabled()
                                    ->placeholder('BDM-TNDR-0001'),
                                Select::make('tender_id')
                                    ->label('Tender Opportunity')
                                    ->relationship('tender', 'title')
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                            ]),
                    ]),
                Section::make('Evaluation Scoring (0 - 10)')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Slider::make('win_probability')
                                    ->label('Win Probability')
                                    ->minValue(0)->maxValue(10)->step(1)->default(0)->live()
                                    ->afterStateUpdated(fn (Set $set, Get $get) => static::calculateOutcome($set, $get)),
                                Slider::make('profitability')
                                    ->label('Profitability')
                                    ->minValue(0)->maxValue(10)->step(1)->default(0)->live()
                                    ->afterStateUpdated(fn (Set $set, Get $get) => static::calculateOutcome($set, $get)),
                                Slider::make('strategic_fit')
                                    ->label('Strategic Fit')
                                    ->minValue(0)->maxValue(10)->step(1)->default(0)->live()
                                    ->afterStateUpdated(fn (Set $set, Get $get) => static::calculateOutcome($set, $get)),
                                Slider::make('resource_availability')
                                    ->label('Resource Availability')
                                    ->minValue(0)->maxValue(10)->step(1)->default(0)->live()
                                    ->afterStateUpdated(fn (Set $set, Get $get) => static::calculateOutcome($set, $get)),
                                Slider::make('technical_capability')
                                    ->label('Technical Capability')
                                    ->minValue(0)->maxValue(10)->step(1)->default(0)->live()
                                    ->afterStateUpdated(fn (Set $set, Get $get) => static::calculateOutcome($set, $get)),
                                Slider::make('client_relationship')
                                    ->label('Client Relationship')
                                    ->minValue(0)->maxValue(10)->step(1)->default(0)->live()
                                    ->afterStateUpdated(fn (Set $set, Get $get) => static::calculateOutcome($set, $get)),
                            ]),
                    ]),
                Section::make('Outcome')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('total_score')
                                    ->disabled()
                                    ->dehydrated()
                                    ->label('Total Aggregated Score'),
                                TextInput::make('suggested_decision')
                                    ->disabled()
                                    ->dehydrated()
                                    ->label('Suggested Decision')
                                    ->extraInputAttributes(['style' => 'font-size: 1.2rem; color: #1e40af; font-weight: bold;']),
                            ]),
                    ]),
            ]);
    }

    public static function calculateOutcome(Set $set, Get $get): void
    {
        $total = (int) $get('win_probability') +
                 (int) $get('profitability') +
                 (int) $get('strategic_fit') +
                 (int) $get('resource_availability') +
                 (int) $get('technical_capability') +
                 (int) $get('client_relationship');
                 
        $set('total_score', $total);
        
        if ($total >= 45) {
            $set('suggested_decision', 'Bid (High Priority)');
        } elseif ($total >= 30) {
            $set('suggested_decision', 'Bid (Medium Priority)');
        } else {
            $set('suggested_decision', 'No-Go');
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('series')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tender.title')
                    ->label('Tender')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_score')
                    ->sortable()
                    ->badge(),
                Tables\Columns\TextColumn::make('suggested_decision')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Bid (High Priority)' => 'success',
                        'Bid (Medium Priority)' => 'info',
                        'No-Go' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBidDecisionMatrices::route('/'),
            'create' => Pages\CreateBidDecisionMatrix::route('/create'),
            'view' => Pages\ViewBidDecisionMatrix::route('/{record}'),
            'edit' => Pages\EditBidDecisionMatrix::route('/{record}/edit'),
        ];
    }
}

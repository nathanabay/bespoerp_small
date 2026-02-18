<?php

namespace Webkul\Tender\Filament\Resources;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Tender\Filament\Resources\CostEstimationResource\Pages;
use Webkul\Tender\Models\CostEstimation;
use Webkul\Chatter\Filament\Pages\Concerns\HasChatter;
use UnitEnum;
use BackedEnum;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Utilities\Get;

class CostEstimationResource extends Resource
{
    protected static ?string $model = CostEstimation::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calculator';

    protected static string|UnitEnum|null $navigationGroup = 'Tenders';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(1)
                    ->schema([
                        Section::make('Tender Information')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('series')
                                            ->disabled()
                                            ->placeholder('CE-TNDR-0001'),
                                        Select::make('tender_id')
                                            ->label('Tender Opportunity')
                                            ->relationship('tender', 'title')
                                            ->required()
                                            ->searchable()
                                            ->preload(),
                                    ]),
                            ]),
                        Section::make('Bill of Quantities (BOQ)')
                            ->schema([
                                Repeater::make('items')
                                    ->relationship()
                                    ->schema([
                                        TextInput::make('description')
                                            ->required()
                                            ->columnSpan(2),
                                        TextInput::make('quantity')
                                            ->numeric()
                                            ->default(1)
                                            ->required()
                                            ->live()
                                            ->afterStateUpdated(fn (Set $set, Get $get) => static::calculateItemTotal($set, $get)),
                                        TextInput::make('uom')
                                            ->label('UOM'),
                                        TextInput::make('unit_cost')
                                            ->numeric()
                                            ->prefix('$')
                                            ->required()
                                            ->live()
                                            ->afterStateUpdated(fn (Set $set, Get $get) => static::calculateItemTotal($set, $get)),
                                        TextInput::make('total_cost')
                                            ->disabled()
                                            ->dehydrated()
                                            ->prefix('$')
                                            ->columnSpan(1),
                                    ])
                                    ->columns(6)
                                    ->live()
                                    ->afterStateUpdated(fn (Set $set, Get $get) => static::calculateGrandTotals($set, $get)),
                            ]),
                        Section::make('Margins & Final Price')
                            ->schema([
                                Grid::make(4)
                                    ->schema([
                                        TextInput::make('total_direct_cost')
                                            ->label('Total Direct Cost')
                                            ->disabled()
                                            ->dehydrated()
                                            ->prefix('$'),
                                        TextInput::make('overhead_percentage')
                                            ->label('Overhead (%)')
                                            ->numeric()
                                            ->default(15.00)
                                            ->live()
                                            ->afterStateUpdated(fn (Set $set, Get $get) => static::calculateGrandTotals($set, $get)),
                                        TextInput::make('profit_margin_percentage')
                                            ->label('Profit Margin (%)')
                                            ->numeric()
                                            ->default(10.00)
                                            ->live()
                                            ->afterStateUpdated(fn (Set $set, Get $get) => static::calculateGrandTotals($set, $get)),
                                        TextInput::make('total_price')
                                            ->label('Final Tender Price')
                                            ->disabled()
                                            ->dehydrated()
                                            ->prefix('$')
                                            ->extraInputAttributes(['style' => 'font-weight: bold']),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public static function calculateItemTotal(Set $set, Get $get): void
    {
        $quantity = (float) $get('quantity') ?: 0;
        $unitCost = (float) $get('unit_cost') ?: 0;
        
        $set('total_cost', number_format($quantity * $unitCost, 2, '.', ''));
    }

    public static function calculateGrandTotals(Set $set, Get $get): void
    {
        $items = $get('items') ?: [];
        $directCost = 0;
        
        foreach ($items as $item) {
            $directCost += (float) ($item['quantity'] ?? 0) * (float) ($item['unit_cost'] ?? 0);
        }
        
        $set('total_direct_cost', number_format($directCost, 2, '.', ''));
        
        $overheadPercent = (float) $get('overhead_percentage') ?: 0;
        $profitPercent = (float) $get('profit_margin_percentage') ?: 0;
        
        $overheadAmount = $directCost * ($overheadPercent / 100);
        $profitAmount = ($directCost + $overheadAmount) * ($profitPercent / 100);
        
        $totalPrice = $directCost + $overheadAmount + $profitAmount;
        
        $set('total_price', number_format($totalPrice, 2, '.', ''));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('series')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tender.title')
                    ->label('Tender')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_direct_cost')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Final Price')
                    ->money('USD')
                    ->weight('bold')
                    ->sortable(),
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
            'index' => Pages\ListCostEstimations::route('/'),
            'create' => Pages\CreateCostEstimation::route('/create'),
            'view' => Pages\ViewCostEstimation::route('/{record}'),
            'edit' => Pages\EditCostEstimation::route('/{record}/edit'),
        ];
    }
}

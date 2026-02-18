<?php

namespace Webkul\Tender\Filament\Resources;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Webkul\Support\Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Tender\Filament\Resources\PerformanceBondResource\Pages;
use Webkul\Tender\Models\PerformanceBond;
use Webkul\Chatter\Filament\Pages\Concerns\HasChatter;
use UnitEnum;
use BackedEnum;

class PerformanceBondResource extends Resource
{
    protected static ?string $model = PerformanceBond::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';

    protected static string|UnitEnum|null $navigationGroup = 'Tenders';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Tender & Bond Information')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('series')
                                    ->disabled()
                                    ->placeholder('PB-TNDR-0001'),
                                Select::make('tender_id')
                                    ->label('Tender Opportunity')
                                    ->relationship('tender', 'title')
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                                TextInput::make('bond_number')
                                    ->required()
                                    ->maxLength(255),
                                Select::make('bond_type')
                                    ->options([
                                        'bid_bond'                 => 'Bid Bond',
                                        'performance_bond'         => 'Performance Bond',
                                        'advance_payment_bond'     => 'Advance Payment Bond',
                                        'retention_bond'           => 'Retention Bond',
                                    ])
                                    ->required(),
                                TextInput::make('amount')
                                    ->numeric()
                                    ->prefix('$')
                                    ->required(),
                                TextInput::make('bank_name')
                                    ->required(),
                                DatePicker::make('expiry_date')
                                    ->required(),
                            ]),
                    ]),
                Section::make('Contract Milestones')
                    ->schema([
                        Repeater::make('contractMilestones')
                            ->relationship()
                            ->schema([
                                TextInput::make('description')
                                    ->required()
                                    ->columnSpan(2),
                                DatePicker::make('due_date'),
                                TextInput::make('amount')
                                    ->numeric()
                                    ->prefix('$'),
                                Select::make('status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'completed' => 'Completed',
                                        'cancelled' => 'Cancelled',
                                    ])
                                    ->default('pending'),
                            ])
                            ->columns(5),
                    ]),
                Section::make('Accounting Integration')
                    ->schema([
                        Select::make('journal_entry_id')
                            ->label('Related Journal Entry')
                            ->relationship('journalEntry', 'id')
                            ->searchable()
                            ->preload()
                            ->hint('Link to issuance or release journal entry'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('series')
                    ->sortable(),
                Tables\Columns\TextColumn::make('bond_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tender.title')
                    ->label('Tender')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bond_type')
                    ->badge(),
                Tables\Columns\TextColumn::make('amount')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('bank_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('expiry_date')
                    ->date()
                    ->sortable()
                    ->color(fn ($state) => $state < now() ? 'danger' : null),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('bond_type'),
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
            'index' => Pages\ListPerformanceBonds::route('/'),
            'create' => Pages\CreatePerformanceBond::route('/create'),
            'view' => Pages\ViewPerformanceBond::route('/{record}'),
            'edit' => Pages\EditPerformanceBond::route('/{record}/edit'),
        ];
    }
}

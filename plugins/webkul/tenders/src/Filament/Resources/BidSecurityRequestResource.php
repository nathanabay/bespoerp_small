<?php

namespace Webkul\Tender\Filament\Resources;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Tender\Filament\Resources\BidSecurityRequestResource\Pages;
use Webkul\Tender\Models\BidSecurityRequest;
use UnitEnum;
use BackedEnum;

class BidSecurityRequestResource extends Resource
{
    protected static ?string $model = BidSecurityRequest::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-lock-closed';

    protected static string|UnitEnum|null $navigationGroup = 'Tenders';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Request Details')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('series')
                                    ->disabled()
                                    ->placeholder('BSR-2026-0001'),
                                Select::make('tender_id')
                                    ->label('Tender')
                                    ->relationship('tender', 'title')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->reactive(),
                                TextInput::make('organization')
                                    ->label('Organization')
                                    ->disabled()
                                    ->helperText('Fetched from tender'),
                                Select::make('status')
                                    ->options([
                                        'draft' => 'Draft',
                                        'requested' => 'Requested',
                                        'issued' => 'Issued',
                                    ])
                                    ->default('draft')
                                    ->required(),
                                Select::make('type')
                                    ->label('Security Type')
                                    ->options([
                                        'CPO' => 'CPO (Chief Procurement Officer)',
                                        'Bank Guarantee' => 'Bank Guarantee',
                                        'Insurance Bond' => 'Insurance Bond',
                                        'Letter of Credit' => 'Letter of Credit',
                                    ])
                                    ->default('CPO')
                                    ->required(),
                                TextInput::make('amount')
                                    ->numeric()
                                    ->prefix('$')
                                    ->required(),
                                TextInput::make('validity_period_days')
                                    ->label('Validity Period (Days)')
                                    ->numeric()
                                    ->default(90)
                                    ->minValue(1)
                                    ->required(),
                                DatePicker::make('required_date')
                                    ->label('Required Date')
                                    ->required(),
                                DatePicker::make('expiry_date')
                                    ->label('Expiry Date')
                                    ->disabled()
                                    ->helperText('Auto-calculated from required date + validity period'),
                                TextInput::make('security_number')
                                    ->label('Security Number')
                                    ->placeholder('Filled when issued'),
                                TextInput::make('journal_entry')
                                    ->label('Journal Entry')
                                    ->disabled()
                                    ->helperText('Auto-linked from accounting'),
                                Select::make('bank_account_id')
                                    ->label('Bank Account')
                                    ->relationship('bankAccount', 'id')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                            ]),
                    ]),
                Section::make('ISO Approval Workflow')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Select::make('prepared_by_id')
                                    ->label('Prepared By')
                                    ->relationship('preparedBy', 'name')
                                    ->default(auth()->id())
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                Select::make('checked_by_id')
                                    ->label('Checked By (ISO)')
                                    ->relationship('checkedBy', 'name')
                                    ->searchable()
                                    ->preload(),
                                Select::make('approved_by_id')
                                    ->label('Approved By (ISO)')
                                    ->relationship('approvedBy', 'name')
                                    ->searchable()
                                    ->preload(),
                            ]),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('series')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('tender.title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'requested' => 'warning',
                        'issued' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('amount')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('preparedBy.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status'),
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
            'index' => Pages\ListBidSecurityRequests::route('/'),
            'create' => Pages\CreateBidSecurityRequest::route('/create'),
            'view' => Pages\ViewBidSecurityRequest::route('/{record}'),
            'edit' => Pages\EditBidSecurityRequest::route('/{record}/edit'),
        ];
    }
}

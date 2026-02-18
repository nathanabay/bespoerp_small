<?php

namespace Webkul\Tender\Filament\Resources;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Tender\Filament\Resources\TenderContentLibraryResource\Pages;
use Webkul\Tender\Models\TenderContentLibrary;
use UnitEnum;
use BackedEnum;

class TenderContentLibraryResource extends Resource
{
    protected static ?string $model = TenderContentLibrary::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-book-open';

    protected static string|UnitEnum|null $navigationGroup = 'Tenders';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Snippet Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('title')
                                    ->required(),
                                Select::make('category')
                                    ->options([
                                        'policy' => 'Policy',
                                        'bio'    => 'Bio',
                                        'spec'   => 'Specification',
                                        'other'  => 'Other',
                                    ])
                                    ->required(),
                            ]),
                        RichEditor::make('content')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category')
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category'),
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
            'index' => Pages\ListTenderContentLibraries::route('/'),
            'create' => Pages\CreateTenderContentLibrary::route('/create'),
            'view' => Pages\ViewTenderContentLibrary::route('/{record}'),
            'edit' => Pages\EditTenderContentLibrary::route('/{record}/edit'),
        ];
    }
}

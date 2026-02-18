<?php

namespace Webkul\Tender\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Actions\CreateAction;
use Webkul\Tender\Filament\Resources\CompetitorResource\Pages;
use Webkul\Tender\Models\Competitor;
use UnitEnum;
use BackedEnum;

class CompetitorResource extends Resource
{
    protected static ?string $model = Competitor::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-office-2';

    protected static string|UnitEnum|null $navigationGroup = 'Tenders';

    protected static ?string $slug = 'tender-competitors';

    protected static ?string $modelLabel = 'Competitor';

    protected static ?string $navigationLabel = 'Competitors (Master)';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Competitor Information')
                    ->tabs([
                        Tab::make('Basic Information')
                            ->icon('heroicon-o-building-office-2')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('competitor_name')
                                            ->label('Competitor Name')
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->maxLength(255),
                                        Select::make('company_type')
                                            ->label('Company Type')
                                            ->options([
                                                'Local' => 'Local',
                                                'International' => 'International',
                                                'Joint Venture' => 'Joint Venture',
                                            ]),
                                        TextInput::make('sector')
                                            ->label('Sector / Industry')
                                            ->maxLength(255),
                                    ]),
                            ]),
                        Tab::make('Contact Information')
                            ->icon('heroicon-o-phone')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('contact_person')
                                            ->label('Contact Person')
                                            ->maxLength(255),
                                        TextInput::make('phone')
                                            ->label('Phone Number')
                                            ->tel()
                                            ->maxLength(255),
                                        TextInput::make('email')
                                            ->label('Email')
                                            ->email()
                                            ->maxLength(255),
                                        TextInput::make('website')
                                            ->label('Website')
                                            ->url()
                                            ->maxLength(255),
                                    ]),
                            ]),
                        Tab::make('Competitive Intelligence')
                            ->icon('heroicon-o-chart-bar')
                            ->schema([
                                Select::make('typical_pricing_strategy')
                                    ->label('Typical Pricing Strategy')
                                    ->options([
                                        'Aggressive (Low Price)' => 'Aggressive (Low Price)',
                                        'Competitive' => 'Competitive',
                                        'Premium' => 'Premium',
                                    ]),
                                Textarea::make('strengths')
                                    ->label('Strengths')
                                    ->placeholder('What are their competitive advantages?')
                                    ->rows(4),
                                Textarea::make('weaknesses')
                                    ->label('Weaknesses')
                                    ->placeholder('Where do they struggle or underperform?')
                                    ->rows(4),
                                Textarea::make('notes')
                                    ->label('Additional Notes')
                                    ->placeholder('Past encounters, reputation, key wins/losses')
                                    ->rows(4),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('competitor_name')
                    ->label('Competitor Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('company_type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Local' => 'success',
                        'International' => 'info',
                        'Joint Venture' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('sector')
                    ->label('Sector')
                    ->searchable(),
                TextColumn::make('typical_pricing_strategy')
                    ->label('Pricing Strategy')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Aggressive (Low Price)' => 'danger',
                        'Competitive' => 'warning',
                        'Premium' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('phone')
                    ->label('Phone')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
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
            'index' => Pages\ListCompetitors::route('/'),
            'create' => Pages\CreateCompetitor::route('/create'),
            'view' => Pages\ViewCompetitor::route('/{record}'),
            'edit' => Pages\EditCompetitor::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources\Domains;

use App\Filament\Resources\Domains\Pages;
use App\Models\Domain;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ColorPicker;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use BackedEnum;

class DomainResource extends Resource
{
    protected static ?string $model = Domain::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-globe-alt';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Domain Utama')
                    ->components([
                        TextInput::make('name')
                            ->label('Nama Domain')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('weight')
                            ->label('Bobot Domain (%)')
                            ->numeric()
                            ->required(),

                        ColorPicker::make('color')
                            ->label('Warna Grafik'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Domain')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('weight')
                    ->label('Bobot')
                    ->suffix('%'),

                Tables\Columns\ColorColumn::make('color')
                    ->label('Warna'),
            ])
            ->recordActions([
                // Menggunakan class yang di-import dari Tables\Actions
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDomains::route('/'),
            'create' => Pages\CreateDomain::route('/create'),
            'edit' => Pages\EditDomain::route('/{record}/edit'),
        ];
    }
}

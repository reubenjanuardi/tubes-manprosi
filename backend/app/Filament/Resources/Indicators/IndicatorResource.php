<?php

namespace App\Filament\Resources\Indicators;

use App\Filament\Resources\Indicators\Pages;
use App\Models\Indicator;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use BackedEnum;

class IndicatorResource extends Resource
{
    protected static ?string $model = Indicator::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Pertanyaan Indikator')
                    ->components([ // Gunakan components()
                        Select::make('subdomain_id')
                            ->label('Subdomain')
                            ->relationship('subdomain', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),

                        TextInput::make('code')
                            ->label('Kode Indikator'),

                        Textarea::make('name')
                            ->label('Pertanyaan Indikator')
                            ->required()
                            ->rows(3),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('subdomain.domain.name')->label('Domain'),
                Tables\Columns\TextColumn::make('subdomain.name')->label('Subdomain'),
                Tables\Columns\TextColumn::make('code')->label('Kode'),
                Tables\Columns\TextColumn::make('name')->label('Pertanyaan')->limit(50),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIndicators::route('/'),
            'create' => Pages\CreateIndicator::route('/create'),
            'edit' => Pages\EditIndicator::route('/{record}/edit'),
        ];
    }
}

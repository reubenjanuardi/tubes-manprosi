<?php

namespace App\Filament\Resources\Subdomains;

use App\Filament\Resources\Subdomains\Pages;
use App\Models\Subdomain;
use Filament\Forms;
use Filament\Schemas\Schema; // Gunakan Schema sesuai pesan error
use Filament\Actions\DeleteAction; // Namespace baru v4
use Filament\Actions\EditAction;   // Namespace baru v4
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use BackedEnum;

class SubdomainResource extends Resource
{
    protected static ?string $model = Subdomain::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $recordTitleAttribute = 'name';
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Subdomain')
                    ->components([ // Gunakan components(), bukan schema()
                        Select::make('domain_id')
                            ->label('Pilih Domain')
                            ->relationship('domain', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),

                        TextInput::make('name')
                            ->label('Nama Subdomain')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('weight')
                            ->label('Bobot Subdomain (%)')
                            ->numeric()
                            ->required()
                            ->default(0),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('domain.name')
                    ->label('Domain')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Subdomain')
                    ->searchable(),
                Tables\Columns\TextColumn::make('weight')
                    ->label('Bobot (%)')
                    ->sortable(),
            ])
            ->actions([
                EditAction::make(),   // Gunakan class yang sudah di-import
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubdomains::route('/'),
            'create' => Pages\CreateSubdomain::route('/create'),
            'edit' => Pages\EditSubdomain::route('/{record}/edit'),
        ];
    }
}

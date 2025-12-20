<?php

namespace App\Filament\Resources\Assessments;

use App\Filament\Resources\Assessments\Pages\CreateAssessment;
use App\Filament\Resources\Assessments\Pages\EditAssessment;
use App\Filament\Resources\Assessments\Pages\ListAssessments;
use App\Filament\Resources\Assessments\Pages\ViewAssessment;
use App\Filament\Resources\Assessments\Schemas\AssessmentForm;
use App\Filament\Resources\Assessments\Schemas\AssessmentInfolist;
use App\Filament\Resources\Assessments\Tables\AssessmentsTable;
use App\Models\Assessment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables;

class AssessmentResource extends Resource
{
    protected static ?string $model = Assessment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'org_name';

    public static function form(Schema $schema): Schema
    {
        return AssessmentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AssessmentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('org_name')
                    ->label('Organisasi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_score')
                    ->label('Skor Akhir')
                    ->numeric(decimalPlaces: 1)
                    ->sortable(),
                Tables\Columns\TextColumn::make('maturity_level')
                    ->label('Level Kematangan')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Initial' => 'danger',
                        'Managed' => 'warning',
                        'Defined' => 'primary',
                        'Quantitatively Managed' => 'info',
                        'Optimizing' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime()
                    ->sortable(),
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
            'index' => ListAssessments::route('/'),
            'create' => CreateAssessment::route('/create'),
            'view' => ViewAssessment::route('/{record}'),
            'edit' => EditAssessment::route('/{record}/edit'),
        ];
    }
}

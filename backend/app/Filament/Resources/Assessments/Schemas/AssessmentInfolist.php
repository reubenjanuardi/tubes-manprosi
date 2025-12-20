<?php

namespace App\Filament\Resources\Assessments\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AssessmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('user.name')
                    ->label('User')
                    ->placeholder('-'),
                TextEntry::make('organization.name')
                    ->label('Organization')
                    ->placeholder('-'),
                TextEntry::make('org_name'),
                TextEntry::make('org_type'),
                TextEntry::make('assessor_name'),
                TextEntry::make('assessor_position'),
                TextEntry::make('assessment_date')
                    ->date(),
                TextEntry::make('total_score')
                    ->numeric(),
                TextEntry::make('maturity_level'),
                TextEntry::make('status'),
                TextEntry::make('completed_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}

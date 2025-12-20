<?php

namespace App\Filament\Resources\Assessments\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AssessmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name'),
                Select::make('organization_id')
                    ->relationship('organization', 'name'),
                TextInput::make('org_name')
                    ->required(),
                TextInput::make('org_type')
                    ->required(),
                TextInput::make('assessor_name')
                    ->required(),
                TextInput::make('assessor_position')
                    ->required(),
                DatePicker::make('assessment_date')
                    ->required(),
                TextInput::make('total_score')
                    ->required()
                    ->numeric(),
                TextInput::make('maturity_level')
                    ->required(),
                TextInput::make('status')
                    ->required()
                    ->default('completed'),
                DateTimePicker::make('completed_at'),
            ]);
    }
}

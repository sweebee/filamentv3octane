<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupporterResource\Pages;
use App\Filament\Resources\SupporterResource\RelationManagers;
use App\Models\Group;
use App\Models\Supporter;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SupporterResource extends Resource
{
    protected static ?string $model = Supporter::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('first_name')->label('Voornaam')->required(),
                        Forms\Components\TextInput::make('last_name')->label('Achternaam')->required(),
                    ]),
                    Forms\Components\Select::make('group_id')
                                           ->options(Group::pluck('name', 'id'))
                                           ->createOptionForm([
                                               Forms\Components\TextInput::make('name')->label('Naam'),
                                           ])
                                           ->createOptionUsing(function($data){
                                                $group = new Group();
                                                $group->name = $data['name'];
                                                $group->save();
                                                return $group->id;
                                           })
                                           ->label('Groep')
                ]),
                Forms\Components\Section::make('Contactgegevens')->schema([
                    Forms\Components\TextInput::make('email')->label('E-mailadres')->email()->required(),
                    Forms\Components\TextInput::make('telephone')->label('Telefoonnummer')->tel(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')->label('Naam'),
                Tables\Columns\TextColumn::make('email')->label('E-mailadres'),
                Tables\Columns\TextColumn::make('group.name')->label('Groep'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListSupporters::route('/'),
            'create' => Pages\CreateSupporter::route('/create'),
            'edit' => Pages\EditSupporter::route('/{record}/edit'),
        ];
    }
}

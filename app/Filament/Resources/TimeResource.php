<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Room;
use App\Models\Time;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Card;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\TimeResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TimeResource\RelationManagers;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;

class TimeResource extends Resource
{
    protected static ?string $model = Time::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    public static function form(Form $form): Form
    {
        $rooms = Room::pluck('name', 'id')->toArray();

        return $form
        ->schema([
            Card::make()->schema([
                Select::make('room_id')
                    ->label('Room')
                    ->options($rooms)
                    ->required(),
                DateTimePicker::make('start_time')
                    ->label('Start Time')
                    ->required(),
                DateTimePicker::make('end_time')
                    ->label('End Time')
                    ->after('start_time')
                    ->required(),
            ])->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('room.name')->sortable()->searchable(),
                TextColumn::make('start_time')->dateTime()->sortable()->searchable(),
                TextColumn::make('end_time')->dateTime()->sortable()->searchable(),
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
            'index' => Pages\ListTimes::route('/'),
            'create' => Pages\CreateTime::route('/create'),
            'edit' => Pages\EditTime::route('/{record}/edit'),
        ];
    }
}

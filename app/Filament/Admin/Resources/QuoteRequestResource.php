<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\QuoteRequestResource\Pages;
use App\Models\QuoteRequest;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class QuoteRequestResource extends Resource
{
    protected static ?string $model = QuoteRequest::class;

    protected static ?string $navigationLabel = 'Заявки КП';

    protected static ?string $modelLabel = 'Заявка КП';

    protected static ?string $pluralModelLabel = 'Заявки КП';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Контакты')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->label('Имя'),
                        TextInput::make('company')
                            ->label('Компания'),
                        TextInput::make('phone')
                            ->required()
                            ->label('Телефон'),
                        TextInput::make('email')
                            ->email()
                            ->label('Email'),
                    ])
                    ->columns(2),

                Section::make('Запрос')
                    ->schema([
                        TextInput::make('product_interest')
                            ->label('Интересующая продукция'),
                        TextInput::make('quantity')
                            ->label('Объем заказа'),
                        Textarea::make('message')
                            ->rows(4)
                            ->label('Сообщение'),
                        Select::make('status')
                            ->options([
                                'new' => 'Новая',
                                'in_progress' => 'В работе',
                                'answered' => 'Отвечено',
                                'closed' => 'Закрыто',
                            ])
                            ->default('new')
                            ->label('Статус'),
                        Textarea::make('notes')
                            ->rows(3)
                            ->label('Примечания'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('Имя'),
                Tables\Columns\TextColumn::make('company')
                    ->searchable()
                    ->label('Компания'),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Телефон'),
                Tables\Columns\TextColumn::make('product_interest')
                    ->label('Продукция'),
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        'new' => 'Новая',
                        'in_progress' => 'В работе',
                        'answered' => 'Отвечено',
                        'closed' => 'Закрыто',
                    ])
                    ->label('Статус'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Дата'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'new' => 'Новая',
                        'in_progress' => 'В работе',
                        'answered' => 'Отвечено',
                        'closed' => 'Закрыто',
                    ])
                    ->label('Статус'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuoteRequests::route('/'),
            'create' => Pages\CreateQuoteRequest::route('/create'),
            'edit' => Pages\EditQuoteRequest::route('/{record}/edit'),
        ];
    }
}

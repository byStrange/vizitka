<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ProductResource\Pages;
use App\Models\Category;
use App\Models\Product;
use App\Services\ImageOptimizer;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationLabel = 'Продукция';

    protected static ?string $modelLabel = 'Товар';

    protected static ?string $pluralModelLabel = 'Продукция';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Основное')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name.ru')
                                    ->label('Название (RU)')
                                    ->required(),
                                TextInput::make('name.uz')
                                    ->label('Nomi (UZ)'),
                            ]),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('description.ru')
                                    ->label('Описание (RU)'),
                                TextInput::make('description.uz')
                                    ->label('Tavsif (UZ)'),
                            ]),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true),
                        TextInput::make('sku')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->label('Артикул'),
                        Select::make('category_id')
                            ->options(fn (): array => Category::orderBy('sort_order')->orderBy('id')->get()->mapWithKeys(fn ($c) => [$c->id => $c->slug])->toArray())
                            ->required()
                            ->label('Категория'),
                        TextInput::make('moq')
                            ->label('Мин. заказ'),
                        TextInput::make('lead_time')
                            ->label('Срок изготовления'),
                    ])
                    ->columns(2),

                Section::make('Технические характеристики')
                    ->schema([
                        KeyValue::make('specs')
                            ->keyLabel('Параметр (RU)')
                            ->valueLabel('Значение (RU)')
                            ->addable(true)
                            ->deletable(true),
                    ]),

                Section::make('Настройки')
                    ->schema([
                        FileUpload::make('image')
                            ->image()
                            ->disk('public')
                            ->directory('products')
                            ->label('Главное изображение')
                            ->saveUploadedFileUsing(function ($file, $statePath) {
                                return app(ImageOptimizer::class)->compressAndStore($file, 'products');
                            }),
                        FileUpload::make('gallery')
                            ->multiple()
                            ->image()
                            ->disk('public')
                            ->directory('products/gallery')
                            ->label('Галерея')
                            ->saveUploadedFileUsing(function ($file, $statePath) {
                                return app(ImageOptimizer::class)->compressAndStore($file, 'products/gallery');
                            }),
                        Grid::make(3)
                            ->schema([
                                Toggle::make('is_new')
                                    ->label('Новинка'),
                                Toggle::make('is_featured')
                                    ->label('В featured'),
                                Toggle::make('is_active')
                                    ->label('Активен'),
                                Toggle::make('customization')
                                    ->label('Кастомизация'),
                            ]),
                        TextInput::make('discount_percent')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->label('Скидка %'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->defaultImageUrl(fn () => '/images/placeholder.png')
                    ->square()
                    ->size(40),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->limit(40),
                Tables\Columns\TextColumn::make('sku')
                    ->searchable()
                    ->label('Артикул'),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Категория')
                    ->formatStateUsing(fn ($record) => json_decode($record->category?->name, true)['ru'] ?? $record->category?->name ?? '—'),
                Tables\Columns\IconColumn::make('is_new')
                    ->boolean()
                    ->label('Новинка'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Активен'),
                Tables\Columns\IconColumn::make('customization')
                    ->boolean()
                    ->label('Кастом'),
                Tables\Columns\TextColumn::make('discount_percent')
                    ->label('Скидка %'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->options(fn (): array => Category::orderBy('sort_order')->orderBy('id')->get()->mapWithKeys(fn ($c) => [$c->id => $c->name])->toArray())
                    ->label('Категория'),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Активность'),
                Tables\Filters\TernaryFilter::make('is_new')
                    ->label('Новинка'),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}

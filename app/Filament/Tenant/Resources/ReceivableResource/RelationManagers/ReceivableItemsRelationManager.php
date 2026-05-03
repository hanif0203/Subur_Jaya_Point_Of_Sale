<?php

namespace App\Filament\Tenant\Resources\ReceivableResource\RelationManagers;

use App\Models\Tenants\Setting;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ReceivableItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'receivableItems';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return can('lihat piutang');
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('amount')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('price')
                    ->translateLabel()
                    ->money(Setting::get('currency', 'IDR')),
                Tables\Columns\TextColumn::make('subtotal')
                    ->translateLabel()
                    ->money(Setting::get('currency', 'IDR')),
            ])
            ->filters([
                //
            ]);
    }

    public function isReadOnly(): bool
    {
        return true;
    }
}
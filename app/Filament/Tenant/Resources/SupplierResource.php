<?php

namespace App\Filament\Tenant\Resources;

use App\Filament\Tenant\Resources\SupplierResource\Pages;
use App\Models\Tenants\Supplier;
use App\Traits\HasTranslatableResource;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SupplierResource extends Resource
{
    use HasTranslatableResource;

    protected static ?string $model = Supplier::class;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    public static function canViewAny(): bool
    {
        return can('lihat supplier');
    }

    public static function canCreate(): bool
    {
        return can('buat supplier');
    }

    public static function canEdit($record): bool
    {
        return can('ubah supplier');
    }

    public static function canDelete($record): bool
    {
        return can('hapus supplier');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Supplier::form());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(Supplier::columns())
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
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
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}
<?php

namespace App\Filament\Tenant\Resources\StockOpnameResource\RelationManagers;

use App\Constants\StockOpnameStatus;
use App\Filament\Tenant\Resources\StockOpnameResource\Traits\HasStockOpnameItemForm;
use App\Filament\Tenant\Resources\Traits\RefreshThePage;
use App\Models\Tenants\StockOpnameItem;
use App\Services\Tenants\StockOpnameItemService;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class StockOpnameItemsRelationManager extends RelationManager
{
    use HasStockOpnameItemForm, RefreshThePage;

    protected static string $relationship = 'stockOpnameItems';

    private StockOpnameItemService $soIService;

    public function __construct()
    {
        $this->soIService = new StockOpnameItemService();
    }

    // Permission check untuk view relation
    public static function canViewForRecord($ownerRecord, string $pageClass): bool
    {
        return can('lihat item stok opname') || can('lihat stok opname');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema($this->get('product'));
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\SelectColumn::make('adjustment_type')
                    ->options([
                        'broken' => __('Broken'),
                        'lost' => __('Lost'),
                        'expired' => __('Expired'),
                        'manual_input' => __('Manual Input'),
                        'match' => __('Match'),
                    ])
                    ->translateLabel()
                    ->disabled(fn () => $this->isReadOnly() || !can('ubah item stok opname'))
                    ->afterStateUpdated(function (StockOpnameItem $soi, $state) {
                        if (!can('ubah item stok opname')) {
                            return;
                        }

                        $adjusment_stock = $soi->current_stock - $soi->actual_stock;

                        $this->soIService->update($soi, [
                            'missing_stock' => $adjusment_stock,
                            'adjustment_type' => $state,
                        ]);
                    }),
                Tables\Columns\TextColumn::make('current_stock')
                    ->translateLabel()
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextInputColumn::make('actual_stock')
                    ->type('number')
                    ->translateLabel()
                    ->disabled(fn () => $this->isReadOnly() || !can('ubah item stok opname'))
                    ->afterStateUpdated(function (StockOpnameItem $soi, $state) {
                        if (!can('ubah item stok opname')) {
                            return;
                        }

                        $adjusment_stock = $soi->current_stock - $state;
                        $this->soIService->update($soi, [
                            'actual_stock' => $state,
                            'missing_stock' => $adjusment_stock,
                        ]);
                    })
                    ->rules(['required', 'numeric', 'min:0']),
                Tables\Columns\TextColumn::make('missing_stock')
                    ->translateLabel()
                    ->numeric()
                    ->sortable()
                    ->color(fn ($state) => match (true) {
                        $state > 0 => 'danger',
                        $state < 0 => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\ImageColumn::make('attachment')
                    ->translateLabel()
                    ->circular(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->visible(fn () => can('buat item stok opname') && !$this->isReadOnly())
                    ->mutateFormDataUsing(function (array $data): array {
                        // Auto calculate missing stock
                        $data['missing_stock'] = ($data['current_stock'] ?? 0) - ($data['actual_stock'] ?? 0);
                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn () => can('ubah item stok opname') && !$this->isReadOnly())
                    ->mutateFormDataUsing(function (array $data): array {
                        // Auto calculate missing stock
                        $data['missing_stock'] = ($data['current_stock'] ?? 0) - ($data['actual_stock'] ?? 0);
                        return $data;
                    }),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => can('hapus item stok opname') && !$this->isReadOnly()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => can('hapus item stok opname') && !$this->isReadOnly()),
                ]),
            ])
            ->emptyStateHeading(__('Tidak ada data yang ditemukan'))
            ->emptyStateDescription(__('Buat stock opname item untuk memulai.'))
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->visible(fn () => can('buat item stok opname') && !$this->isReadOnly()),
            ]);
    }

    public function isReadOnly(): bool
    {
        $so = $this->getOwnerRecord();
        return $so->status == StockOpnameStatus::approved;
    }
}
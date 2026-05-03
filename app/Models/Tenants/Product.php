<?php

namespace App\Models\Tenants;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Number;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin IdeHelperProduct
 */
class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id', 'hero_images_url', 'expired'];

    protected $appends = ['hero_image'];

    protected $casts = [
        'images' => 'array',
    ];

    private int $expiredDay = 20;

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class)
            ->where('is_ready', 1);
    }

    public function CartItems(): HasMany
    {
        return $this->hasMany(CartItem::class)
            ->where('user_id', Filament::auth()->id());
    }

    public function scopeStockLatestCalculateIn()
    {
        $usingFifoPrice = Setting::get('selling_method', env('SELLING_METHOD', 'fifo')) == 'fifo';
        $usingNormalPrice = Setting::get('selling_method', env('SELLING_METHOD', 'fifo')) == 'normal';
        $usingLifoPrice = Setting::get('selling_method', env('SELLING_METHOD', 'fifo')) == 'lifo';

        return $this
            ->stocks()
            ->where('type', 'in')
            ->when($usingNormalPrice, fn(Builder $query) => $query->orderBy('date')->latest())
            ->when($usingFifoPrice, fn(Builder $query) => $query
                ->where('stock', '>', 0)
                ->orderBy('created_at')->orderBy('date'))
            ->when($usingLifoPrice, fn(Builder $query) => $query
                ->where('stock', '>', 0)
                ->orderByDesc('created_at')->orderByDesc('date'));
    }

    public function stockCalculate(): Attribute
    {
        return Attribute::make(
            get: function () {
                $stock = $this
                    ->stockLatestCalculateIn()
                    ->sum('stock');

                return $stock;
            },
            set: fn($value) => $value
        );
    }

    public function initialPriceCalculate(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $stock = $this
                    ->stockLatestCalculateIn();
                if ($stock?->first() == null) {
                    return $value;
                }

                return $stock->first()->initial_price;
            },
            set: fn($value) => $value
        );
    }

    public function sellingPriceCalculate(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $stock = $this
                    ->stockLatestCalculateIn();
                if ($stock?->first() == null) {
                    return $value;
                }

                return $stock->first()->selling_price;
            },
            set: fn($value) => $value
        );
    }

    public function sellingPriceLabelCalculate(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                return Number::currency($this->initial_price, Setting::get('currency', 'IDR'));
            },
            set: fn($value) => $value
        );
    }

    // ✅ ACCESSOR: heroImages (array) - Support all formats
    public function heroImages(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $result = [];
                
                // Prioritas 1: hero_image (singular) - New format
                $heroImage = $this->attributes['hero_image'] ?? null;
                if ($heroImage) {
                    $url = $this->getImageUrl($heroImage);
                    if ($url) {
                        $result[] = $url;
                    }
                }
                
                // Prioritas 2: images (JSON array) - New format
                $images = $this->attributes['images'] ?? null;
                if ($images) {
                    $imagesArray = is_string($images) ? json_decode($images, true) : $images;
                    if (is_array($imagesArray)) {
                        foreach ($imagesArray as $img) {
                            $url = $this->getImageUrl($img);
                            if ($url) {
                                $result[] = $url;
                            }
                        }
                    }
                }
                
                // Prioritas 3: hero_images (comma-separated) - Old format
                if (empty($result) && $value) {
                    $oldImages = is_string($value) ? explode(',', $value) : (is_array($value) ? $value : []);
                    foreach ($oldImages as $img) {
                        $img = trim($img);
                        if ($img) {
                            $url = $this->getImageUrl($img);
                            if ($url) {
                                $result[] = $url;
                            }
                        }
                    }
                }
                
                return $result;
            },
            set: fn($value) => $value ? Arr::join(is_array($value) ? $value : $value->toArray(), ',') : null
        );
    }

    // ✅ ACCESSOR: heroImage (string) - Return first image URL
    public function heroImage(): Attribute
    {
        return Attribute::make(
            get: function () {
                // Prioritas 1: hero_image (singular) - New format
                $heroImage = $this->attributes['hero_image'] ?? null;
                if ($heroImage) {
                    $url = $this->getImageUrl($heroImage);
                    if ($url) {
                        return $url;
                    }
                }
                
                // Prioritas 2: images array (first image) - New format
                $images = $this->attributes['images'] ?? null;
                if ($images) {
                    $imagesArray = is_string($images) ? json_decode($images, true) : $images;
                    if (is_array($imagesArray) && !empty($imagesArray)) {
                        $url = $this->getImageUrl($imagesArray[0]);
                        if ($url) {
                            return $url;
                        }
                    }
                }
                
                // Prioritas 3: hero_images (comma-separated, first image) - Old format
                $heroImages = $this->attributes['hero_images'] ?? null;
                if ($heroImages) {
                    $oldImages = is_string($heroImages) ? explode(',', $heroImages) : (is_array($heroImages) ? $heroImages : []);
                    $oldImages = array_map('trim', $oldImages);
                    $oldImages = array_filter($oldImages);
                    
                    if (!empty($oldImages)) {
                        $url = $this->getImageUrl($oldImages[0]);
                        if ($url) {
                            return $url;
                        }
                    }
                }
                
                // Fallback: placeholder
                return asset('images/placeholder-product.png');
            }
        );
    }

    // ✅ HELPER METHOD: Convert path to full URL
    private function getImageUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        // Clean path
        $path = trim($path);

        // Jika sudah full URL
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // Remove leading slash
        $path = ltrim($path, '/');

        // Try storage disk first
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->url($path);
        }

        // Fallback: return asset path
        return asset('storage/' . $path);
    }

    public function netProfit(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->selling_price - $this->initial_price
        );
    }

    public function scopeNearestExpiredProduct(Builder $builder)
    {
        return $builder->whereHas('stocks', function (Builder $builder) {
            $nearestExpired = now()->addDay($this->expiredDay);

            return $builder
                ->whereDate('expired', '<=', $nearestExpired);
        });
    }

    public function expiredStock(): Attribute
    {
        return Attribute::make(
            get: function () {
                $nearestExpired = now()->addDay($this->expiredDay);

                return $this
                    ->stocks()
                    ->where('stock', '>', 0)
                    ->whereDate('expired', '<=', $nearestExpired)->latest()->first();
            }
        );
    }

    public function hasExpiredStock(): Attribute
    {
        return Attribute::make(
            get: function () {
                $nearestExpired = now()->addDay($this->expiredDay);

                return $this->stocks()
                    ->where('stock', '>', 0)
                    ->whereDate('expired', '<=', $nearestExpired)->exists();
            }
        );
    }

    public function setExpiredDay(int $day)
    {
        $this->expiredDay = $day;

        return $this;
    }

    public function sellingDetails(): HasMany
    {
        return $this->hasMany(SellingDetail::class);
    }

    public function scopeInActivate(Builder $builder): Builder
    {
        return $builder->where('show', false);
    }

    public function priceUnits(): HasMany
    {
        return $this->hasMany(PriceUnit::class);
    }

    public function barcodes(): HasMany
    {
        return $this->hasMany(Barcode::class);
    }

    public function primaryBarcode(): HasMany
    {
        return $this->hasMany(Barcode::class)->where('type', 'primary')->where('is_active', true);
    }

    public static function findByBarcodeOrSku(string $code): ?Product
    {
        $product = Barcode::findProductByCode($code);

        if ($product) {
            return $product;
        }
        return static::where('sku', $code)->first();
    }
}
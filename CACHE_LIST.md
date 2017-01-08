#Product list

1. Sorting product ID list

```php
$arCacheTags = [Plugin::CACHE_TAG, Product::CACHE_TAG_LIST];
$sCacheKey = $sSorting;
```

2. Product ID list filtered by category ID
```php
$arCacheTags = [Plugin::CACHE_TAG, Product::CACHE_TAG_LIST, Category::CACHE_TAG_ELEMENT];
$sCacheKey = $iCategoryID;
```

3. Sorting product ID list filtered by category ID
```php
$arCacheTags = [Plugin::CACHE_TAG, Product::CACHE_TAG_LIST, Category::CACHE_TAG_ELEMENT];
$sCacheKey = implode('_', [$sSorting, $iCategoryID]);
```

4. Active product ID list
```php
$arCacheTags = [Plugin::CACHE_TAG, Product::CACHE_TAG_LIST];
$sCacheKey = Product::CACHE_TAG_LIST;
```
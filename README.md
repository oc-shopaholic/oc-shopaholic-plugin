# Plugin settings
    Backend -> Settings -> Shopaholic:
 1. **Настройки валидации:**
    - Ограничение максимальной длинны превью-текста для категории. Для категорий будет добавлено правило валидации для поля "preview_text".
    - Ограничение максимальной длинны превью-текста для товаров. Для товаров будет добавлено правило валидации для поля "preview_text".
    - Ограничение максимальной длинны превью-текста для товарных предложений. Для товарных предложений будет добавлено правило валидации для поля "preview_text".
 2. **Настройка формата цен:**
    - Число знаков после запятой.
    - Разделитель дробной части.
    - Разделитель разряда тысяч.
    - Обозначение валюты.
 3. **Настройка отображения полей в административной панели:**
    - Скрыть/отобразить поля для категорий: preview_text, description, preview_image, images, code, external_id.
    - Скрыть/отобразить поля  для товаров: category, brand, preview_text, description, preview_image, images, code, external_id.
    - Скрыть/отобразить поля  для товарных предложений: quantity, price, old_price, preview_text, description, preview_image, images, code, external_id.

# Компонент "ProductPage"
**Настройки компонента:**
  - Отображать 404 страницу, если товар не был найден.
  - Значение поля "slug" для поиска товара. Использование: если URL страницы "/product/:slug", то необходимо указать в настройках компонента: slug = "{{ :slug }}"

**Использование:**
Компонент удобно использовать для отображения страницы товара. Поиск товара будет производится по значению поля "slug". Пример получения данных товара:

```html
{% set obProduct = ProductPage.get %}
...
 
<div>{{ obProduct.name }}</div>
<div>{{ obProperty.description }}</div>
```

# Компонент "ProductData"
**Использование:**
Компонент удобно использовать для получения данных товара по ID.

```html
{% set obProduct = ProductData.get(10) %}
...
 
<div>{{ obProduct.name }}</div>
<div>{{ obProduct.description }}</div>
```

Данные о товаре можно получить ajax-запросом:

**Example 1**

```js
$.request('ProductData::onGetProductData', {
    data: {'product_id': 10},
    success: function(data) {
        //do something with product data (id=10)
    }
});
```
**Example 2**

```js
$.request('ProductData::onAjaxRequest', {
    data: {'product_id': 10},
    update: {'product-data': '.product-data-wrapper'}
});
```
```html 
{% set obProduct = ProductData.get() %}
...
 
<div>{{ obProduct.name }}</div>
<div>{{ obProduct.description }}</div>
```

# Product data
```php
[
    'id'                => 10,
    'name'              => 'product name',
    'slug'              => 'product_name',
    'code'              => 'vendor code',
    'category_id'       => 2,
    'preview_text'      => 'preview text about product',
    'preview_image'     => [
        'path'  => '/storage/app/upload/...',
        'title' => 'image title',
        'alt'   => 'image alt',
    ],
    'description'       => 'product description',
    'images'            => [
        [
            'path'  => '/storage/app/upload/...',
            'title' => 'image title',
            'alt'   => 'image alt',
        ],[
            'path'  => '/storage/app/upload/...',
            'title' => 'image title',
            'alt'   => 'image alt',
        ],
    ],
    'offer'             => [            //Array with offer data
        1 => [...],                     //Array with offer data (id=1)
        6 => [...],                     //Array with offer data (id=6)
        18 => [...],                    //Array with offer data (id=18)
    ],
    'offer_id'          => [1, 6, 18],  //Array with offer ID
]
```

#Offer data
```php
[
    'id'                => 6,
    'name'              => 'offer name',
    'code'              => 'vendor code',
    'preview_text'      => 'preview text about product',
    'product_id'        => 10,
    'preview_image'     => [
        'path'  => '/storage/app/upload/...',
        'title' => 'image title',
        'alt'   => 'image alt',
    ],
    'description'       => 'product description',
    'images'            => [
        [
            'path'  => '/storage/app/upload/...',
            'title' => 'image title',
            'alt'   => 'image alt',
        ],[
            'path'  => '/storage/app/upload/...',
            'title' => 'image title',
            'alt'   => 'image alt',
        ],
    ],
    'price'             => '1 250,50',
    'old_price'         => '1 350',
    'price_value'       => '1250.5',
    'old_price_value'   => '1350',
    'quantity'          => '4',
]
```

# Компонент "Currency"

**Использование:**
Компонент предназначен для получение значения валюты из настроек плагина:

```html
<div>{{ Currency.get }}</div>
```

# Компонент "CategoryPage"
**Настройки компонента:**
  - Отображать 404 страницу, если категория не была найдена.
  - Значение поля "slug" для поиска категории. Использование: если URL страницы "/catalog/:slug", то необходимо указать в настройках компонента: slug = "{{ :slug }}"

**Использование:**
Компонент удобно использовать для отображения страницы категори. Поиск категории будет производится по значению поля "slug". Пример получения данных категории:

```html
{% set obCategory = CategoryPage.get %}
...
 
<div>{{ obCategory.name }}</div>
<div>{{ obCategory.description }}</div>
```

# Компонент "CategoryData"
**Использование:**
Компонент удобно использовать для получения данных категории по ID.

```html
{% set obCategory = CategoryData.get(10) %}
...
 
<div>{{ obCategory.name }}</div>
<div>{{ obCategory.description }}</div>
```

Данные о категории можно получить ajax-запросом:

**Example 1**

```js
$.request('CategoryData::onGetCategoryData', {
    data: {'category_id': 10},
    success: function(data) {
        //do something with category data (id=10)
    }
});
```
**Example 2**

```js
$.request('CategoryData::onAjaxRequest', {
    data: {'category_id': 10},
    update: {'category-data': '.category-data-wrapper'}
});
```
```html 
{% set obCategory = CategoryData.get() %}
...
 
<div>{{ obCategory.name }}</div>
<div>{{ obCategory.description }}</div>
```

# Category data
```php
[
    'id'                => 10,
    'name'              => 'category name',
    'slug'              => 'category_name',
    'code'              => 'vendor code',
    'nest_depth'        => 1,
    'preview_text'      => 'preview text about category',
    'preview_image'     => [
        'path'  => '/storage/app/upload/...',
        'title' => 'image title',
        'alt'   => 'image alt',
    ],
    'description'       => 'category description',
    'images'            => [
        [
            'path'  => '/storage/app/upload/...',
            'title' => 'image title',
            'alt'   => 'image alt',
        ],[
            'path'  => '/storage/app/upload/...',
            'title' => 'image title',
            'alt'   => 'image alt',
        ],
    ],
    'children'      => [            //Array with children category data
        1 => [...],                 //Array with children category data (id=1)
        6 => [...],                 //Array with children category data (id=6)
        18 => [...],                //Array with children category data (id=18)
    ],
]
```
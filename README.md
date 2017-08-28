# Shopaholic plugin for October CMS

[![Build Status](https://travis-ci.org/lovata/oc-shopaholic-plugin.svg?branch=master)](https://travis-ci.org/lovata/oc-shopaholic-plugin) [![Code Climate](https://codeclimate.com/github/lovata/oc-shopaholic-plugin/badges/gpa.svg)](https://codeclimate.com/github/lovata/oc-shopaholic-plugin) [![Crowdin](https://d322cqt584bo4o.cloudfront.net/shopaholic-plugin-for-october/localized.svg)](https://crowdin.com/project/shopaholic-plugin-for-october)

## Description

E-Commerce plugin by [LOVATA](http://lovata.com) for October CMS.


### Plugin settings
    Backend -> Settings -> Shopaholic:
 1. **Настройка формата цен:**
    - Число знаков после запятой.
    - Разделитель дробной части.
    - Разделитель разряда тысяч.
    - Обозначение валюты.

### Component "ProductPage"
**Component properties:**
  - Отображать 404 страницу, если товар не был найден.
  - Значение поля "slug" для поиска товара. Usage: если URL страницы "/product/:slug", то необходимо указать в настройках компонента: slug = "{{ :slug }}"

**Usage:**
Component удобно использовать для отображения страницы товара. Поиск товара будет производится по значению поля "slug". Пример получения данных товара:

```html
{% set obProduct = ProductPage.get %}
...
 
<div>{{ obProduct.name }}</div>
<div>{{ obProperty.description }}</div>
```

### Component "ProductData"
**Usage:**
Component удобно использовать для получения данных товара по ID.

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

### Product data
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
    'offer_list'          => [1, 6, 18],  //Array with offer ID
]
```

### Offer data
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

### Component "Currency"

**Usage:**
Component предназначен для получение значения валюты из настроек плагина:

```html
<div>{{ Currency.get }}</div>
```

### Component "CategoryPage"
**Component properties:**
  - Отображать 404 страницу, если категория не была найдена.
  - Значение поля "slug" для поиска категории. Usage: если URL страницы "/catalog/:slug", то необходимо указать в настройках компонента: slug = "{{ :slug }}"

**Usage:**
Component удобно использовать для отображения страницы категори. Поиск категории будет производится по значению поля "slug". Пример получения данных категории:

```html
{% set obCategory = CategoryPage.get %}
...
 
<div>{{ obCategory.name }}</div>
<div>{{ obCategory.description }}</div>
```

### Component "CategoryData"
**Usage:**
Component удобно использовать для получения данных категории по ID.

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

### Category data
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

### Component "CategoryList"
**Usage:**
Component используется для отображения категорий в виде дерева. Дерево возвращается начиная с первого уровня вложенности.

```html 
{% set obCategoryList = CategoryList.get() %}
...
 
<ul>
    {% for obCategory in obCategoryList %}
        <li>{{ obCategory.name }}</li>
        {% if obCategory.children is not empty %}
            <ul>
                {% for obChildrenCategory in obCategory.children %}
                    <li>{{ obChildrenCategory.name }}</li>
                {% endfor %}
            </ul>
        {% endif %}
    {% endfor %}
</ul>
```

### Component "ProductList"
**Usage:**
Component используется для отображения постраничного списка отсортированных товаров.

**Component properties:**
  - Default sorting.
  - Pagination settings.
  
**Example 1 (Get product list)**

```html
{# 10 - category ID #}
{# 1 - page number (default = 1) #}
{# true - учитывать номер страницы из запроса (default = true) #}
{% set arProductList = ProductList.get(10, 1) %}
{% if arProductList is not empty %}
    {% for obProduct in arProductList %}
        <div>{{ obProduct.name }}</div>
        <div>{{ obProduct.description }}</div>
    {% endfor %}
{% endif %}
```

**Example 2 (Get pagination elements)**

```html
{# 10 - category ID #}
{# 1 - page number (default = 1) #}
{# true - учитывать номер страницы из запроса (default = true) #}
{% set arPaginationList = ProductList.getPagination(10, 1) %}
{% if arPaginationList is not empty %}
<ul class="pagination-wrapper">
    {% for obPagination in arPaginationList %}
        <li  class="pagination-element {{ obPagination.class }}">
            <a data-page="{{ obPagination.value }}">{{ obPagination.name }}</a>
        </li>
    {% endfor %}
</ul>
{% endif %}
```

**Example 3 (Get product count)**

```html
{# 10 - category ID #}
{% set iProductCount = ProductList.getCount(10) %}
<div>Product count: {{ iProductCount }}</div>
```

**Example 4 (Get full data)**

```html
{# 10 - category ID #}
{# 1 - page number (default = 1) #}
{# true - учитывать номер страницы из запроса (default = true) #}
{% set arProductList = ProductList.getData(10, 1) %}
{% if arProductList.list is not empty %}
    {% for obProduct in arProductList.list %}
        <div>{{ obProduct.name }}</div>
        <div>{{ obProduct.description }}</div>
    {% endfor %}
{% endif %}
...
{% if arProductList.pagination is not empty %}
<ul class="pagination-wrapper">
    {% for obPagination in arProductList.pagination %}
        <li  class="pagination-element {{ obPagination.class }}">
            <a data-page="{{ obPagination.value }}">{{ obPagination.name }}</a>
        </li>
    {% endfor %}
</ul>
{% endif %}
...
<div>Product count: {{ arProductList.count }}</div>
```

**Example 5 (Get active sorting)**

```html
{% set sActiveSorting = ProductList.getSorting() %}
<select name="sort">
    <option {% if sActiveSorting == 'no' %}selected{% endif %}>Without sorting</option>
    <option {% if sActiveSorting == 'new' %}selected{% endif %}>New</option>
    <option {% if sActiveSorting == 'price|asc' %}selected{% endif %}>Price asc</option>
    <option {% if sActiveSorting == 'price|desc' %}selected{% endif %}>Price desc</option>
</select>
```

**Example 6 (Usage plugin javascript with pagination)**
Если ваш список товаров работает без перезагрузки страниц для отображения следующей страницы, вы можите использовать
функции плагина для изменения URL страницы: /catalog/category_slug -> /catalog/category_slug?page=2
-> /catalog/category_slug?page=3. Для этого вам необходимо:
  - Добавить класс "oc-shopaholic-pagination" для обертки списка элементов пагинации
  - Объявить функцию windows.paginationShopaholicAjaxRequest(_this) для отправки ajax-запроса

```html
<div class="oc-shopaholic-pagination">
    <ul class="pagination-wrapper">
        {% for obPagination in arPaginationList %}
            <li  class="pagination-element {{ obPagination.class }}">
                <a data-page="{{ obPagination.value }}">{{ obPagination.name }}</a>
            </li>
        {% endfor %}
    </ul>
</div>
```

```js
window.paginationShopaholicAjaxRequest = function(_this) {
    $.request('ProductList::onAjaxRequest', {
        data: {/*...*/},
        update: {'product-list': '.product-list-wrapper'}
    });
}
```

**Example 7 (Usage plugin javascript with sorting)**
Если ваш список товаров работает без перезагрузки страниц для отображения страниц, вы можите использовать
функции плагина для изменения URL страницы: /catalog/category_slug -> /catalog/category_slug?sort=new
-> /catalog/category_slug?sort=price|asc. Для этого вам необходимо:
  - Добавить класс "oc-shopaholic-sorting" для элемента сортировки
  - Объявить функцию windows.sortingShopaholicAjaxRequest(_this) для отправки ajax-запроса

```html
<select name="sort" class="oc-shopaholic-sorting">
    <option {% if sActiveSorting == 'no' %}selected{% endif %}>Without sorting</option>
    <option {% if sActiveSorting == 'new' %}selected{% endif %}>New</option>
    <option {% if sActiveSorting == 'price|asc' %}selected{% endif %}>Price asc</option>
    <option {% if sActiveSorting == 'price|desc' %}selected{% endif %}>Price desc</option>
</select>
```

```js
window.sortingShopaholicAjaxRequest = function(_this) {
    $.request('ProductList::onAjaxRequest', {
        data: {/*...*/},
        update: {'product-list': '.product-list-wrapper'}
    });
}
```

**Example 8 (Usage onAjaxRequest)**
 - По-умолчанию метод onAjaxRequest возвращает строку запроса ('sort=price|asc&page=3')
 - Если отправить 'response_type' == 'full', то метод вернет результат выполнения метода компонента getData
 - Если отправить 'response_type' == 'id_list', то метод вернет отсортированный список ID товаров

## License

© 2017, [LOVATA Group, LLC](http://lovata.com) under [GNU GPL v3](https://opensource.org/licenses/GPL-3.0).

Developed by [Andrey Kharanenka](https://github.com/kharanenka).

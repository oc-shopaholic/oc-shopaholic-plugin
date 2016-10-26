# Trait ActiveField
 
 You can use trait in your models with "active" filed (bool)
 
#Installation
Require this package in your `composer.json` and update composer.
 
```php

"kharanenka/laravel-scope-active": "1.0.*"

```

# Usage

```php

    
    class MyModel extend Model {
    
        use Kharanenka\Scope\ActiveField;
    
        ...
    
    }
    
    $obElement = MyModel::active()->first();
    $obElement = MyModel::notActive()->first();
    
```
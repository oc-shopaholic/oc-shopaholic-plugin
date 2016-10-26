# Class Result

Universal result store:
 - flag (bool)
 - data (bool | number | string | array | object)
 
#Installation
Require this package in your `composer.json` and update composer.
 
```php

"kharanenka/php-result-store": "1.0.*"

```

# Usage

You can use class "Result" in any places your application. Class "Result" is singleton.

```php

    use Kharanenka\Helper\Result;
    
    //Method Result::flag() return false
    //Method Result::data() return 'Error message text'
    
    //Check errors
    if($bHasError) {
        $sMessage = 'Error message text';
        return Result::setFalse($sMessage)->get();
    }
    
    ...
    //Call check function
    check();
    if(!Result::flag()) {
        return Result::get();
    }
    
    ...
    //Get result data
    $arData = [
        'id' => 6,
        'name' => 'Andrey',
    ];
    
    return Result::setTrue($arData)->get();
    
    ...
    function check() {
    
        ...
    
        if($bHasError) {
            $sMessage = 'Error message text';
            Result::setFalse($sMessage);
        }
    }
    
```
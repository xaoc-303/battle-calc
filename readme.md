# Battle Calc

## Composer
In the `require` key of `composer.json` file add the following

```json
"xaoc303/battle-calc": "~2.0"
```
or run command
```bash
$ composer require xaoc303/battle-calc:~2.0
```

Run the Composer update command

```bash
$ composer update
```

In your `config/app.php` add `'Xaoc303\BattleCalc\BattleCalcServiceProvider'` to the end of the `providers` array

```php
'providers' => array(

    'Illuminate\Foundation\Providers\ArtisanServiceProvider',
    'Illuminate\Auth\AuthServiceProvider',
    ...
    'Xaoc303\BattleCalc\BattleCalcServiceProvider',

),
```
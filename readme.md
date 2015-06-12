# Battle Calc

[![Build Status](https://travis-ci.org/xaoc-303/battle-calc.svg?branch=master)](https://travis-ci.org/xaoc-303/battle-calc)
[![Latest Stable Version](https://poser.pugx.org/xaoc303/battle-calc/v/stable)](https://packagist.org/packages/xaoc303/battle-calc)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/xaoc-303/battle-calc/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/xaoc-303/battle-calc/?branch=master)

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

## Setting

In your `config/app.php` add `'Xaoc303\BattleCalc\BattleCalcServiceProvider'` to the end of the `providers` array

```php
'providers' => array(

    'Illuminate\Foundation\Providers\ArtisanServiceProvider',
    'Illuminate\Auth\AuthServiceProvider',
    ...
    'Xaoc303\BattleCalc\BattleCalcServiceProvider',

),
```
Multiple fields adding and processing
=====================================
Possibility to insert/update multiple relation enteties

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist mitalcoi/yii2-multi "*"
```

or add

```
"mitalcoi/yii2-multi": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?= \mitalcoi\multi\MutiInput::widget(); ?>```
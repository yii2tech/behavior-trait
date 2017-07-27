<p align="center">
    <a href="https://github.com/yii2tech" target="_blank">
        <img src="https://avatars2.githubusercontent.com/u/12951949" height="100px">
    </a>
    <h1 align="center">Behavior Trait Extension for Yii 2</h1>
    <br>
</p>

This extension provides the ability of handling events via inline declared methods, which can be
added via traits.

For license information check the [LICENSE](LICENSE.md)-file.

[![Latest Stable Version](https://poser.pugx.org/yii2tech/behavior-trait/v/stable.png)](https://packagist.org/packages/yii2tech/behavior-trait)
[![Total Downloads](https://poser.pugx.org/yii2tech/behavior-trait/downloads.png)](https://packagist.org/packages/yii2tech/behavior-trait)
[![Build Status](https://travis-ci.org/yii2tech/behavior-trait.svg?branch=master)](https://travis-ci.org/yii2tech/behavior-trait)


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist yii2tech/behavior-trait
```

or add

```json
"yii2tech/behavior-trait": "*"
```

to the require section of your composer.json.


Usage
-----

This extension introduces special trait [[\yii2tech\behaviortrait\BehaviorTrait]], which if used provides
the ability of handling events via inline declared methods, which can be added via other traits.
This trait can be added to any descendant of [[\yii\base\Component]].

Each event handler method should be named by pattern: '{eventName}Handler{UniqueSuffix}', where 'eventName' is a
name of the event the method should handle, 'UniqueSuffix' any suffix, which separate particular event handler
method from the others.
For example: if the class has an event 'beforeInsert' it can introduce method named `beforeInsertHandlerEncryptPassword`,
which will be automatically triggered when event 'beforeInsert' is triggered:

```php
use yii\db\ActiveRecord;
use yii2tech\behaviortrait\BehaviorTrait;

class User extends ActiveRecord
{
    use BehaviorTrait; // add `BehaviorTrait` allowing to use inline event handlers
    use EncryptPasswordTrait; // add trait, which introduce inline event handler

    // ...
}

trait EncryptPasswordTrait
{
    public function beforeInsertHandlerEncryptPassword(Event $event)
    {
        if (!empty($this->newPassword)) {
            $this->password = sha1($this->newPassword);
        }
    }
}
```

> Attention: watch for the naming collisions, ensure any inline handler declared either in class or via trait has
  a unique name (with unique suffix)!

> Note: using traits instead behaviors improves performance but is less flexible. Keep in mind that such approach
  has been rejected at Yii2 core at [yiisoft/yii2#1041](https://github.com/yiisoft/yii2/pull/1041).

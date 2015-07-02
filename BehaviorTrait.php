<?php
/**
 * @link https://github.com/yii2tech
 * @copyright Copyright (c) 2015 Yii2tech
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace yii2tech\behaviortrait;

use yii\base\Event;

/**
 * BehaviorTrait adds the ability of handling events via inline declared methods,
 * which can be added via other traits.
 *
 * This trait can be added to any descendant of [[\yii\base\Component]].
 *
 * Each event handler method should be named by pattern: '{eventName}Handler{UniqueSuffix}',
 * where 'eventName' name of the event the method should handle, 'UniqueSuffix' any suffix,
 * which separate particular event handler method from the others.
 * For example: if the class has an event 'beforeSave' it can introduce method named
 * 'beforeSaveHandlerEncryptPassword', which will be automatically triggered when event 'beforeSave'
 * is triggered.
 *
 * Note: watch for the naming collisions, ensure any inline handler declared either in class
 * or via trait has a unique name (with unique suffix)!
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 1.0
 */
trait BehaviorTrait
{
    /**
     * Triggers an event.
     * This method represents the happening of an event. It invokes
     * all attached handlers for the event including class-level handlers.
     * @param string $name the event name
     * @param Event $event the event parameter. If not set, a default [[Event]] object will be created.
     */
    public function trigger($name, Event $event = null)
    {
        if ($event === null) {
            $event = new Event();
        }
        $methods = get_class_methods($this);
        $eventHandlerMethodPrefix = $name . 'Handler';
        $eventHandlers = array_filter($methods, function ($method) use ($eventHandlerMethodPrefix) {
            return (stripos($method, $eventHandlerMethodPrefix) === 0);
        });
        if (!empty($eventHandlers)) {
            if ($event->sender === null) {
                $event->sender = $this;
            }
            $event->handled = false;
            $event->name = $name;
            foreach ($eventHandlers as $eventHandler) {
                $this->$eventHandler($event);
                // stop further handling if the event is handled
                if ($event instanceof Event && $event->handled) {
                    return;
                }
            }
        }
        parent::trigger($name, $event);
    }
}
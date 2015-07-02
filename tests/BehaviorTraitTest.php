<?php

namespace yii2tech\tests\unit\behaviortrait;

use yii\base\Component;
use yii2tech\behaviortrait\BehaviorTrait;

class BehaviorTraitTest extends TestCase
{
    public function testEvent()
    {
        $component = new TestComponent();

        $expectedOutput = implode("\n", [
            'yii2tech\tests\unit\behaviortrait\TestComponent::runEventTest',
            'yii2tech\tests\unit\behaviortrait\TestTrait::afterEventTestHandlerByTestTrait',
            'custom event handler',
        ]) . "\n";
        $this->expectOutputString($expectedOutput);

        $component->runEventTest();
    }
}

class TestComponent extends Component
{
    use BehaviorTrait, TestTrait;

    public function init()
    {
        $this->on('afterEventTest', function() {
            echo "custom event handler\n";
        });
    }

    public function runEventTest()
    {
        echo __METHOD__ . "\n";
        $this->trigger('afterEventTest');
    }
}

trait TestTrait
{
    public function afterEventTestHandlerByTestTrait()
    {
        echo __METHOD__ . "\n";
    }
}
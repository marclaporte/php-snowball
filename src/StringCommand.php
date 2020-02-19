<?php

namespace Wamania\Snowball;

use voku\helper\UTF8;
use Wamania\Snowball\Type\Non;

class StringCommand
{
    /** @var Context */
    private $context;

    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    public function s():bool
    {
        //$substring = $this->context->
    }

    public function test(bool $test)
    {
        return $test;
    }

    public function try(bool $test)
    {

    }

    public function goto($variable): bool
    {
        if ($variable instanceof Non) {
            return $this->gotoNon($variable);
        }

        if (is_string($variable)) {
            $variable = [$variable];
        }

        $found = false;

        for ($i=$this->context->getCursor(); $i<$this->context->getLimit(); $i++) {
            $substring = UTF8::substr($this->context->getString(), $i);
            foreach ($variable as $item) {
                if (UTF8::strpos($substring, $item) === 0) {
                    $this->context->setCursor($i);
                    $found = true;
                }
            }
        }

        return $found;
    }

    private function gotoNon(Non $variable): bool
    {
        $variables = $variable->get();

        if (is_string($variables)) {
            $variables = [$variables];
        }

        for ($i=$this->context->getCursor(); $i<$this->context->getLimit(); $i++) {
            $substring = UTF8::substr($this->context->getString(), $i);
            foreach ($variables as $item) {
                if (UTF8::strpos($substring, $item) === 0) {
                    $found = true;
                }
            }

            if (!$found) {
                $this->context->setCursor($i);
            }
        }

        return !$found;
    }

    public function gopast($variable): bool
    {
        if ($variable instanceof Non) {
            return $this->gopastNon($variable);
        }

        if (is_string($variable)) {
            $variable = [$variable];
        }

        $found = false;

        for ($i=$this->context->getCursor(); $i<$this->context->getLimit(); $i++) {
            $substring = UTF8::substr($this->context->getString(), $i);
            foreach ($variable as $item) {
                if (UTF8::strpos($substring, $item) === 0) {
                    $this->context->setCursor($i + UTF8::strlen($item));
                    $found = true;
                }
            }
        }

        return $found;
    }

    private function gopastNon(Non $variable): bool
    {
        $variables = $variable->get();

        if (is_string($variables)) {
            $variables = [$variables];
        }

        $length = 0;
        $found = false;

        for ($i=$this->context->getCursor(); $i<$this->context->getLimit(); $i++) {
            $substring = UTF8::substr($this->context->getString(), $i);
            foreach ($variables as $item) {
                if (UTF8::strpos($substring, $item) === 0) {
                    $found = true;
                    $length = UTF8::strlen($item);
                }
            }

            if (!$found) {
                $this->context->setCursor($i+$length);
            }
        }

        return !$found;
    }

    public function hop(int $number): bool
    {
        if ($number < 0) {
            return false;
        }

        if (($this->context->getCursor() + $number) > $this->context->getLimit()) {
            return false;
        }

        $this->context->setCursor($this->context->getCursor() + $number);

        return true;
    }

    /*public function try()
    {
        return true;
    }*/

    public function setmark(int &$x): bool
    {
        $x = $this->context->getCursor();

        return true;
    }
}
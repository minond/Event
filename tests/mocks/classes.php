<?php

namespace Efficio\Tests\Mocks\Event;

trait TraitTest
{
}

interface InterfaceTest
{
}

class ClassTest
{
}

class ClassTraitTest
{
    use TraitTest;
}

class ClassClassTraitTest extends ClassTraitTest
{
}

class ClassClassTest extends ClassTest
{
}

class ClassClassClassTest extends ClassClassTest
{
}

class ClassInterfaceTest implements InterfaceTest
{
}

class ClassClassInterfaceTest extends ClassInterfaceTest
{
}

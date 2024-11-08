<?php

namespace iutnc\goodfood\action;

class DefaultAction extends Action
{

    public function execute(): string
    {
        return "<p>Bienvenue sur GoodFood !</p>";
    }
}
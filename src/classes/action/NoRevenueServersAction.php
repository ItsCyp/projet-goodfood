<?php

namespace iutnc\goodfood\action;

class NoRevenueServersAction extends Action
{
    public function execute(): string
    {
        return "Liste des serveurs n'ayant pas généré de revenus.";
    }
}
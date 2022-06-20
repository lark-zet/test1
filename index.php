<?php
    require 'Taxicab.php';

    $taxicab = new Taxicab();

    $taxicab->calculate();
    $taxicab->showResults();
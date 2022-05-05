<?php

namespace App\OpenApi\Parameters\Auth;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Factories\ParametersFactory;

class RegisterParameters extends ParametersFactory
{
    /**
     * @return Parameter[]
     */
    public function build(): array
    {
        return [

            Parameter::query()
                ->name('name')
                ->required()
                ->schema(Schema::string()),
            Parameter::query()
                ->name('email')
                ->required()
                ->schema(Schema::string()),
            Parameter::query()
                ->name('password')
                ->required()
                ->schema(Schema::string()),
        ];
    }
}

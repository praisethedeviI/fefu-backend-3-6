<?php

namespace App\OpenApi\Parameters;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Factories\ParametersFactory;

class ToggleFavouriteProductParameters extends ParametersFactory
{
    /**
     * @return Parameter[]
     */
    public function build(): array
    {
        return [

            Parameter::query()
                ->name('id')
                ->required(false)
                ->schema(Schema::string()),
            Parameter::query()
                ->name('slug')
                ->required(false)
                ->schema(Schema::string()),

        ];
    }
}

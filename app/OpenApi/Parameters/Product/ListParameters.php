<?php

namespace App\OpenApi\Parameters\Product;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Factories\ParametersFactory;

class ListParameters extends ParametersFactory
{
    /**
     * @return Parameter[]
     */
    public function build(): array
    {
        return [
            Parameter::query()
                ->name('category_slug')
                ->required(false)
                ->schema(Schema::string()),
            Parameter::query()
                ->name('search_query')
                ->required(false)
                ->schema(Schema::string()),
            Parameter::query()
                ->name('sort_mode')
                ->required(false)
                ->schema(Schema::integer())
                ->description('0 - price asc, 1 - price desc'),
            Parameter::query()
                ->name('filters')
                ->required(false)
                ->schema(Schema::array()->items(
                   Schema::array()->items(
                       Schema::string()
                   )
                )),
        ];
    }
}

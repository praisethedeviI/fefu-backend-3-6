<?php

namespace App\OpenApi\Responses\Product;

use App\OpenApi\Schemas\PaginatorLinksSchema;
use App\OpenApi\Schemas\PaginatorMetaSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;

class ListProductResponse extends ResponseFactory
{
    public function build(): Response
    {
        return Response::ok()->description('Successful response')->content(
            MediaType::json()->schema(Schema::object()->properties(
                Schema::array('data')->items(Schema::object()->properties(
                    Schema::string('name'),
                    Schema::number('price')->format('double'),
                    Schema::string('slug')
                )),
                PaginatorLinksSchema::ref('links'),
                PaginatorMetaSchema::ref('meta'),
            ))
        );
    }
}

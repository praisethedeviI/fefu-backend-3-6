<?php

namespace App\OpenApi\Responses\Product;

use App\OpenApi\Schemas\FilterSchema;
use App\OpenApi\Schemas\ListProductSchema;
use App\OpenApi\Schemas\PaginatorLinksSchema;
use App\OpenApi\Schemas\PaginatorMetaSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;

class   ListProductResponse extends ResponseFactory
{
    public function build(): Response
    {
        return Response::ok()->description('Successful response')->content(
            MediaType::json()->schema(Schema::object()->properties(
                Schema::array('data')->items(ListProductSchema::ref()),
                PaginatorLinksSchema::ref('links'),
                PaginatorMetaSchema::ref('meta'),
                Schema::array('filters')->items(
                    FilterSchema::ref()
                )
            ))
        );
    }
}

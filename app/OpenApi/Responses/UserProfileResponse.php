<?php

namespace App\OpenApi\Responses;

use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;

class UserProfileResponse extends ResponseFactory
{
    public function build(): Response
    {
        return Response::ok()->description('Successful response')->content(
            MediaType::json()->schema(Schema::object()->properties(
                Schema::string('name'),
                Schema::string('email'),
                Schema::string('github_logged_in_at')->format(Schema::FORMAT_DATE_TIME),
                Schema::string('github_registered_at')->format(Schema::FORMAT_DATE_TIME),
                Schema::string('discord_logged_in_at')->format(Schema::FORMAT_DATE_TIME),
                Schema::string('discord_registered_at')->format(Schema::FORMAT_DATE_TIME),
                Schema::string('app_logged_in_at')->format(Schema::FORMAT_DATE_TIME),
                Schema::string('app_registered_at')->format(Schema::FORMAT_DATE_TIME),
            ))
        );
    }
}

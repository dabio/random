# Task

Please create a simple REST server in PHP that can handle these requests:

Method | URL         | Parameters                              | Response
-------|-------------|-----------------------------------------|---------
PUT    | /items/save | name, description, price, id (optional) | id, error
GET    | /items/list |                                         | list all items
POST   | /cart/add   | customer_id, item_id                    | id, error

## Opinion

This URL schema is not that RESTful.

I would rather suggest using the following URL schema.

Method | URL         | Parameters                              | Response
-------|-------------|-----------------------------------------|---------
POST   | /items      | name, description, price                | item, error
PUT    | /items/:id  | name, description, price                | item, error
GET    | /items      |                                         | list all items
POST   | /cart       | item_id                                 | cart, error

Most of them are just minor changes in the URL naming. The cart should not get the customer_id as a parameter. The customer_id is a rather sensitive attribute, which should better be in a session (represented by a hash).

As no response format was specified, I assume JSON might be an option.

## Getting started

Make sure your lokal `mysql` service is up, running and you have a database created for this project.

```bash
$ git clone https://github.com/dabio/random.git
$ cd random/msg
```

Install all dependencies with `composer install` and answer the questions concerning the database connection.

Create the database schema through `php app/console doctrine:schema:create`. A basic server is shipped with PHP. Start it with `php app/console server:run`.

### Testing

All tests are in the `src/dabio/RestBundle/Tests` directory. Feel free to look around. `PHPUnit` is installed as a dependency. Start running all tests with `./vendor/phpunit/phpunit/phpunit -c app/phpunit.xml.dist`.
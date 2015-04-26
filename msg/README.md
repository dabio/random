# Task

Please create a simple REST server in PHP that can handle these requests:

Method | URL         | Parameters                              | Response
-------|-------------|-----------------------------------------|---------
PUT    | /items/save | name, description, price, id (optional) | id, error
GET    | /items/list |                                         | list all items
POST   | /cart/add   | customer_id, item_id                    | id, error

## Opinion

This URL schema is not RESTful.

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
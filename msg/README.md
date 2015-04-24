# Task

Please create a simple REST server in PHP that can handle these requests:

Method | URL            | Parameters                                 | Response
-------------------------------------------------------------------------------------
PUT    | /items/save    | name, description, price, id (optional)    | id, error
GET    | /items/list    |                                            | list all items
POST   | /cart/add      | customer_id, item_id                       | id, error

## Opinion

This URL schema is not RESTful.
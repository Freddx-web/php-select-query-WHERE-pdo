# php-select-query-WHERE-pdo
How to create a WHERE clause for PDO dynamically

This is quite a common task when we need to create a search query based on the arbitrary number of parameters.

The main problem here is that we cannot create a query beforehand (well, actually we can and it will be shown later) and therefore we don't know which parameters to bind.

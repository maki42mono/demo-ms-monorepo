# What is it?
This is a monorepo containing microservices built with Symfony 5, derived from a previous job. All microservices, except for **Auth**, authorize users with a JWT token through `security.access_control`.

## Auth
Authenticates users using JWT tokens.

## Order
Processes data related to user orders and displays it in a React.js application.

## Order_chat
Processes chat data between players and compiles a list of chats.

## User
Displays user information.

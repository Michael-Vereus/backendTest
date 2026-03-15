# SSMS API

A simple REST-style backend API built with **pure PHP** as a learning project.
This project was created to practice backend architecture concepts such as layered design, repository patterns, and structured API responses without relying on a framework.

The goal of this project is to understand how backend systems work **under the hood** before moving to modern frameworks.

---

# Project Purpose

This project was built to practice and demonstrate understanding of:

* Object Oriented Programming (OOP)
* Basic REST API design
* MVC-inspired architecture
* Repository pattern
* Service layer pattern
* SQL interaction
* Basic error handling
* Consistent API responses

It serves as a foundational backend project before transitioning to frameworks like Laravel.

---

# Architecture Overview

The project follows a simple layered architecture:

Controller
Handles incoming HTTP requests and determines which service to call.

Service
Contains business logic and coordinates operations between controller and repository.

Repository
Handles all database queries and data access.

Model
Represents data structures used throughout the application.

This separation helps keep the code organized and easier to maintain.

---

# Project Structure

```
ssmsApi/
│
├── controller/
│   └── ...
│
├── service/
│   └── ...
│
├── repository/
│   └── ...
│
├── model/
│   └── ...
│
├── config/
│    database configuration
│
├── notes.md
│
└── ssmsApi.php
```

---

# Features

Current features implemented:

* Basic API request handling
* CRUD-style operations
* Structured JSON responses
* Error handling using try/catch
* Repository pattern for database queries
* Separation of business logic from HTTP logic

---

# Example Response Format

The API returns structured JSON responses to keep output consistent.

Example:

```json
{
  "success": true,
  "data": {},
  "message": "Operation successful"
}
```

If an error occurs:

```json
{
  "success": false,
  "data": null,
  "message": "An error occurred"
}
```

---

# How To Run

1. Clone the repository

```
git clone https://github.com/Michael-Vereus/ssmsApi.git
```

2. Move into the project directory

```
cd ssmsApi
```

3. Configure database connection in the config file.

4. Run the project using a local PHP server, Apache , or XAMPP.

Example with PHP built-in server:

```
php -S localhost:8000
```

5. Use Postman or any HTTP client to send requests to the API.

---

# Tools Used

* PHP
* SQLite / MySQL (depending on configuration)
* Postman (for API testing)
* Git / GitHub
* Visual Studio Code

---

# Learning Notes

This project focuses on understanding backend fundamentals without frameworks.
The architecture and patterns used here helped build a foundation for learning more advanced backend tools.

Future projects will expand on this knowledge using modern frameworks and containerized environments.

---

# Future Improvements

Possible improvements for this project:

* Add request validation
* Implement proper routing instead of switch statements
* Add authentication
* Add unit testing
* Improve error logging
* Dockerize the project environment

---

# Author

Michael Vereus

Self-taught developer learning backend engineering through hands-on projects :>

GitHub:
https://github.com/Michael-Vereus

---

# License

This project is open for educational purposes.

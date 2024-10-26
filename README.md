Here’s a comprehensive README.md file that walks through the installation and implementation process from setting up the Laravel backend with JWT and WebSocket to integrating the Flutter app with JWT authentication and real-time updates.


# Car Management System with Microservice Architecture, JWT Authentication, and Real-Time Updates

This project is a full-stack application built with Laravel and Flutter that implements JWT-based authentication, WebSocket for real-time updates, and a microservice-inspired architecture. Users can register, login, and perform CRUD operations on cars, with real-time notifications available for certain actions.

## Table of Contents
1. [Features](#features)
2. [Technologies Used](#technologies-used)
3. [Installation](#installation)
4. [Backend (Laravel) Setup](#backend-laravel-setup)
5. [Frontend (Flutter) Setup](#frontend-flutter-setup)
6. [Running the Application](#running-the-application)

---

## Features
- **User Authentication**: Secure user login and registration with JWT.
- **Car Management**: CRUD operations for car entities.
- **Real-Time Notifications**: Receive live updates when a car entity is added, modified, or deleted.
- **Microservice-Inspired Architecture**: Decouples user authentication and car management functionalities.

## Technologies Used
- **Backend**: Laravel (PHP)
- **Frontend**: Flutter (Dart)
- **Authentication**: JSON Web Tokens (JWT)
- **Real-Time Notifications**: WebSocket using Pusher and Laravel Echo
- **Database**: MySQL (or any preferred database)

---

## Installation

### Prerequisites
- [Composer](https://getcomposer.org/) (for PHP dependencies)
- [Node.js](https://nodejs.org/) and npm (for Laravel Echo)
- [Flutter SDK](https://flutter.dev/docs/get-started/install) (for the mobile app)
- [Pusher Account](https://pusher.com/) (for WebSocket functionality)

---

## Backend (Laravel) Setup

### 1. Clone the Repository
```bash
git clone https://github.com/your-username/car-management-system.git
cd car-management-system/backend
```
2. Install Dependencies
```bash 
composer install
npm install
```
3. Environment Configuration
Copy .env.example to .env and update your database, Pusher, and JWT settings:
```bash
cp .env.example .env
```

Update .env with your configuration:
```bash
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

PUSHER_APP_ID=your_pusher_app_id
PUSHER_APP_KEY=your_pusher_app_key
PUSHER_APP_SECRET=your_pusher_app_secret
PUSHER_APP_CLUSTER=your_pusher_app_cluster

JWT_SECRET=your_jwt_secret
```
4. Generate Keys and Run Migrations
```bash
php artisan key:generate
php artisan jwt:secret
php artisan migrate
```
5. Start Laravel Server
```bash
php artisan serve
```

6. Setting Up WebSocket with Pusher
In config/broadcasting.php, set the default broadcast driver to Pusher:

```php
'default' => env('BROADCAST_DRIVER', 'pusher'),
```
In resources/js/bootstrap.js, import Laravel Echo and initialize Pusher:

```javascript
import Echo from 'laravel-echo';
window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: true
});
```

Then, compile assets:

```bash
npm run dev
```


Frontend (Flutter) Setup
1. Navigate to Flutter Project Directory

```bash
cd ../frontend
```

2. Install Flutter Dependencies
Run the following command to install required packages:

```bash
flutter pub get
```
3. Secure Storage and HTTP Setup
Install flutter_secure_storage for storing JWT tokens and http for API requests in your pubspec.yaml:

```yaml
dependencies:
  flutter_secure_storage: ^5.0.2
  http: ^0.13.3
  pusher_client: ^1.2.0
```
4. API Communication with JWT
Login and Fetch JWT Token
Add a login function to store the JWT token securely:

```dart
import 'package:http/http.dart' as http;
import 'package:flutter_secure_storage/flutter_secure_storage.dart';

final storage = FlutterSecureStorage();

Future<void> login(String email, String password) async {
  final response = await http.post(
    Uri.parse('https://your-api.com/api/v1/auth/login'),
    body: {'email': email, 'password': password},
  );

  if (response.statusCode == 200) {
    final token = json.decode(response.body)['token'];
    await storage.write(key: 'jwt_token', value: token);
  } else {
    throw Exception('Failed to login');
  }
}

Future<Map<String, String>> getHeaders() async {
  final token = await storage.read(key: 'jwt_token');
  return {
    'Authorization': 'Bearer $token',
  };
}
```

Fetching Cars
Use the token to make authenticated requests to the backend:

```dart
Future<List<Car>> fetchCars() async {
  final headers = await getHeaders();
  final response = await http.get(
    Uri.parse('https://your-api.com/api/cars'),
    headers: headers,
  );

  if (response.statusCode == 200) {
    // Parse response
  } else {
    throw Exception('Failed to load cars');
  }
}
```

5. Real-Time Notifications with Pusher
Initialize Pusher

```dart
import 'package:pusher_client/pusher_client.dart';

final pusher = PusherClient(
  "YOUR_PUSHER_APP_KEY",
  PusherOptions(
    cluster: "YOUR_PUSHER_APP_CLUSTER",
    auth: PusherAuth(
      'https://your-api.com/api/v1/broadcasting/auth',
      headers: {'Authorization': 'Bearer YOUR_JWT_TOKEN'},
    ),
  ),
);

void initPusher() {
  pusher.connect();
  Channel channel = pusher.subscribe('cars');

  channel.bind('car.updated', (event) {
    print("Car updated: ${event.data}");
  });
}
```

#Running the Application
Backend
Run the Laravel server:
```bash 
php artisan serve
```

Run WebSocket server if needed:
```bash 
php artisan websockets:serve
```

Frontend
Run the Flutter app:
```bash
flutter run
```




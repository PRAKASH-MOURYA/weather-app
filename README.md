# 🌦️ Weather App

A simple weather application built using **PHP** and **CSS** that allows users to fetch real-time weather data for any city using a weather API.

## 📌 Features

- ✅ Search current weather by city name
- ✅ Displays temperature, humidity, wind speed, and weather conditions
- ✅ Clean and responsive UI with pure CSS
- ✅ Fetches live weather data from OpenWeatherMap API (or similar)

## 🧰 Tech Stack

- **Frontend**: HTML5, CSS3
- **Backend**: PHP
- **API**: [OpenWeatherMap API](https://openweathermap.org/api)

## 🚀 Getting Started

### Prerequisites

- PHP installed (version 7.x or above recommended)
- Internet connection for API access
- A free API key from [OpenWeatherMap](https://openweathermap.org/)

### Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/PRAKASH-MOURYA/weather-app.git
   cd weather-app
Open the folder in your local server environment (e.g., XAMPP, WAMP, MAMP).

Add your API key to the PHP file:

php
Copy
Edit
$apiKey = "541fd2708b7f3ea51100552776d46447";
Open the app in your browser:

arduino
Copy
Edit
http://localhost/weather-app/index.php
💡 How It Works
User enters a city name in the input field.

The PHP script sends a request to the weather API.

The API returns the current weather details.

The data is parsed and displayed in a styled layout using CSS.

📁 File Structure
bash
Copy
Edit
weather-app/
├── index.php         # Main frontend and backend logic
├── style.css         # Custom styles
├── assets/           # Optional: images or icons
└── README.md         # Project documentation

🖼️ Screenshots


![Screenshot 2025-06-27 122629](https://github.com/user-attachments/assets/caeca0c2-09f3-40d6-9eb0-428373925f16)

![Screenshot 2025-06-27 122733](https://github.com/user-attachments/assets/be57764e-ad46-4765-b3ac-554b63034373)

![Screenshot 2025-06-27 122805](https://github.com/user-attachments/assets/e4ff0027-ec95-4c6f-a80f-a878d9c73f18)

📌 Notes

Ensure error handling is enabled for invalid city names or API errors.

You may enhance the UI using frameworks like Bootstrap or Tailwind, but this version uses plain CSS.

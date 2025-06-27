<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}
include 'db.php';
$user_id = $_SESSION['user_id'];
// Get user info
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$city = $user['favorite_city'];
$apiKey = '541fd2708b7f3ea51100552776d46447'; // Your OpenWeatherMap API key

// Handle favorite city update
if (isset($_POST['update_fav_city'])) {
    $city = $_POST['city'];
    $sql = "UPDATE users SET favorite_city = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $city, $user_id);
    $stmt->execute();
}

// Handle city search
$search_city = '';
$search_weather = null;
$search_coords = null;
if (isset($_POST['search_city'])) {
    $search_city = trim($_POST['search_city']);
    if ($search_city) {
        $url = "https://api.openweathermap.org/data/2.5/weather?q=".urlencode($search_city)."&appid=$apiKey&units=metric";
        $response = file_get_contents($url);
        if ($response) {
            $search_weather = json_decode($response, true);
            if (isset($search_weather['coord'])) {
                $search_coords = $search_weather['coord'];
            }
        }
    }
}

// Fetch weather for favorite city
$fav_weather = null;
$fav_coords = null;
if ($city) {
    $url = "https://api.openweathermap.org/data/2.5/weather?q=".urlencode($city)."&appid=$apiKey&units=metric";
    $response = file_get_contents($url);
    if ($response) {
        $fav_weather = json_decode($response, true);
        if (isset($fav_weather['coord'])) {
            $fav_coords = $fav_weather['coord'];
        }
    }
}

// Fetch AQI for given coordinates
function get_aqi($lat, $lon, $apiKey) {
    $aqi_url = "https://api.openweathermap.org/data/2.5/air_pollution?lat={$lat}&lon={$lon}&appid={$apiKey}";
    $aqi_response = file_get_contents($aqi_url);
    if ($aqi_response) {
        $aqi_data = json_decode($aqi_response, true);
        if (isset($aqi_data['list'][0]['main']['aqi'])) {
            return $aqi_data['list'][0]['main']['aqi'];
        }
    }
    return null;
}

// For favorite city
$fav_aqi = null;
if ($fav_coords) {
    $fav_aqi = get_aqi($fav_coords['lat'], $fav_coords['lon'], $apiKey);
}
// For searched city
$search_aqi = null;
if ($search_coords) {
    $search_aqi = get_aqi($search_coords['lat'], $search_coords['lon'], $apiKey);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Weather - Weather App</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <a href="logout.php" class="logout-link">Logout</a>
        <h2>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h2>
        <div class="main-content">
            <!-- Favorite City Section (Left) -->
            <div class="weather-section">
                <h3>Your Favorite City: <?php echo htmlspecialchars($city); ?></h3>
                <form method="post" style="margin-bottom: 10px;">
                    <label>Change Favorite City:</label>
                    <input type="text" name="city" required>
                    <button type="submit" name="update_fav_city">Update</button>
                </form>
                <?php if ($fav_weather && $fav_weather['cod'] == 200): ?>
                    <div class="weather-info">
                        <h3>Weather in <?php echo htmlspecialchars($city); ?>:</h3>
                        <p>Temperature: <?php echo $fav_weather['main']['temp']; ?> °C</p>
                        <p>Feels like: <?php echo $fav_weather['main']['feels_like']; ?> °C</p>
                        <p>Humidity: <?php echo $fav_weather['main']['humidity']; ?>%</p>
                        <p>Weather: <?php echo $fav_weather['weather'][0]['description']; ?></p>
                        <p>AQI: <?php echo $fav_aqi ? $fav_aqi : 'N/A'; ?></p>
                        <small style="display:block;margin-bottom:8px;">(1: Good, 2: Fair, 3: Moderate, 4: Poor, 5: Very Poor)</small>
                        <?php if ($fav_coords): ?>
                            <iframe width="100%" height="200" style="border:0; margin-top:10px; border-radius:8px;" loading="lazy"
                                src="https://www.openstreetmap.org/export/embed.html?bbox=<?php echo $fav_coords['lon']-0.05; ?>%2C<?php echo $fav_coords['lat']-0.05; ?>%2C<?php echo $fav_coords['lon']+0.05; ?>%2C<?php echo $fav_coords['lat']+0.05; ?>&layer=mapnik&marker=<?php echo $fav_coords['lat']; ?>%2C<?php echo $fav_coords['lon']; ?>">
                            </iframe>
                        <?php endif; ?>
                    </div>
                <?php elseif ($city): ?>
                    <div class="weather-info">
                        <p>Could not fetch weather for <?php echo htmlspecialchars($city); ?>.</p>
                    </div>
                <?php endif; ?>
            </div>
            <!-- Search City Section (Right) -->
            <div class="weather-section">
                <h3>Search Weather for Any City</h3>
                <form method="post" style="margin-bottom: 10px;">
                    <input type="text" name="search_city" placeholder="Enter city name" value="<?php echo htmlspecialchars($search_city); ?>" required>
                    <button type="submit">Search</button>
                </form>
                <?php if ($search_city): ?>
                    <?php if ($search_weather && $search_weather['cod'] == 200): ?>
                        <div class="weather-info">
                            <h3>Weather in <?php echo htmlspecialchars($search_city); ?>:</h3>
                            <p>Temperature: <?php echo $search_weather['main']['temp']; ?> °C</p>
                            <p>Feels like: <?php echo $search_weather['main']['feels_like']; ?> °C</p>
                            <p>Humidity: <?php echo $search_weather['main']['humidity']; ?>%</p>
                            <p>Weather: <?php echo $search_weather['weather'][0]['description']; ?></p>
                            <p>AQI: <?php echo $search_aqi ? $search_aqi : 'N/A'; ?></p>
                            <small style="display:block;margin-bottom:8px;">(1: Good, 2: Fair, 3: Moderate, 4: Poor, 5: Very Poor)</small>
                            <?php if ($search_coords): ?>
                                <iframe width="100%" height="200" style="border:0; margin-top:10px; border-radius:8px;" loading="lazy"
                                    src="https://www.openstreetmap.org/export/embed.html?bbox=<?php echo $search_coords['lon']-0.05; ?>%2C<?php echo $search_coords['lat']-0.05; ?>%2C<?php echo $search_coords['lon']+0.05; ?>%2C<?php echo $search_coords['lat']+0.05; ?>&layer=mapnik&marker=<?php echo $search_coords['lat']; ?>%2C<?php echo $search_coords['lon']; ?>">
                                </iframe>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="weather-info">
                            <p>Could not fetch weather for <?php echo htmlspecialchars($search_city); ?>.</p>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html> 
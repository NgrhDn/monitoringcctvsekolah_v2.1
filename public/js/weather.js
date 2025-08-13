    document.addEventListener('DOMContentLoaded', function () {
        const apiKey = '66db882578a46f03c27c30a952240556';
        const lat = -7.797068;
        const lon = 110.370529;

        const iconMap = {
            '01d': 'mdi-weather-sunny',
            '01n': 'mdi-weather-night',
            '02d': 'mdi-weather-partlycloudy',
            '02n': 'mdi-weather-night',
            '03d': 'mdi-weather-cloudy',
            '03n': 'mdi-weather-cloudy',
            '04d': 'mdi-weather-cloudy',
            '04n': 'mdi-weather-cloudy',
            '09d': 'mdi-weather-pouring',
            '09n': 'mdi-weather-pouring',
            '10d': 'mdi-weather-rainy',
            '10n': 'mdi-weather-rainy',
            '11d': 'mdi-weather-lightning',
            '11n': 'mdi-weather-lightning',
            '13d': 'mdi-weather-snowy',
            '13n': 'mdi-weather-snowy',
            '50d': 'mdi-weather-fog',
            '50n': 'mdi-weather-fog',
        };

        fetch(`https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&units=metric&appid=${apiKey}`)
            .then(response => response.json())
            .then(data => {
                const temperature = Math.round(data.main.temp);
                const location = data.name;
                const iconCode = data.weather[0].icon;
                const mdiClass = iconMap[iconCode] || 'mdi-weather-cloudy';

                document.getElementById('weatherIcon').className = 'mdi mr-2 ' + mdiClass;
                document.getElementById('tempValue').innerHTML = `${temperature}<sup>°C</sup>`;
                document.getElementById('location').textContent = location;
                document.getElementById('country').textContent = data.sys.country || 'Indonesia';
            })
            .catch(error => {
                console.error('Error fetching weather data:', error);
            });
    });

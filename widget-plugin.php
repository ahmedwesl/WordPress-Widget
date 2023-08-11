<?php
/*
Plugin Name: CNAlps Weather Widget
Description: A simple weather widget to display weather information using the WeatherWP API.
Version: 1.0
Author: Ahmed
*/

class CNAlps_Weather_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'cnalps_weather_widget',
            'CNAlps Weather Widget',
            array( 'description' => 'Displays weather information for a specified city and country.' )
        );
    }

    public function widget( $args, $instance ) {
        $city = isset( $instance['city'] ) ? $instance['city'] : 'Crest';
        $country = isset( $instance['country'] ) ? $instance['country'] : 'France';

        echo '<div class="cnalps-weather-widget">';
        echo '<div class="weather-title">Météo à <span id="city">' . esc_html( $city ) . '</span></div>';
        echo '<p>Température : <span id="temperature"></span> °C</p>';
        echo '<img id="weather-icon" src="" alt="">';
        echo '<p id="weather-description"></p>';
        echo '</div>';
        ?>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var temperatureElement = document.getElementById("temperature");
                var weatherIconElement = document.getElementById("weather-icon");
                var weatherDescriptionElement = document.getElementById("weather-description");
                var cityElement = document.getElementById("city");

                
                fetch('https://www.weatherwp.com/api/common/publicWeatherForLocation.php?city=<?php echo esc_js( $city ); ?>&country=<?php echo esc_js( $country ); ?>&language=french')
                    .then(response => response.json())
                    .then(data => {
                        temperatureElement.textContent = data.temp;
                        weatherIconElement.src = data.icon;
                        weatherDescriptionElement.textContent = data.description;
                        cityElement.textContent = '<?php echo esc_js( $city ); ?>';
                    })
                    .catch(error => {
                        console.error('Une erreur s\'est produite lors de l\'appel API :', error);
                    });
            });
        </script>
        <?php
    }

    public function form( $instance ) {
        $city = isset( $instance['city'] ) ? $instance['city'] : '';
        $country = isset( $instance['country'] ) ? $instance['country'] : '';

        echo '<label for="' . $this->get_field_id( 'city' ) . '">Ville :</label>';
        echo '<input type="text" class="widefat" id="' . $this->get_field_id( 'city' ) . '" name="' . $this->get_field_name( 'city' ) . '" value="' . esc_attr( $city ) . '"><br>';

        echo '<label for="' . $this->get_field_id( 'country' ) . '">Pays :</label>';
        echo '<input type="text" class="widefat" id="' . $this->get_field_id( 'country' ) . '" name="' . $this->get_field_name( 'country' ) . '" value="' . esc_attr( $country ) . '">';
    }

    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['city'] = sanitize_text_field( $new_instance['city'] );
        $instance['country'] = sanitize_text_field( $new_instance['country'] );
        return $instance;
    }
}

function register_cnalps_weather_widget() {
    register_widget( 'CNAlps_Weather_Widget' );
}
add_action( 'widgets_init', 'register_cnalps_weather_widget' );
?>

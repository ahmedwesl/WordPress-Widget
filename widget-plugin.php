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

        $api_url = "https://www.weatherwp.com/api/common/publicWeatherForLocation.php?city=$city&country=$country&language=french";
        $response = wp_remote_get( $api_url );

        if ( ! is_wp_error( $response ) ) {
            $body = wp_remote_retrieve_body( $response );
            $data = json_decode( $body );

            echo '<div class="cnalps-weather-widget">';
            echo '<div class="weather-title">Météo à ' . esc_html( $data->status_message ) . '</div>';
            echo '<p>Température : ' . esc_html( $data->temp ) . ' °C</p>';
            echo '<img src="' . esc_url( $data->icon ) . '" alt="' . esc_attr( $data->description ) . '">';
            echo '<p>' . esc_html( $data->description ) . '</p>';
            echo '</div>';
        }
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

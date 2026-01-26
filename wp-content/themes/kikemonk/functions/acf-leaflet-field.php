<?php
/**
 * Custom ACF Leaflet Map Field
 * Free alternative to Google Maps field
 */

class ACF_Field_Leaflet_Map extends acf_field {
    
    function __construct() {
        $this->name = 'leaflet_map';
        $this->label = 'Leaflet Map';
        $this->category = 'jquery';
        
        parent::__construct();
    }
    
    function render_field_settings($field) {
        acf_render_field_setting($field, array(
            'label' => 'Center Latitude',
            'instructions' => 'Center latitude for the map',
            'type' => 'text',
            'name' => 'center_lat',
            'default_value' => '16.7371',
        ));
        
        acf_render_field_setting($field, array(
            'label' => 'Center Longitude',
            'instructions' => 'Center longitude for the map',
            'type' => 'text',
            'name' => 'center_lng',
            'default_value' => '-92.6376',
        ));
        
        acf_render_field_setting($field, array(
            'label' => 'Zoom',
            'instructions' => 'Zoom level for the map',
            'type' => 'number',
            'name' => 'zoom',
            'default_value' => 14,
        ));
    }
    
    function render_field($field) {
        $value = wp_parse_args($field['value'], array(
            'address' => '',
            'lat' => $field['center_lat'] ?: '16.7371',
            'lng' => $field['center_lng'] ?: '-92.6376',
        ));
        
        $id = 'acf-leaflet-' . $field['key'];
        ?>
        <style>
            .acf-leaflet-map-field .leaflet-map-container {
                height: 400px;
                margin-top: 10px;
                border: 1px solid #ddd;
                border-radius: 4px;
                z-index: 1;
            }
            .acf-leaflet-map-field .leaflet-search-input {
                width: 100%;
                padding: 8px 12px;
                border: 1px solid #ddd;
                border-radius: 4px;
            }
        </style>
        <div class="acf-leaflet-map-field">
            <div class="acf-input-wrap">
                <input 
                    type="text" 
                    class="leaflet-search-input" 
                    placeholder="Buscar dirección en San Cristóbal..."
                    value="<?php echo esc_attr($value['address']); ?>"
                />
                <div id="<?php echo $id; ?>" class="leaflet-map-container"></div>
                
                <input type="hidden" name="<?php echo esc_attr($field['name']); ?>[address]" class="leaflet-address" value="<?php echo esc_attr($value['address']); ?>" />
                <input type="hidden" name="<?php echo esc_attr($field['name']); ?>[lat]" class="leaflet-lat" value="<?php echo esc_attr($value['lat']); ?>" />
                <input type="hidden" name="<?php echo esc_attr($field['name']); ?>[lng]" class="leaflet-lng" value="<?php echo esc_attr($value['lng']); ?>" />
            </div>
        </div>
        
        <script>
        (function($) {
            // Function to initialize map with safety checks
            function initACFLeafletMap(id, args) {
                var attempt = 0;
                var maxAttempts = 20; // Try for 2 seconds (20 * 100ms)

                var waitForLeaflet = setInterval(function() {
                    attempt++;

                    if (typeof L !== 'undefined') {
                        clearInterval(waitForLeaflet);
                        
                        var $container = $('#' + id);
                        if ($container.hasClass('leaflet-container')) return; // Already initialized

                        // Ensure container is visible for sizing
                        setTimeout(function() { 
                            var map = L.map(id).setView([args.lat, args.lng], args.zoom);
                            
                            L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                                attribution: '&copy; CARTO'
                            }).addTo(map);
                            
                            var marker = L.marker([args.lat, args.lng], {
                                draggable: true
                            }).addTo(map);

                            // Fields
                            var $wrapper = $container.closest('.acf-leaflet-map-field');
                            var $lat = $wrapper.find('.leaflet-lat');
                            var $lng = $wrapper.find('.leaflet-lng');
                            var $address = $wrapper.find('.leaflet-address');
                            var $search = $wrapper.find('.leaflet-search-input');

                            // Events
                            marker.on('dragend', function(e) {
                                var position = e.target.getLatLng();
                                $lat.val(position.lat);
                                $lng.val(position.lng);
                                reverseGeocode(position.lat, position.lng, $address, $search);
                            });

                            map.on('click', function(e) {
                                marker.setLatLng(e.latlng);
                                $lat.val(e.latlng.lat);
                                $lng.val(e.latlng.lng);
                                reverseGeocode(e.latlng.lat, e.latlng.lng, $address, $search);
                            });

                            // Resize check for tabs/hidden areas
                            map.invalidateSize();
                            setTimeout(function(){ map.invalidateSize(); }, 500);

                            // Search
                            var searchTimeout;
                            $search.on('input', function() {
                                clearTimeout(searchTimeout);
                                var query = $(this).val();
                                if (query.length > 3) {
                                    searchTimeout = setTimeout(function() {
                                        geocode(query, map, marker, $lat, $lng, $address);
                                    }, 500);
                                }
                            });

                        }, 100);

                    } else if (attempt >= maxAttempts) {
                        clearInterval(waitForLeaflet);
                        console.error('Leaflet JS not found for ACF Map ' + id);
                    }
                }, 100);
            }

            // Helpers
            function geocode(query, map, marker, $lat, $lng, $address) {
                $.get('https://nominatim.openstreetmap.org/search', {
                    q: query + ', San Cristóbal de las Casas, Chiapas',
                    format: 'json',
                    limit: 1
                }).done(function(data) {
                    if (data.length > 0) {
                        var result = data[0];
                        var lat = parseFloat(result.lat);
                        var lng = parseFloat(result.lon);
                        
                        $lat.val(lat);
                        $lng.val(lng);
                        $address.val(result.display_name);
                        
                        marker.setLatLng([lat, lng]);
                        map.setView([lat, lng], 16);
                        map.invalidateSize();
                    }
                });
            }

            function reverseGeocode(lat, lng, $address, $search) {
                $.get('https://nominatim.openstreetmap.org/reverse', {
                    lat: lat,
                    lon: lng,
                    format: 'json'
                }).done(function(data) {
                    if (data.display_name) {
                        $address.val(data.display_name);
                        $search.val(data.display_name);
                    }
                });
            }
            
            // Initialization Arguments
            var mapArgs = {
                lat: <?php echo $value['lat'] ?: 16.7371; ?>,
                lng: <?php echo $value['lng'] ?: -92.6376; ?>,
                zoom: <?php echo $field['zoom'] ?: 14; ?>
            };

            // Hook into ACF
            if (typeof acf !== 'undefined') {
                acf.addAction('ready', function() {
                    initACFLeafletMap('<?php echo $id; ?>', mapArgs);
                });
                acf.addAction('append', function() {
                    initACFLeafletMap('<?php echo $id; ?>', mapArgs);
                });
                // Fix for tabs
                acf.addAction('show_field', function() {
                     setTimeout(function() {
                         var mapEl = document.getElementById('<?php echo $id; ?>');
                         // Trigger resize event purely via JS if standard invalidateSize isn't reachable easily
                         // But we're inside closure. We'll rely on the initial double-invalidate.
                     }, 200);
                });
            } else {
                $(document).ready(function() {
                    initACFLeafletMap('<?php echo $id; ?>', mapArgs);
                });
            }
        })(jQuery);
        </script>
        <?php
    }
    
    function input_admin_enqueue_scripts() {
        // Enqueue Leaflet CSS and JS
        wp_enqueue_style('leaflet', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css');
        wp_enqueue_script('leaflet', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', array(), '1.9.4', true);
    }
    
    function format_value($value, $post_id, $field) {
        // Handle empty values
        if (empty($value)) {
            return false;
        }
        
        // If it's already an array, return it
        if (is_array($value)) {
            return array(
                'address' => isset($value['address']) ? $value['address'] : '',
                'lat' => isset($value['lat']) ? floatval($value['lat']) : 0,
                'lng' => isset($value['lng']) ? floatval($value['lng']) : 0,
            );
        }
        
        // If it's a string (legacy data), return default structure
        return array(
            'address' => $value,
            'lat' => floatval($field['center_lat']) ?: 16.7371,
            'lng' => floatval($field['center_lng']) ?: -92.6376,
        );
    }
}

// Register the field
new ACF_Field_Leaflet_Map();

@php
    $mapId = $mapId ?? 'map';
    $latInputId = $latInputId ?? 'latitude';
    $lngInputId = $lngInputId ?? 'longitude';
    $addressArId = $addressArId ?? 'address_ar';
    $addressEnId = $addressEnId ?? 'address_en';
    $initialLat = !empty($lat) ? (float)$lat : 15.3694;
    $initialLng = !empty($lng) ? (float)$lng : 44.1910;
    $apiKey = env('GOOGLE_MAPS_API_KEY') ?: 'AIzaSyB3Ja2gtos27GNBN5Bs-8bEHSRWOLkmzcU';
@endphp

<div class="map-picker-card border rounded-3 p-3 bg-white shadow-sm mb-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <label class="form-label fw-bold mb-0 text-dark fs-7">
            <i class="ti ti-map-pin text-danger me-1"></i> موقع العقار على الخريطة
        </label>
        <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2 fs-7" id="btn_current_loc_{{ $mapId }}">
            <i class="ti ti-current-location me-1"></i> موقعي الحالي
        </button>
    </div>

    <!-- Search Box Overlay -->
    <div class="position-relative mb-2">
        <div class="input-group input-group-sm">
            <span class="input-group-text bg-light border-end-0"><i class="ti ti-search text-muted"></i></span>
            <input id="pac_input_{{ $mapId }}" class="form-control border-start-0 fs-7" type="text" placeholder="ابحث عن مكان، مدينة، أو اسم معلم..." />
        </div>
    </div>

    <!-- Map Canvas Container -->
    <div id="{{ $mapId }}" style="height: 320px; width: 100%; border-radius: 10px;" class="border shadow-inner position-relative"></div>

    <!-- Live Coordinate & Location Info Bar -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-2 pt-2 border-top fs-7">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-secondary-subtle text-dark border font-monospace" id="lat_display_{{ $mapId }}">
                Lat: {{ $lat ? number_format($lat, 6) : 'غير محدد' }}
            </span>
            <span class="badge bg-secondary-subtle text-dark border font-monospace" id="lng_display_{{ $mapId }}">
                Lng: {{ $lng ? number_format($lng, 6) : 'غير محدد' }}
            </span>
        </div>
        <small class="text-muted" id="geo_status_{{ $mapId }}">انقر على الخريطة أو اسحب الدبوس لتغيير الموقع</small>
    </div>

    <!-- Hidden / Input Elements for Coordinates -->
    <input type="hidden" id="{{ $latInputId }}" name="{{ $latInputId }}" value="{{ $lat }}">
    <input type="hidden" id="{{ $lngInputId }}" name="{{ $lngInputId }}" value="{{ $lng }}">
</div>

<script>
    (function() {
        let map, marker, geocoder;
        let isLeafletActive = false;
        let leafletMap, leafletMarker;
        const initialPos = { lat: {{ $initialLat }}, lng: {{ $initialLng }} };

        window.gm_authFailure = function() {
            console.warn("Google Maps API Auth Failure detected. Switching to OpenStreetMap (Leaflet)...");
            initLeafletFallback();
        };

        function updatePosition(lat, lng) {
            const numLat = parseFloat(lat);
            const numLng = parseFloat(lng);

            const latInput = document.getElementById("{{ $latInputId }}");
            const lngInput = document.getElementById("{{ $lngInputId }}");
            if (latInput) latInput.value = numLat;
            if (lngInput) lngInput.value = numLng;

            const latDisp = document.getElementById("lat_display_{{ $mapId }}");
            const lngDisp = document.getElementById("lng_display_{{ $mapId }}");
            if (latDisp) latDisp.innerText = "Lat: " + numLat.toFixed(6);
            if (lngDisp) lngDisp.innerText = "Lng: " + numLng.toFixed(6);
        }

        function setAddressFields(address) {
            const addrAr = document.getElementById("{{ $addressArId }}");
            const addrEn = document.getElementById("{{ $addressEnId }}");
            if (addrAr && !addrAr.value.trim()) addrAr.value = address;
            if (addrEn && !addrEn.value.trim()) addrEn.value = address;
        }

        // 1. Google Maps Initialization
        function initGoogleMap() {
            const mapContainer = document.getElementById("{{ $mapId }}");
            if (!mapContainer || isLeafletActive) return;

            try {
                geocoder = new google.maps.Geocoder();

                map = new google.maps.Map(mapContainer, {
                    zoom: {{ !empty($lat) && !empty($lng) ? 15 : 12 }},
                    center: initialPos,
                    mapTypeControl: true,
                    streetViewControl: false,
                    fullscreenControl: true,
                    zoomControl: true,
                });

                marker = new google.maps.Marker({
                    position: initialPos,
                    map: map,
                    draggable: true,
                    title: "موقع العقار"
                });

                const searchInput = document.getElementById("pac_input_{{ $mapId }}");
                if (searchInput) {
                    const searchBox = new google.maps.places.SearchBox(searchInput);
                    searchInput.addEventListener('keydown', (e) => {
                        if (e.key === 'Enter' || e.keyCode === 13) e.preventDefault();
                    });

                    searchBox.addListener("places_changed", () => {
                        const places = searchBox.getPlaces();
                        if (places.length === 0) return;

                        const place = places[0];
                        if (!place.geometry || !place.geometry.location) return;

                        const loc = place.geometry.location;
                        marker.setPosition(loc);
                        updatePosition(loc.lat(), loc.lng());

                        if (place.formatted_address) {
                            setAddressFields(place.formatted_address);
                        } else {
                            reverseGeocodeGoogle(loc);
                        }

                        if (place.geometry.viewport) {
                            map.fitBounds(place.geometry.viewport);
                        } else {
                            map.setCenter(loc);
                            map.setZoom(16);
                        }
                    });
                }

                marker.addListener('dragend', function(e) {
                    updatePosition(e.latLng.lat(), e.latLng.lng());
                    reverseGeocodeGoogle(e.latLng);
                });

                map.addListener('click', function(e) {
                    marker.setPosition(e.latLng);
                    updatePosition(e.latLng.lat(), e.latLng.lng());
                    reverseGeocodeGoogle(e.latLng);
                });
            } catch(e) {
                console.warn("Failed to initialize Google Maps:", e);
                initLeafletFallback();
            }
        }

        function reverseGeocodeGoogle(latLng) {
            if (!geocoder) return;
            document.getElementById("geo_status_{{ $mapId }}").innerText = "جاري جلب العنوان...";
            geocoder.geocode({ 'location': latLng }, function (results, status) {
                if (status === 'OK' && results[0]) {
                    setAddressFields(results[0].formatted_address);
                    document.getElementById("geo_status_{{ $mapId }}").innerText = "تم تحديث العنوان من الخريطة";
                } else {
                    document.getElementById("geo_status_{{ $mapId }}").innerText = "تم تحديد الإحداثيات بنجاح";
                }
            });
        }

        // 2. OpenStreetMap / Leaflet Fallback Initialization
        function initLeafletFallback() {
            if (isLeafletActive) return;
            isLeafletActive = true;

            const mapContainer = document.getElementById("{{ $mapId }}");
            if (!mapContainer) return;
            mapContainer.innerHTML = ""; // Clear error message overlay

            // Inject Leaflet CSS & JS dynamically if not present
            if (!document.getElementById('leaflet_css')) {
                const link = document.createElement('link');
                link.id = 'leaflet_css';
                link.rel = 'stylesheet';
                link.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
                document.head.appendChild(link);
            }

            const loadLeafletScript = function(callback) {
                if (typeof L === 'object') {
                    callback();
                    return;
                }
                if (!document.getElementById('leaflet_js')) {
                    const script = document.createElement('script');
                    script.id = 'leaflet_js';
                    script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
                    script.onload = callback;
                    document.head.appendChild(script);
                } else {
                    setTimeout(callback, 300);
                }
            };

            loadLeafletScript(function() {
                leafletMap = L.map('{{ $mapId }}').setView([initialPos.lat, initialPos.lng], {{ !empty($lat) && !empty($lng) ? 15 : 12 }});

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap'
                }).addTo(leafletMap);

                leafletMarker = L.marker([initialPos.lat, initialPos.lng], { draggable: true }).addTo(leafletMap);

                leafletMarker.on('dragend', function(e) {
                    const position = leafletMarker.getLatLng();
                    updatePosition(position.lat, position.lng);
                    reverseGeocodeNominatim(position.lat, position.lng);
                });

                leafletMap.on('click', function(e) {
                    leafletMarker.setLatLng(e.latlng);
                    updatePosition(e.latlng.lat, e.latlng.lng);
                    reverseGeocodeNominatim(e.latlng.lat, e.latlng.lng);
                });

                // Nominatim Search for input
                const searchInput = document.getElementById("pac_input_{{ $mapId }}");
                if (searchInput) {
                    searchInput.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter' || e.keyCode === 13) {
                            e.preventDefault();
                            const query = searchInput.value.trim();
                            if (query) {
                                document.getElementById("geo_status_{{ $mapId }}").innerText = "جاري البحث...";
                                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`)
                                    .then(res => res.json())
                                    .then(data => {
                                        if (data && data.length > 0) {
                                            const lat = parseFloat(data[0].lat);
                                            const lon = parseFloat(data[0].lon);
                                            leafletMap.setView([lat, lon], 16);
                                            leafletMarker.setLatLng([lat, lon]);
                                            updatePosition(lat, lon);
                                            setAddressFields(data[0].display_name);
                                            document.getElementById("geo_status_{{ $mapId }}").innerText = "تم العثور على الموقع";
                                        } else {
                                            document.getElementById("geo_status_{{ $mapId }}").innerText = "لم يتم العثور على نتائج للبحث";
                                        }
                                    }).catch(() => {
                                        document.getElementById("geo_status_{{ $mapId }}").innerText = "تعذر الاتصال بخدمة البحث";
                                    });
                            }
                        }
                    });
                }
            });
        }

        function reverseGeocodeNominatim(lat, lng) {
            document.getElementById("geo_status_{{ $mapId }}").innerText = "جاري جلب العنوان...";
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                .then(res => res.json())
                .then(data => {
                    if (data && data.display_name) {
                        setAddressFields(data.display_name);
                        document.getElementById("geo_status_{{ $mapId }}").innerText = "تم تحديث العنوان من الخريطة";
                    } else {
                        document.getElementById("geo_status_{{ $mapId }}").innerText = "تم تحديد الإحداثيات بنجاح";
                    }
                }).catch(() => {
                    document.getElementById("geo_status_{{ $mapId }}").innerText = "تم تحديد الإحداثيات بنجاح";
                });
        }

        // Current Location Button
        const currentLocBtn = document.getElementById("btn_current_loc_{{ $mapId }}");
        if (currentLocBtn) {
            currentLocBtn.addEventListener("click", function() {
                if (navigator.geolocation) {
                    document.getElementById("geo_status_{{ $mapId }}").innerText = "جاري جلب موقعك...";
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;
                            if (isLeafletActive && leafletMap && leafletMarker) {
                                leafletMap.setView([lat, lng], 16);
                                leafletMarker.setLatLng([lat, lng]);
                                reverseGeocodeNominatim(lat, lng);
                            } else if (map && marker) {
                                const pos = { lat: lat, lng: lng };
                                map.setCenter(pos);
                                map.setZoom(16);
                                marker.setPosition(pos);
                                reverseGeocodeGoogle(pos);
                            }
                            updatePosition(lat, lng);
                            document.getElementById("geo_status_{{ $mapId }}").innerText = "تمت محاذاة الخريطة مع موقعك الحالي";
                        },
                        () => {
                            alert("تعذر تحديد موقعك الحالي. يرجى تفعيل إذن الموقع الجغرافي.");
                            document.getElementById("geo_status_{{ $mapId }}").innerText = "تعذر تحديد الموقع الجغرافي";
                        }
                    );
                }
            });
        }

        // Load Google Maps script or trigger Leaflet fallback
        function loadMapScript() {
            if (typeof google === 'object' && typeof google.maps === 'object') {
                initGoogleMap();
                return;
            }

            const apiKey = "{{ $apiKey }}";
            if (!apiKey || apiKey === '' || apiKey.includes('YOUR_API_KEY')) {
                initLeafletFallback();
                return;
            }

            const scriptId = 'google_maps_sdk';
            if (!document.getElementById(scriptId)) {
                window.initMap_{{ $mapId }} = function() {
                    initGoogleMap();
                };
                const script = document.createElement('script');
                script.id = scriptId;
                script.src = `https://maps.googleapis.com/maps/api/js?key=${apiKey}&libraries=places&callback=initMap_{{ $mapId }}`;
                script.onerror = function() {
                    console.warn("Failed to load Google Maps script. Falling back to OpenStreetMap...");
                    initLeafletFallback();
                };
                script.async = true;
                script.defer = true;
                document.head.appendChild(script);
            } else {
                setTimeout(initGoogleMap, 500);
            }
        }

        if (document.readyState === 'complete' || document.readyState === 'interactive') {
            loadMapScript();
        } else {
            document.addEventListener('DOMContentLoaded', loadMapScript);
        }
    })();
</script>

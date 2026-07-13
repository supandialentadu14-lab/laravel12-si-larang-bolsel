<script>
    const initialLat = {{ $lat ?: 0.4317 }};
    const initialLng = {{ $lng ?: 123.4817 }};
    const hasInitial = {{ $lat && $lng ? 'true' : 'false' }};

    const pickerMap = L.map('picker-map').setView([initialLat, initialLng], hasInitial ? 15 : 10);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19,
    }).addTo(pickerMap);

    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');
    const pasteInput = document.getElementById('paste-coord');
    const pasteFeedback = document.getElementById('paste-feedback');

    let pickerMarker = null;
    if (hasInitial) {
        pickerMarker = L.marker([initialLat, initialLng]).addTo(pickerMap);
    }

    function setPoint(lat, lng, recenter = true) {
        latInput.value = lat.toFixed(7);
        lngInput.value = lng.toFixed(7);

        if (pickerMarker) {
            pickerMarker.setLatLng([lat, lng]);
        } else {
            pickerMarker = L.marker([lat, lng]).addTo(pickerMap);
        }

        if (recenter) {
            pickerMap.setView([lat, lng], 16);
        }
    }

    // 1. Klik di peta -> update input lat/long
    pickerMap.on('click', function (e) {
        setPoint(e.latlng.lat, e.latlng.lng, false);
    });

    // 2. Ketik manual di input Latitude/Longitude -> update peta
    function handleManualInput() {
        const lat = parseFloat(latInput.value);
        const lng = parseFloat(lngInput.value);
        if (!isNaN(lat) && !isNaN(lng) && lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
            setPoint(lat, lng, true);
        }
    }
    latInput.addEventListener('change', handleManualInput);
    lngInput.addEventListener('change', handleManualInput);

    // 3. Tempel koordinat dari Google Maps
    // Mendukung 2 format:
    //   a) Desimal:  0.4317, 123.4817   atau   0.4317 123.4817
    //   b) DMS:      0°26'16.9"N 124°18'57.4"E
    function dmsToDecimal(deg, min, sec, dir) {
        let dec = parseFloat(deg) + parseFloat(min) / 60 + parseFloat(sec) / 3600;
        if (dir === 'S' || dir === 'W') dec = -dec;
        return dec;
    }

    function parsePastedCoord(text) {
        const cleaned = text.trim();

        // --- Coba format DMS dulu: contoh 0°26'16.9"N 124°18'57.4"E ---
        const dmsRegex = /(\d+)[°]\s*(\d+)['\u2019]\s*([\d.]+)["\u201d]?\s*([NSns])[,\s]+(\d+)[°]\s*(\d+)['\u2019]\s*([\d.]+)["\u201d]?\s*([EWew])/;
        const dmsMatch = cleaned.match(dmsRegex);
        if (dmsMatch) {
            const lat = dmsToDecimal(dmsMatch[1], dmsMatch[2], dmsMatch[3], dmsMatch[4].toUpperCase());
            const lng = dmsToDecimal(dmsMatch[5], dmsMatch[6], dmsMatch[7], dmsMatch[8].toUpperCase());
            if (lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
                return { lat, lng };
            }
        }

        // --- Coba format desimal: 0.4317, 123.4817 ---
        const decRegex = /(-?\d+(?:\.\d+)?)\s*[,;\s]\s*(-?\d+(?:\.\d+)?)/;
        const decMatch = cleaned.match(decRegex);
        if (decMatch) {
            const lat = parseFloat(decMatch[1]);
            const lng = parseFloat(decMatch[2]);
            if (!isNaN(lat) && !isNaN(lng) && lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
                return { lat, lng };
            }
        }

        return null;
    }

    document.getElementById('btn-apply-paste').addEventListener('click', function () {
        const result = parsePastedCoord(pasteInput.value);
        if (!result) {
            pasteFeedback.textContent = 'Format tidak dikenali. Coba format desimal (0.4317, 123.4817) atau format Google Maps (0°26\'16.9"N 124°18\'57.4"E)';
            pasteFeedback.className = 'paste-feedback err';
            return;
        }
        setPoint(result.lat, result.lng, true);
        pasteFeedback.textContent = `Koordinat berhasil diterapkan: ${result.lat.toFixed(7)}, ${result.lng.toFixed(7)}`;
        pasteFeedback.className = 'paste-feedback ok';
    });

    pasteInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('btn-apply-paste').click();
        }
    });

    // Coba pusatkan ke lokasi saat ini (opsional, kalau belum ada titik dan browser mendukung)
    if (!hasInitial && navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (pos) {
            const { latitude, longitude } = pos.coords;
            if (latitude > -1 && latitude < 2 && longitude > 122 && longitude < 125) {
                pickerMap.setView([latitude, longitude], 13);
            }
        }, function () {
            // diabaikan kalau user menolak izin lokasi
        });
    }
</script>

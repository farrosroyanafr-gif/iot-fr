<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🌱 IoT Dashboard - Monitoring Tanaman
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Card Sensor Row --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Suhu --}}
                <div class="bg-white rounded-xl shadow p-6 flex items-center gap-4">
                    <div class="text-4xl">🌡️</div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Suhu Udara</p>
                        <p class="text-3xl font-bold text-red-500" id="temperature">-- °C</p>
                        <p class="text-xs text-gray-400">DHT22</p>
                    </div>
                </div>

                {{-- Kelembaban Udara --}}
                <div class="bg-white rounded-xl shadow p-6 flex items-center gap-4">
                    <div class="text-4xl">💧</div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Kelembaban Udara</p>
                        <p class="text-3xl font-bold text-blue-500" id="humidity_air">-- %</p>
                        <p class="text-xs text-gray-400">DHT22</p>
                    </div>
                </div>

                {{-- Kelembaban Tanah --}}
                <div class="bg-white rounded-xl shadow p-6 flex items-center gap-4">
                    <div class="text-4xl">🌍</div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Kelembaban Tanah</p>
                        <p class="text-3xl font-bold text-green-500" id="humidity_soil">-- %</p>
                        <p class="text-xs text-gray-400">Soil Sensor</p>
                    </div>
                </div>

            </div>

            {{-- Kontrol Pompa --}}
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">💦 Kontrol Pompa Air</h3>
                <div class="flex items-center gap-6">
                    <div id="pump-status-badge" class="px-4 py-2 rounded-full text-white font-semibold text-sm bg-gray-400">
                        Status: Memuat...
                    </div>
                    <button onclick="togglePump()"
                        id="pump-btn"
                        class="px-6 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold transition">
                        Toggle Pompa
                    </button>
                </div>
            </div>

            {{-- Tabel Data Terakhir --}}
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">📋 Data Sensor Terbaru</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-600">
                        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                            <tr>
                                <th class="px-4 py-3">Waktu</th>
                                <th class="px-4 py-3">Suhu (°C)</th>
                                <th class="px-4 py-3">Kelembaban Udara (%)</th>
                                <th class="px-4 py-3">Kelembaban Tanah (%)</th>
                            </tr>
                        </thead>
                        <tbody id="sensor-table">
                            <tr><td colspan="4" class="px-4 py-3 text-center text-gray-400">Memuat data...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    {{-- Script Auto Refresh --}}
    <script>
        async function fetchSensorData() {
            try {
                const res = await fetch('/api/data/latest');
                const data = await res.json();
                document.getElementById('temperature').textContent = data.temperature + ' °C';
                document.getElementById('humidity_air').textContent = data.humidity_air + ' %';
                document.getElementById('humidity_soil').textContent = data.humidity_soil + ' %';
            } catch (e) {}
        }

        async function fetchPumpStatus() {
            try {
                const res = await fetch('/api/pump/status');
                const data = await res.json();
                const badge = document.getElementById('pump-status-badge');
                if (data.is_on) {
                    badge.textContent = 'Status: NYALA 🟢';
                    badge.className = 'px-4 py-2 rounded-full text-white font-semibold text-sm bg-green-500';
                } else {
                    badge.textContent = 'Status: MATI 🔴';
                    badge.className = 'px-4 py-2 rounded-full text-white font-semibold text-sm bg-red-500';
                }
            } catch (e) {}
        }

        async function fetchTable() {
            try {
                const res = await fetch('/api/data/history');
                const data = await res.json();
                const tbody = document.getElementById('sensor-table');
                tbody.innerHTML = data.map(row => `
                    <tr class="border-t">
                        <td class="px-4 py-2">${row.created_at}</td>
                        <td class="px-4 py-2">${row.temperature}</td>
                        <td class="px-4 py-2">${row.humidity_air}</td>
                        <td class="px-4 py-2">${row.humidity_soil}</td>
                    </tr>
                `).join('');
            } catch (e) {}
        }

        async function togglePump() {
            await fetch('/api/pump/toggle', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            });
            fetchPumpStatus();
        }

        // Auto refresh tiap 5 detik
        fetchSensorData();
        fetchPumpStatus();
        fetchTable();
        setInterval(() => {
            fetchSensorData();
            fetchPumpStatus();
            fetchTable();
        }, 5000);
    </script>

</x-app-layout>
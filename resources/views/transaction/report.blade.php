<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800  dark:text-gray-200 leading-tight">
            Reports
        </h2>
    </x-slot>

    <div class="p-6 dark:text-black dark:bg-gray-800 rounded-lg shadow-lg">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b">
                    <form class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <!-- Date Range -->
                        <div class="col-span-2 flex flex-col md:flex-row items-center gap-2">
                            <input type="date" placeholder="dd/mm/yyyy" class="form-input w-full rounded-md">
                            <span class="dark:text-gray-200 text-center md:text-left">To</span>
                            <input type="date" placeholder="dd/mm/yyyy" class="form-input w-full rounded-md">
                        </div>

                        <!-- Select Segment -->
                        <div>
                            <select id="segmentSelect" name="segment" class="form-select w-full rounded-md">
                                <option>--Select Segment--</option>
                                @foreach ($segments as $segment)
                                    <option value="{{ $segment->_id }}">{{ $segment->Gr_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Select Line -->
                        <div>
                            <select id="lineSelect" name="line" class="form-select w-full rounded-md" disabled>
                                <option>--Select Line--</option>
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <div class="col-span-1 md:col-span-1">
                            <button type="submit"
                                class="w-full md:w-auto px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
                                Submit
                            </button>

                        </div>
                    </form>

                </div>

            </div>

        </div>
        <div class="flex items-center justify-center w-full h-64 lg:h-96">
            <canvas id="myChart"></canvas>

        </div>
        <div class="flex items-center justify-center mt-5">

            <button id="downloadButton"
                class="w-full md:w-auto px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                disabled>
                Download Data
            </button>
        </div>

    </div>
    <script>
        const ctx = document.getElementById('myChart');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                datasets: [{
                    label: '# of Votes',
                    data: [12, 19, 3, 5, 2, 3],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const lineSelect = document.querySelector('select[name="line"]'); // Sesuaikan dengan name select line
            const downloadButton = document.getElementById('downloadButton');

            // Cek perubahan pada select line
            lineSelect.addEventListener('change', function() {
                if (lineSelect.value !== '--Select Line--') {
                    downloadButton.disabled = false; // Aktifkan tombol jika line dipilih
                } else {
                    downloadButton.disabled = true; // Nonaktifkan tombol jika line tidak dipilih
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const segmentSelect = document.getElementById('segmentSelect');
            const lineSelect = document.getElementById('lineSelect');
            const downloadButton = document.getElementById('downloadButton');

            // Cek perubahan pada select Segment
            segmentSelect.addEventListener('change', function() {
                const selectedSegmentId = segmentSelect.value;

                if (selectedSegmentId !== '--Select Segment--') {
                    // Kirim permintaan AJAX untuk mengambil data Line
                    fetch(`/get-lines?segment_id=${selectedSegmentId}`)
                        .then(response => response.json())
                        .then(data => {
                            // Kosongkan opsi Line yang ada
                            lineSelect.innerHTML = '<option>--Select Line--</option>';

                            // Tambahkan opsi Line baru
                            data.forEach(line => {
                                const option = document.createElement('option');
                                option.value = line._id;
                                option.textContent = line.Ln_name;
                                lineSelect.appendChild(option);
                            });

                            // Aktifkan select Line
                            lineSelect.disabled = false;
                        })
                        .catch(error => console.error('Error fetching lines:', error));
                } else {
                    // Jika Segment tidak dipilih, nonaktifkan select Line dan kosongkan opsi
                    lineSelect.innerHTML = '<option>--Select Line--</option>';
                    lineSelect.disabled = true;
                    downloadButton.disabled = true; // Nonaktifkan tombol Download Data
                }
            });

            // Cek perubahan pada select Line
            lineSelect.addEventListener('change', function() {
                if (lineSelect.value !== '--Select Line--') {
                    downloadButton.disabled = false; // Aktifkan tombol jika Line dipilih
                } else {
                    downloadButton.disabled = true; // Nonaktifkan tombol jika Line tidak dipilih
                }
            });
        });
    </script>

</x-app-layout>

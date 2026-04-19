<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verifikasi Alamat Siswa</title>

    @vite(['resources/js/app.js'])
    <!-- Tailwind CDN (sementara, nanti bisa Vite) -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <div class="min-h-screen">
        <!-- Navbar -->
        <nav class="bg-blue-600 text-white p-4 shadow">
            <h1 class="text-lg font-semibold">📍 Verifikasi Alamat Siswa</h1>
        </nav>

        <!-- <div class="max-w-4xl mx-auto mt-4">

            @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-3">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-100 text-red-700 p-3 rounded mb-3">
                {{ session('error') }}
            </div>
            @endif

        </div> -->

        <!-- Content -->
        <main class="p-4">
            @yield('content')
        </main>
    </div>

    <script>
        const searchInput = document.getElementById('searchInput');
        const items = document.querySelectorAll('.student-item');
        const noResult = document.getElementById('noResult');

        searchInput.addEventListener('keyup', function() {
            const keyword = this.value.toLowerCase();
            let visible = 0;

            items.forEach(item => {
                const name = item.dataset.name;
                const nisn = item.dataset.nisn;

                if (name.includes(keyword)) {
                    item.style.display = '';
                    visible++;
                } else {
                    item.style.display = 'none';
                }
            });

            noResult.classList.toggle('hidden', visible !== 0);
        });
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}"></script>

</body>

</html>
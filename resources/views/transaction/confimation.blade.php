<x-guest-layout class="">
    <div style="margin-bottom: 17rem; margin-top: 6rem;"
        class="max-w-md mx-auto mt-10 lg:mb-10 p-6 bg-white shadow-md rounded-lg">
        <!-- Bagian Atas: Judul -->
        <div class="text-center">
            <h1 class="text-2xl font-bold text-gray-800">Konfirmasi Approval</h1>
        </div>

        <!-- Bagian Tengah: Deskripsi -->
        <div class="mt-6 text-center">
            <p class="text-gray-600">
                Apakah Anda yakin ingin menyetujui atau menolak permintaan ini? Pastikan untuk memeriksa detail dengan
                cermat sebelum melanjutkan.
            </p>
        </div>

        <!-- Bagian Bawah: Tombol Approve dan Reject -->
        <div class="mt-8 flex justify-around">
            <form method="POST" action="{{ route('acc') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $appr->token ?? '' }}">
                <input type="hidden" name="no_order" value="{{ $appr->no_order }}">
                <button type="submit" class="px-4 py-2 bg-green-500 text-white font-bold rounded hover:bg-green-600">
                    Approve
                </button>
            </form>

            <form method="POST" action="">
                @csrf
                <input type="hidden" name="token" value="{{ $appr }}">
                <button type="submit" class="px-4 py-2 bg-red-500 text-white font-bold rounded hover:bg-red-600">
                    Reject
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>

<div class="flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-lg flex flex-col min-h-screen">

        <!-- Logo -->
        <div class="h-16 flex items-center justify-start border-b px-4">
            <span class="font-bold text-lg">Pengaduan</span>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 p-4 space-y-2">

            <a href=""
            wire:navigate
            class="block px-4 py-2 rounded-lg hover:bg-gray-100">
            Dashboard
            </a>

            @if(auth()->user()->role === 'admin')

            <a href=""
            wire:navigate
            class="block px-4 py-2 rounded-lg hover:bg-gray-100">
            Products
            </a>

            <a href=""
            wire:navigate
            class="block px-4 py-2 rounded-lg hover:bg-gray-100">
            Pembelian
            </a>

            <a href=""
            wire:navigate
            class="block px-4 py-2 rounded-lg hover:bg-gray-100">
            Users
            </a>

            @endif

            @if(auth()->user()->role === 'petugas')

            <a href="{{ route ("products.index") }}"
            wire:navigate
            class="block px-4 py-2 rounded-lg hover:bg-gray-100">
            Products
            </a>

            <a href=""
            wire:navigate
            class="block px-4 py-2 rounded-lg hover:bg-gray-100">
            Pembelian
            </a>

            @endif

        </nav>

        <div class="border-t p-4">
            <form method="POST" action="">
                @csrf
                <button
                    type="submit"
                    class="w-full text-left hover:bg-gray-100 text-black px-4 py-2 rounded-lg transition">
                    Logout
                </button>
            </form>
        </div>

    </aside>
</div>

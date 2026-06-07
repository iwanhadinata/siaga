<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="bg-surface rounded-lg shadow-sm border border-gray-100 p-6" x-data="jemaatSSP()">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h2 class="text-xl font-bold text-primary">Daftar Jemaat</h2>
            <p class="text-sm text-gray-500">Kelola data seluruh jemaat yang terdaftar</p>
        </div>
        <div class="flex items-center gap-3 w-full md:w-auto">
            <input type="text" x-model="search" @input.debounce.500ms="searchData()" placeholder="Cari Nama / NIJ..." class="border border-secondary rounded-md px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-tertiary w-full md:w-64">
            <a href="<?= url_to('jemaat/create') ?>" class="bg-tertiary text-on-primary px-4 py-2 rounded-md text-sm font-medium hover:opacity-90 whitespace-nowrap text-center">
                Tambah Jemaat
            </a>
        </div>
    </div>

    <!-- Flash Message -->
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="py-3 px-4 text-sm font-semibold text-primary cursor-pointer hover:bg-gray-50" @click="sortBy('nij')">
                        NIJ
                        <span x-show="sort === 'nij'" x-text="dir === 'ASC' ? '↑' : '↓'" class="ml-1 text-tertiary"></span>
                    </th>
                    <th class="py-3 px-4 text-sm font-semibold text-primary cursor-pointer hover:bg-gray-50" @click="sortBy('nama_lengkap')">
                        Nama Lengkap
                        <span x-show="sort === 'nama_lengkap'" x-text="dir === 'ASC' ? '↑' : '↓'" class="ml-1 text-tertiary"></span>
                    </th>
                    <th class="py-3 px-4 text-sm font-semibold text-primary">Jenis Kelamin</th>
                    <th class="py-3 px-4 text-sm font-semibold text-primary">Status</th>
                    <th class="py-3 px-4 text-sm font-semibold text-primary text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="item in jemaatList" :key="item.id">
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4 text-sm text-gray-600" x-text="item.nij"></td>
                        <td class="py-3 px-4 text-sm text-gray-800 font-medium" x-text="item.nama_lengkap"></td>
                        <td class="py-3 px-4 text-sm text-gray-600" x-text="item.jenis_kelamin === 'L' ? 'Laki-Laki' : (item.jenis_kelamin === 'P' ? 'Perempuan' : item.jenis_kelamin)"></td>
                        <td class="py-3 px-4 text-sm text-gray-600" x-text="item.status_pernikahan"></td>
                        <td class="py-3 px-4 text-sm text-center">
                            <!-- TODO: implement edit and delete routes using item.id -->
                            <button class="text-tertiary hover:underline text-sm font-medium mr-2">Edit</button>
                            <button class="text-red-500 hover:underline text-sm font-medium">Hapus</button>
                        </td>
                    </tr>
                </template>
                <tr x-show="jemaatList.length === 0 && !isLoading">
                    <td colspan="5" class="py-6 text-center text-gray-500 text-sm">Tidak ada data ditemukan.</td>
                </tr>
                <tr x-show="isLoading">
                    <td colspan="5" class="py-6 text-center text-gray-500 text-sm">Memuat data...</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex justify-between items-center mt-6">
        <div class="text-sm text-gray-500">
            Menampilkan halaman <span x-text="page" class="font-medium"></span> dari <span x-text="totalPages" class="font-medium"></span> (Total: <span x-text="total" class="font-medium"></span> data)
        </div>
        <div class="flex space-x-2">
            <button @click="prevPage()" :disabled="page <= 1" class="px-3 py-1 border border-secondary rounded-md text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50 transition-colors">Prev</button>
            <button @click="nextPage()" :disabled="page >= totalPages" class="px-3 py-1 border border-secondary rounded-md text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50 transition-colors">Next</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('jemaatSSP', () => ({
            jemaatList: [],
            search: '',
            sort: 'nama_lengkap',
            dir: 'ASC',
            page: 1,
            limit: 10,
            total: 0,
            totalPages: 1,
            isLoading: false,

            init() {
                this.fetchData();
            },

            searchData() {
                this.page = 1;
                this.fetchData();
            },

            async fetchData() {
                this.isLoading = true;
                // Pastikan route ini sesuai dengan konfigurasi di Routes.php
                const url = new URL('<?= url_to('jemaat/ajaxList') ?>', window.location.origin);
                url.searchParams.append('search', this.search);
                url.searchParams.append('sort', this.sort);
                url.searchParams.append('dir', this.dir);
                url.searchParams.append('page', this.page);
                url.searchParams.append('limit', this.limit);

                try {
                    const response = await fetch(url.toString(), {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    const result = await response.json();

                    this.jemaatList = result.data || [];
                    this.total = result.total || 0;
                    this.page = result.current_page || 1;
                    this.totalPages = result.total_pages || 1;
                } catch (error) {
                    console.error('Error fetching data:', error);
                } finally {
                    this.isLoading = false;
                }
            },

            sortBy(col) {
                if (this.sort === col) {
                    this.dir = this.dir === 'ASC' ? 'DESC' : 'ASC';
                } else {
                    this.sort = col;
                    this.dir = 'ASC';
                }
                this.page = 1;
                this.fetchData();
            },

            nextPage() {
                if (this.page < this.totalPages) {
                    this.page++;
                    this.fetchData();
                }
            },

            prevPage() {
                if (this.page > 1) {
                    this.page--;
                    this.fetchData();
                }
            }
        }));
    });
</script>
<?= $this->endSection() ?>
<template>
  <div class="app-container">
    <header class="header">
      <h1>SIAKAD <span>Mahasiswa</span></h1>
      <p>Sistem Informasi Akademik & Kelola Data</p>
    </header>

    <main class="main-content">
      <!-- Form Card -->
      <section class="card form-card">
        <div class="card-header">
          <h3>{{ isEdit ? 'Edit Data Mahasiswa' : 'Tambah Mahasiswa Baru' }}</h3>
        </div>
        
        <form @submit.prevent="saveData" class="form-grid">
          <div class="form-group">
            <label>Nama Lengkap</label>
            <input v-model="form.nama" placeholder="Masukkan nama lengkap" required />
          </div>

          <div class="form-group">
            <label>Jenis Kelamin</label>
            <select v-model="form.jenis_kelamin" required>
              <option value="" disabled>Pilih Jenis Kelamin</option>
              <option value="Laki-laki">Laki-laki</option>
              <option value="Perempuan">Perempuan</option>
            </select>
          </div>

          <div class="form-group">
            <label>Tempat Lahir</label>
            <input v-model="form.tempat_lhr" placeholder="Kota lahir" required />
          </div>

          <div class="form-group">
            <label>Tanggal Lahir</label>
            <input type="date" v-model="form.tanggal_lhr" required />
          </div>

          <div class="form-group full-width">
            <label>Alamat</label>
            <textarea v-model="form.alamat" rows="3" placeholder="Alamat lengkap..."></textarea>
          </div>

          <div class="form-actions full-width">
            <button type="submit" class="btn btn-primary">
              {{ isEdit ? 'Simpan Perubahan' : 'Tambah Data' }}
            </button>
            <button type="button" v-if="isEdit" @click="resetForm" class="btn btn-secondary">
              Batal
            </button>
          </div>
        </form>
      </section>

      <!-- Table Card -->
      <section class="card table-card">
        <div class="card-header">
          <h3>Daftar Mahasiswa</h3>
          <span class="badge">{{ mahasiswa.length }} Total</span>
        </div>

        <div class="table-responsive">
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Jenis Kelamin</th>
                <th>Tempat, Tgl Lahir</th>
                <th>Alamat</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="mahasiswa.length === 0">
                <td colspan="6" class="empty-state">Belum ada data mahasiswa.</td>
              </tr>
              <tr v-for="item in mahasiswa" :key="item.id">
                <td class="font-bold">#{{ item.id }}</td>
                <td>
                  <div class="user-name">{{ item.nama }}</div>
                </td>
                <td>
                  <span :class="['tag', item.jenis_kelamin === 'Laki-laki' ? 'tag-male' : 'tag-female']">
                    {{ item.jenis_kelamin }}
                  </span>
                </td>
                <td>{{ item.tempat_lhr }}, {{ item.tanggal_lhr }}</td>
                <td class="text-muted">{{ item.alamat || '-' }}</td>
                <td class="text-center actions">
                  <button @click="editData(item)" class="btn-icon btn-edit" title="Edit">
                    Edit
                  </button>
                  <button @click="deleteData(item.id)" class="btn-icon btn-delete" title="Hapus">
                    Hapus
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const API_URL = '/api/mahasiswa';
const mahasiswa = ref([]);
const isEdit = ref(false);
const editId = ref(null);

const form = ref({
  nama: '',
  jenis_kelamin: '',
  tempat_lhr: '',
  tanggal_lhr: '',
  alamat: ''
});

const fetchMahasiswa = async () => {
  try {
    const res = await axios.get(API_URL);
    mahasiswa.value = res.data.data;
  } catch (error) {
    console.error('Gagal mengambil data:', error);
  }
};

const saveData = async () => {
  try {
    if (isEdit.value) {
      await axios.put(`${API_URL}/${editId.value}`, form.value);
    } else {
      await axios.post(API_URL, form.value);
    }
    resetForm();
    fetchMahasiswa();
  } catch (error) {
    console.error('Gagal menyimpan data:', error);
  }
};

const editData = (item) => {
  isEdit.value = true;
  editId.value = item.id;
  form.value = { ...item };
};

const deleteData = async (id) => {
  if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
    try {
      await axios.delete(`${API_URL}/${id}`);
      fetchMahasiswa();
    } catch (error) {
      console.error('Gagal menghapus data:', error);
    }
  }
};

const resetForm = () => {
  isEdit.value = false;
  editId.value = null;
  form.value = { nama: '', jenis_kelamin: '', tempat_lhr: '', tanggal_lhr: '', alamat: '' };
};

onMounted(fetchMahasiswa);
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

* {
  box-sizing: border-box;
  font-family: 'Plus Jakarta Sans', sans-serif;
}

.app-container {
  min-height: 100vh;
  background-color: #f8fafc;
  padding: 32px 16px;
  color: #1e293b;
}

.header {
  max-width: 900px;
  margin: 0 auto 28px auto;
  text-align: center;
}

.header h1 {
  font-size: 2rem;
  font-weight: 700;
  color: #0f172a;
  margin: 0;
}

.header h1 span {
  color: #4f46e5;
}

.header p {
  color: #64748b;
  margin-top: 6px;
  font-size: 0.95rem;
}

.main-content {
  max-width: 900px;
  margin: 0 auto;
  display: flex;
  flex-direction: column;
  gap: 24px;
}

.card {
  background: #ffffff;
  border-radius: 12px;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
  border: 1px solid #e2e8f0;
  overflow: hidden;
}

.card-header {
  padding: 16px 24px;
  border-bottom: 1px solid #f1f5f9;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.card-header h3 {
  margin: 0;
  font-size: 1.05rem;
  font-weight: 600;
  color: #334155;
}

.badge {
  background: #e0e7ff;
  color: #3730a3;
  padding: 4px 12px;
  border-radius: 9999px;
  font-size: 0.8rem;
  font-weight: 600;
}

.form-grid {
  padding: 24px;
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 16px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.form-group.full-width {
  grid-column: span 2;
}

.form-group label {
  font-size: 0.85rem;
  font-weight: 600;
  color: #475569;
}

input, select, textarea {
  padding: 10px 14px;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  font-size: 0.9rem;
  outline: none;
  transition: all 0.2s ease;
  background-color: #fff;
}

input:focus, select:focus, textarea:focus {
  border-color: #6366f1;
  box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
}

.form-actions {
  display: flex;
  gap: 10px;
  margin-top: 8px;
}

.btn {
  padding: 10px 20px;
  border-radius: 8px;
  font-weight: 600;
  font-size: 0.9rem;
  cursor: pointer;
  border: none;
  transition: background-color 0.2s ease;
}

.btn-primary {
  background-color: #4f46e5;
  color: white;
}

.btn-primary:hover {
  background-color: #4338ca;
}

.btn-secondary {
  background-color: #f1f5f9;
  color: #475569;
}

.btn-secondary:hover {
  background-color: #e2e8f0;
}

.table-responsive {
  overflow-x: auto;
}

table {
  width: 100%;
  border-collapse: collapse;
  text-align: left;
  font-size: 0.9rem;
}

th {
  background-color: #f8fafc;
  color: #64748b;
  font-weight: 600;
  padding: 12px 20px;
  border-bottom: 1px solid #e2e8f0;
}

td {
  padding: 14px 20px;
  border-bottom: 1px solid #f1f5f9;
  color: #334155;
}

tr:hover {
  background-color: #f8fafc;
}

.font-bold {
  font-weight: 600;
  color: #64748b;
}

.user-name {
  font-weight: 600;
  color: #0f172a;
}

.tag {
  display: inline-block;
  padding: 4px 10px;
  border-radius: 6px;
  font-size: 0.75rem;
  font-weight: 600;
}

.tag-male {
  background-color: #e0f2fe;
  color: #0369a1;
}

.tag-female {
  background-color: #fce7f3;
  color: #be185d;
}

.text-muted {
  color: #94a3b8;
}

.text-center {
  text-align: center;
}

.actions {
  white-space: nowrap;
}

.btn-icon {
  border: none;
  background: transparent;
  padding: 6px 12px;
  border-radius: 6px;
  cursor: pointer;
  font-size: 0.8rem;
  font-weight: 600;
  transition: background-color 0.2s;
}

.btn-edit {
  color: #d97706;
  background-color: #fef3c7;
  margin-right: 6px;
}

.btn-edit:hover {
  background-color: #fde68a;
}

.btn-delete {
  color: #dc2626;
  background-color: #fee2e2;
}

.btn-delete:hover {
  background-color: #fecaca;
}

.empty-state {
  text-align: center;
  color: #94a3b8;
  padding: 32px;
}

@media (max-width: 640px) {
  .form-grid {
    grid-template-columns: 1fr;
  }
  .form-group.full-width {
    grid-column: span 1;
  }
}
</style>
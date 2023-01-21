<template>
  <main class="create-post">
    <div class="container">
      <h1>Create Posts!</h1>
      <!-- success message -->
      <!-- jika success nya true -->
      <div class="success-msg" v-if="success">
        <i class="fa fa-check"></i>
        Post created successfully
      </div>
      <!-- Contact Form -->
      <div class="contact-form">
        <form @submit.prevent="submit()">
          <!-- Title -->
          <label for="title"><span>Title</span></label>
          <input v-model="fields.title" type="text" id="title" />
          <span v-if="errors.title" class="error">{{ errors.title[0] }}</span>
          <br />

          <!-- Image -->
          <label for="image"><span>Image</span></label>
          <!-- VUE JS tidak mendukung input type file, jadi dia butuh @input -->
          <!-- @input tidak boleh memanggil method yang menggunakan ()  -->
          <!-- :key aku gunakan agar aku bisa reset input type file ketika aku berhasil menyimpan postingan -->
          <input @change="grabFile" :key="file" type="file" id="image" />
          <span v-if="errors.file" class="error">{{ errors.file[0] }}</span>
          <div class="preview pratinjau_gambar">
            <!-- panggil property url -->
            <!-- : digunakan untuk memanggil property di sebuah attribute -->
            <img v-if="pratinjau_gambar" :src="url" alt="Post Image" />
          </div>
          <br />

          <!-- Drop down kategori -->
          <label for="categories"><span>Choose a category:</span></label>
          <!-- nilai dari :value akan masuk ke fields.category_id, misalnya 1 -->
          <select v-model="fields.category_id" id="categories">
            <!-- matikan select option -->
            <option disabled value="">Select option</option>
            <!-- looping kategori -->
            <option v-for="category in categories" :value="category.id" :key="category.id">{{ category.name }}</option>
          </select>
          <span v-if="errors.category_id" class="error">{{ errors.category_id[0] }}</span>
          <br />

          <!-- Body-->
          <label for="body"><span>Body</span></label>
          <textarea id="body" v-model="fields.body"></textarea>
          <span v-if="errors.body" class="error">{{ errors.body[0] }}</span>
          <!-- Button -->
          <input class="add-post-btn" type="submit" value="Submit" />
        </form>
      </div>
    </div>
  </main>
</template>

<script>
export default {
  // buat property
  data() {
    return {
      // notifikasi "Postingan berhasil disimpan"
      success: false,
      // untuk menyimpan value dari input2x
      fields: {
        category_id: '',
        file: ''
      },
      // untuk menyimpan setiap error validasi dari input2x
      errors: {},
      // menangani pratinjau gambar
      url: '',
      pratinjau_gambar: false,
      // agar input type file bisa di reset  
      file: 0,
      // menangani fitur pilih kategori
      categories: [],
    }
  },

  // buat methods2x
  methods: {
    // Aku membuat ini karena vue js tidak mendukung input type file
    // e berarti event
    grabFile(e) {
      // console.log(e);
      // buka e.target.files[0] agar ada info file
      const file = e.target.files[0];
      this.fields.file = file;
      // fitur pratinjau gambar
      this.pratinjau_gambar = true;
      this.url = URL.createObjectURL(file);
      URL.revokeObjectURL(file);
    },

    // fitur simpan postingan
    submit() {
      // console.log(this.fields);
      axios
        .post("/api/posts", this.fields, {
          // untuk mengirimkan file ke PostController
          headers: { "content-type": "multipart/form-data" },
        })
        .then(() => {
          // kosongkan input
          this.fields = {};
          // ksoongkan input type file berserta pratinjau gambarnya
          this.url = null;
          this.pratinjau_gambar = false;
          // agar input type file bisa di reset
          this.file++;
          // reset select category
          this.fields.category_id = "";
          // tampilkan notifikasi
          this.success = true;
          // reset validasi error
          this.errors = {};
          // hilangkan notifikasi "Post berhasil disimpan setelah 2 detik 500 milsecond"
          setTimeout(() => {
            this.success = false;
          }, 2500);
        })
        .catch((error) => {
          // tampilkan validasi error
          this.errors = error.response.data.errors;
          this.success = false;
          // fitur melakukan logout otomatis terhadap user jika SESSION_LIFETIME sudah habis
          // status === 401 berarti UNAUTHORIZED yang berarti user belum login
          // Jadi aku mengatur CONSTANTA SESSSION_LIFETIME di .env laravel menjadi 1 menit, berarti jika user login lalu user tidak melakukan apa apa dalam waktu 1 menit lalu aku melakukan reload maka harusnya session nya habis lalu user harus kembali ke halaman login
          if (error.response.status === 401) {
            // panggil update-sidebar di router-view milik parent nya yaitu App.vue, jadi property loggedIn punya parent adalah true karena kita panggil $emit maka dia akan jadi false
            this.$emit('updateSidebar');
            localStorage.removeItem('authenticated');
            this.$router.push({name: 'Login'});
          };
        });
    },
  },

  // method yang otomatis dijalankan
  mounted() {
    // ambil semua kategori untuk membuat fitur pilih kategori
    axios.get('/api/categories')
        .then((response) => {
          this.categories = response.data;
        })
        .catch((error) => {
          // fitur melakukan logout otomatis terhadap user jika SESSION_LIFETIME sudah habis
          // status === 401 berarti UNAUTHORIZED yang berarti user belum login
          // Jadi aku mengatur CONSTANTA SESSSION_LIFETIME di .env laravel menjadi 1 menit, berarti jika user login lalu user tidak melakukan apa apa dalam waktu 1 menit lalu aku melakukan reload maka harusnya session nya habis lalu user harus kembali ke halaman login
          if (error.response.status === 401) {
            // panggil update-sidebar di router-view milik parent nya yaitu App.vue, jadi property loggedIn punya parent adalah true karena kita panggil $emit maka dia akan jadi false
            this.$emit('updateSidebar');
            localStorage.removeItem('authenticated');
            this.$router.push({name: 'Login'});
          };
        });
  },
}
</script>

<style scoped>
.create-post {
  background-color: #fff;
  padding: 0 3vw;
}
.container input,
textarea,
select {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
  margin-top: 6px;
  margin-bottom: 20px;
  font-size: 16px;
}
h1 {
  text-align: center;
  padding: 60px 0 50px 0;
}

.add-post-btn {
  background-color: black;
  color: white;
  border: none;
  cursor: pointer;
  transition: 0.3s ease;
}

.add-post-btn:hover {
  transform: translateY(-4px);
}

.preview img {
  max-width: 100%;
  max-height: 120px;
}
</style>
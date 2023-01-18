<template>
  <div class="categories-list">
    <h1>Categories List</h1>
	
	<div class="success-msg" v-if="berhasil_perbarui_kategori">
      <i class="fa fa-check"></i>
      Kategori berhasil diperbarui
    </div>
	
    <!-- success message -->
    <div class="success-msg" v-if="success">
      <i class="fa fa-check"></i>
      Deleted successfully
    </div>

    <!-- category berisi tiap object data -->
    <!-- index dimulai dari 0 -->
    <!-- :key harus ditulis karena agar unique -->
    <div v-for="(category, index) in categories" :key="category.id" class="item">
      <!-- remember that index start from 0 -->
      <span>{{ index + 1 }}</span>
      <p>{{ category.name }}</p>
      <div>
        <router-link :to="{name: 'EditCategories', params: {id: category.id}}" class="edit-link">Edit</router-link>
      </div>

      <input type="button" value="Delete" class="delete-btn" @click="destroy(category.id)">
    </div>
    <div class="index-categories">
      <router-link :to="{ name: 'CreateCategories' }"
        >Create Categories<span>&#8594;</span></router-link
      >
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      // jadi nanti categories akan berisi
      // [
        // {id: 3, name: 'Baru'}
        // {id: 3, name: 'Baru'}
      // ]
      categories: [],
      // notifikasi kategori berhasil diperbarui
	    berhasil_perbarui_kategori: false,
      // notifikasi kategori berhasil dihapus
      success: false
    }
  },

  // mounted akan dijalankan secara otomatis ketika aplikasi dimulai
  mounted() {
    this.fetchCategories();
  },

  methods: {
    error_status_401() {
      this.$emit('updateSidebar');
      localStorage.removeItem('authenticated');
      this.$router.push({name: 'Login'});
    },

    destroy(id) {
      axios.delete('/api/categories/' + id)
      .then((response) => {
        // karena datanya sudah hilang satu makanya kita panggil lagi datanya di table agar aplikasi seolah olah melakukan refresh 
        this.fetchCategories();

        // console.log(response);
        this.success = true;
        setInterval(() => {
            this.success = false;
        }, 5000);
      })
      .catch((error) => {
        // fitur melakukan logout otomatis terhadap user jika SESSION_LIFETIME sudah habis
        // status === 401 berarti UNAUTHORIZED yang berarti user belum login
        // Jadi aku mengatur CONSTANTA SESSSION_LIFETIME di .env laravel menjadi 1 menit, berarti jika user login lalu user tidak melakukan apa apa dalam waktu 1 menit lalu aku melakukan reload maka harusnya session nya habis lalu user harus kembali ke halaman login
        if (error.response.status === 401) {
          // panggil update-sidebar di router-view milik parent nya yaitu App.vue, jadi property loggedIn punya parent adalah true karena kita panggil $emit maka dia akan jadi false
          this.error_status_401();
        };
      });
    },

    fetchCategories() {
      axios.get('/api/categories')
        .then((response) => {
          // console.log(response);
          this.categories = response.data;
          // Fitur menampilkan notifikasi "Kategori berhasil di update"
          if (localStorage.getItem("berhasil_perbarui_kategori")) {
            this.berhasil_perbarui_kategori = true;

            setInterval(() => {
                this.berhasil_perbarui_kategori = false;
                localStorage.removeItem('berhasil_perbarui_kategori');
            }, 5000);
          };
        })
        .catch((error) => {
          // fitur melakukan logout otomatis terhadap user jika SESSION_LIFETIME sudah habis
          // status === 401 berarti UNAUTHORIZED yang berarti user belum login
          // Jadi aku mengatur CONSTANTA SESSSION_LIFETIME di .env laravel menjadi 1 menit, berarti jika user login lalu user tidak melakukan apa apa dalam waktu 1 menit lalu aku melakukan reload maka harusnya session nya habis lalu user harus kembali ke halaman login
          if (error.response.status === 401) {
            // panggil update-sidebar di router-view milik parent nya yaitu App.vue, jadi property loggedIn punya parent adalah true karena kita panggil $emit maka dia akan jadi false
            this.error_status_401();
          };
        });
    },
  }
};
</script>

<style scoped>
.categories-list {
  min-height: 100vh;
  background: #fff;
}

.categories-list h1 {
  font-weight: 300;
  padding: 50px 0 30px 0;
  text-align: center;
}

.categories-list .item {
  display: flex;
  justify-content: right;
  align-items: center;
  max-width: 300px;
  margin: 0 auto !important;
}

.categories-list .item p {
  font-size: 16px;
}

.categories-list .item p,
.categories-list .item div,
.categories-list .item {
  margin: 15px 8px;
}

.categories ul li {
  list-style: none;
  background-color: #494949;
  margin: 20px 5px;

  border-radius: 15px;
}

.categories ul {
  display: flex;
  justify-content: center;
}

.categories a {
  color: white;
  padding: 10px 20px;
  display: inline-block;
}

.create-categories a,
.index-categories a {
  all: revert;
  margin: 20px 0;
  display: inline-block;
  text-decoration: none;
}

.create-categories a span,
.index-categories a span {
  font-size: 22px;
}

.index-categories {
  text-align: center;
}
</style>
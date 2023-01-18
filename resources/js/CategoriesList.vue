<template>
  <div class="categories-list">
    <h1>Categories List</h1>
    <!-- success message -->
    <div class="success-msg" v-if="success">
      <i class="fa fa-check"></i>
      Deleted successfully
    </div>
    <div
      class="item"
      v-for="(category, index) in categories"
      :key="category.id"
    >
      <span>{{ index + 1 }}</span>
      <p>{{ category.name }}</p>
      <div>
        <router-link :to="{name: 'EditCategories', params: {id: category.id}}">Edit</router-link>
      </div>

      <input
        type="button"
        value="Delete"
        @click="destroy(category.id)"
        class="delete-btn"
      />
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
          categories: []
        }
    },
    // mounted = pasang
    // mounted adalah method yang dijalankan otomatis
    mounted() {
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
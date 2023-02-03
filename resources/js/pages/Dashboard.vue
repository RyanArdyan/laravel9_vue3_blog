<template>
  <div id="backend-view">
    <div class="logout"><a href="#" @click="logout()">Log out</a></div>
    <h1 class="heading">Dashboard</h1>
    <span>Hi {{ name }}!</span>
    <div class="links">
      <ul>
        <li>
          <router-link :to="{name: 'CreatePosts'}">Create Posts</router-link>
        </li>
        <li>
          <router-link :to="{name: 'DashboardPostsList'}">Posts List</router-link>
        </li>
        <li>
          <router-link :to="{name:  'CreateCategories'}">Create Categories</router-link>
        </li>
        <li>
          <router-link :to="{name: 'CategoriesList'}">Categories List</router-link>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
  export default {
    // make a property
    data() {
      return {
        // two way binding / pengikatan dua arah
        name: ''
      }
    },
    // jika compoennt sudah dipasang maka jalankan fungsi berikut
    mounted() {
      axios.get('/api/user')
        .then((response) => {
          // console.log(response);
          this.name = response.data.name;
        })
        .catch((error) => {
          // fitur melakukan logout otomatis terhadap user jika SESSION_LIFETIME sudah habis
          // status === 401 berarti UNAUTHORIZED yang berarti user belum login
          // Jadi aku mengatur CONSTANTA SESSSION_LIFETIME di .env laravel menjadi 1 menit, berarti jika user login lalu user tidak melakukan apa apa dalam waktu 1 menit lalu aku melakukan reload maka harusnya session nya habis lalu user harus kembali ke halaman login
          if (error.response.status === 401) {
            // panggil update-sidebar di router-view milik parent nya yaitu App.vue, jadi property loggedIn punya parent adalah true karena kita panggil $emit maka dia akan jadi false
            this.$emit('updateSidebar');
            localStorage.removeItem('authenticated');
            localStorage.removeItem('apakah_admin');
            this.$router.push({name: 'Login'});
          };
        });
    },
    methods: {
      logout() {
        axios.post('/api/logout')
          .then(() => {
          localStorage.removeItem('authenticated');
          localStorage.removeItem('apakah_admin');
          this.$emit('userLogout');
          this.$router.push({name: 'Home'});
          // jika ada error maka tangkap errornya
        }).catch((error) => {
          console.log(error);
        });    
      }
    },
  }
</script>

<style scoped>
/* dashboard */
#backend-view {
  text-align: center;
  background-color: #f3f4f6;
  height: 100vh;
  padding-top: 15vh;
}

.logout {
  position: absolute;
  top: 30px;
  right: 40px;
}
.heading {
  margin-bottom: 5px;
}
.links {
  margin-top: 30px;
  margin-left: auto;
  margin-right: auto;
  background: #ffffff;
  max-width: 500px;
  padding: 15px;
  border-radius: 15px;
}
.links ul {
  list-style: none;
}
.links a {
  all: revert;
  font-size: 26px;
  display: inline-block;
  margin: 10px 0;
}
</style>

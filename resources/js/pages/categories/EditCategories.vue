<template>
  <div id="create-categories">
    <div id="contact-us">
      <h1>Edit Category!</h1>
      <div class="contact-form">
        <form @submit.prevent="submit">
          <label for="name"><span>Name</span></label>
          <input type="text" id="name" v-model="field.name" />
          <span v-if="errors.name" class="error">{{ errors.name[0] }}</span>

          <input type="submit" value="Submit" />
        </form>
      </div>
      <div class="create-categories">
        <router-link :to="{ name: 'CategoriesList' }"
          >Categories List <span>&#8594;</span></router-link
        >
      </div>
    </div>
  </div>
</template>

<script>
export default {
  // aku ada membuat parameter id di name EditCategories
  props: ["id"],
  data() {
    return {
      field: {},
      errors: {}
    };
  },

  methods: {
    submit() {
      axios
        .put("/api/categories/" + this.id, this.field)
        .then(() => {
          localStorage.setItem('berhasil_perbarui_kategori', 'true');
          this.$router.push({name: 'CategoriesList'});
        })
        .catch((error) => {
          this.errors = error.response.data.errors;

          // fitur melakukan logout otomatis terhadap user jika SESSION_LIFETIME sudah habis
          // status === 401 berarti UNAUTHORIZED yang berarti user belum login
          // Jadi aku mengatur CONSTANTA SESSSION_LIFETIME di .env laravel menjadi 1 menit, berarti jika user login lalu user tidak melakukan apa apa dalam waktu 1 menit lalu aku melakukan reload maka harusnya session nya habis lalu user harus kembali ke halaman login
          if (error.response.status === 401) {
            this.error_status_404();
          };
        });
    },

    error_status_404() {
      // panggil update-sidebar di router-view milik parent nya yaitu App.vue, jadi property loggedIn punya parent adalah true karena kita panggil $emit maka dia akan jadi false
      this.$emit('updateSidebar');
      localStorage.removeItem('authenticated');
      this.$router.push({name: 'Login'});
    },
  },

  mounted() {
    axios
      // ambil props id 
      .get("/api/categories/" + this.id)
      .then((response) => {
        console.log(response.data);
        this.field = response.data;
      })
      .catch((error) => {
        console.log(error);

        // fitur melakukan logout otomatis terhadap user jika SESSION_LIFETIME sudah habis
        // status === 401 berarti UNAUTHORIZED yang berarti user belum login
        // Jadi aku mengatur CONSTANTA SESSSION_LIFETIME di .env laravel menjadi 1 menit, berarti jika user login lalu user tidak melakukan apa apa dalam waktu 1 menit lalu aku melakukan reload maka harusnya session nya habis lalu user harus kembali ke halaman login
        if (error.response.status === 401) {
          this.error_status_404();
        };
      });
  },
};
</script>

<style scoped>
#create-categories {
  background-color: #f3f4f6;
  height: 90vh;
  padding: 50px;
}
</style>

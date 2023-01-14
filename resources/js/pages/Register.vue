<template>
  <div id="backend-view">
    <!-- ketika di kirim maka cegah reload dan panggil method submit() -->
    <form @submit.prevent="submit()">
      <h3>Sign Up Here</h3>
      <label for="name">Name</label>
      <input type="text" id="name" v-model="fields.name" />
      <!-- jika ada errors.name maka -->
      <!-- panggil property name yang berisi array mame index ke 0 -->
      <span v-if="errors.name" class="error">{{ errors.name[0] }}</span>

      <label for="email">Email</label>
      <!-- email akan masuk ke object milik property fields -->
      <input type="text" id="email" v-model="fields.email" />
      <span v-if="errors.email" class="error">{{ errors.email[0] }}</span>

      <label for="password">Password</label>
      <input :type="tipe_input_password" id="password" v-model="fields.password" />
      <span v-if="errors.password" class="error">{{ errors.password[0] }}</span>

      <label for="password_confirmation">Confirm password</label>
      <input :type="tipe_input_password" id="password_confirmation" v-model="fields.password_confirmation"
      />
      <span v-if="errors.password_confirmation" class="error">{{ errors.password_confirmation[0] }}</span>

      <!-- fitur tampilkan password dan sembunyikan password -->
      <p @click="ubah_tipe_input_password()" class="pointer_cursor" style="margin-top: 0;">{{ ubah_keterangan }}</p>

      <button type="submit">Sign Up</button>
      <span>Have an account?<a href=""> Log in</a></span>
    </form>
  </div>
</template>

<script>
  export default {
    // aaa, method data berfungsi untuk membuat propery-property
    data() {
      return {
        // fields berisi menyimpan input
        fields: {},
        // errors akan menyimpan errors
        // errors akan berisi
        // email : ['The email field is required.']
        // name : ['The name field is required.']
        // password : ['The password field is required.']
        errors: {},
        // fitur tampilkan dan sembunyikan password
        tipe_input_password: "password",
        ubah_keterangan: "Lihat password",
        // akhir fitur tampilkan dan sembunyikan password
      }
    },
    // methods berfungsi membuat method-methods
    methods: {
      submit() {
        // aku memanggil routes/api http://127.0.0.1:8000/api/register milik laravel
        // aku mengirimkan this.fields
        // this.fields akan berisi value setiap input berarti ada name, email, password dan konfirmasi password
        axios.post('/api/register', this.fields).then(() => {
          this.$router.push({name: 'Dashboard'});
          // jika ada error maka tangkap errornya
        }).catch((error) => {
          // console.log(error);
          // panggil property errors lalu diisi dengan error.response.data.errors
          // error.response.data.errors berisi:
          // email : ['The email field is required.']
          // name : ['The name field is required.']
          // password : ['The password field is required.']
          this.errors = error.response.data.errors;
          // console.log(error.response.data.errors);
        });    
      },

      // fitur tampilkan password dan sembunyikan password
      ubah_tipe_input_password() {
        // bawaannya kan "password" jadi apakah "password" === "password" berarti true, karena true ubah property tipe_input_password menjadi text
          if (this.tipe_input_password === "password") {
              this.tipe_input_password = "text";
              this.ubah_keterangan = "Sembunyikan Password";
          // apakah "text" === "password"? jawabannya adalah false
          } else {
              // berarti sekarang tipe_input_password pada awalnya adalah text jadi kita ubah ke password
              this.tipe_input_password = "password";
              this.ubah_keterangan = "Tampilkan Password";
          };
      },
    }
  };
</script>

<style scoped>
#backend-view {
  height: 100vh;
  background-color: #f3f4f6;
  display: grid;
  align-items: center;
}
form {
  width: 400px;
  background-color: #ffffff;
  margin: 0 auto;
  border-radius: 10px;
  border: 2px solid rgba(255, 255, 255, 0.1);
  padding: 50px 35px;
}
form * {
  letter-spacing: 0.5px;
  outline: none;
}

label {
  display: block;
  margin-top: 20px;
  font-size: 16px;
  font-weight: 500;
}
input {
  display: block;
  height: 50px;
  width: 100%;
  border-radius: 3px;
  padding: 0 10px;
  margin-top: 8px;
  font-size: 16px;
  font-weight: 300;
}

button {
  margin-top: 50px;
  width: 100%;
  background-color: rgba(0, 46, 173, 0.7);
  color: #ffffff;
  padding: 15px 0;
  font-size: 18px;
  font-weight: 600;
  border-radius: 5px;
  cursor: pointer;
}
form span {
  display: block;
  margin-top: 20px;
}
a {
  color: rgba(0, 46, 173, 0.8);
}

.loader {
  text-align: center;
  margin-bottom: 15px;
}
</style>

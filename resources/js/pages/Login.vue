<template>
  <div id="backend-view">
    <form @submit.prevent="submit">
      <h3>Login Here</h3>
      <label for="email">Email</label>
      <input type="text" id="email" v-model="fields.email" />
      <span v-if="errors.email" class="error">{{ errors.email[0] }}</span>

      <label for="password">Password</label>
      <input :type="tipe_input_password" id="password" v-model="fields.password" />
      <span v-if="errors.password" class="error">{{ errors.password[0] }}</span>

      <!-- fitur tampilkan password dan sembunyikan password -->
      <p @click="ubah_tipe_input_password()" class="pointer_cursor" style="margin-top: 0;">{{ ubah_keterangan }}</p>

      <button type="submit">Log In</button>
      <span>Don't have an account? <a href="">Sign up</a></span>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      fields: {},
      errors: {},
      // fitur tampilkan dan sembunyikan password
      tipe_input_password: "password",
      ubah_keterangan: "Lihat password",
      // akhir fitur tampilkan dan sembunyikan password
    };
  },
  methods: {
    submit() {
      axios
        .post("/api/login", this.fields)
        .then(() => {
          this.$router.push({ name: "Dashboard" });
          // fitur menghilangkan menu login dan registrasi ketika user sudah login
          // jadi jika user berhasil login maka localStorage browser akan membuat key authenticated yang bervalue true
          // localStorage adalah kode API Storage javascript vanilla 
          localStorage.setItem('authenticated', 'true');
          // Vue $emit adalah fungsi yang memungkinkan kita mengirim peristiwa khusus dari komponen anak ke induknya.
		  // kirim emit updateSidebar, lalu router-view milik parent yaitu App.vue akan menangkapnya menggunakan @update-sidebar
          this.$emit('updateSidebar');
        })
        .catch((error) => {
          this.errors = error.response.data.errors;
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
  },
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
  margin-top: 35px;
}
a {
  color: rgba(0, 46, 173, 0.8);
}
</style>

<template>
    <main class="create-post">
        <div class="container">
            <h1>Edit Post!</h1>
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
                    <!-- accept="image/*" berfungsi agar aku hanya bisa memilih gambar -->
                    <input @change="grabFile" :key="file" accept="image/*" type="file" id="image" />
                    <span v-if="errors.file" class="error">{{ errors.file[0] }}</span>
                    <div class="preview pratinjau_gambar">
                        <!-- panggil property url -->
                        <!-- : digunakan untuk memanggil property di sebuah attribute -->
                        <img :src="url" alt="Post Image" />
                    </div>
                    <br />

                    <!-- Drop down kategori -->
                    <label for="categories"><span>Choose a category:</span></label>
                    <!-- nilai dari :value akan masuk ke fields.category_id, misalnya 1 -->
                    <select v-model="fields.category_id" id="categories">
                        <!-- matikan select option -->
                        <option disabled value="">Select option</option>
                        <!-- looping kategori -->
                        <!-- nilai dari option sebenarnya adalah categori.id bukan categori.name -->
                        <!-- semua looping butuh :key agar tidak lupa -->
                        <option 
                            :value="category.id" 
                            v-for="category in categories" 
                            :key="category.id">{{
                            category.name
                        }}</option>
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
    // tangkap props yang berisi slug yang dikirimkan oleh router/index.js, untuk mengirimkan slug ketika aku harus mengambil detail post
    props: ['slug'],

    // buat property
    data() {
        return {
            // notifikasi "Postingan berhasil disimpan"
            success: false,
            // untuk menyimpan value dari input2x
            // fiels akan berisi name: 'Budi', category_id: 1
            fields: {
                category_id: '',
                file: '',
            },
            // untuk menyimpan setiap error validasi dari input2x
            errors: {},
            // menangani pratinjau gambar
            url: '',
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
            this.url = URL.createObjectURL(file);
            URL.revokeObjectURL(file);
        },

        // fitur perbarui postingan
        submit() {
            // console.log(this.fields);
            // axios.put tidak akan bekerja jika aku mengirimkan file gambar jadi aku butuh kode javascript vanilla
            const fd = new FormData();
            fd.append('_method', 'PUT');
            // contoh new FormData({
                //      'title' => 'Hello World',
                // })
            // artinya fd tambah key title yang berisi property fields.title yang di ikat menggunakan v-model
            fd.append('title', this.fields.title);
            fd.append('category_id', this.fields.category_id);
            // jika ada this.fields.file atau jika user memasukkan gambar
            if (this.fields.file) {
                fd.append('file', this.fields.file);
            };
            fd.append('body', this.fields.body);

            // kirimkan slug, new FormData, dan multipart/form-data
            axios.post(`/api/posts/${this.slug}`, fd, {
                headers: {
                    'content-type': 'multipart/form-data'
                },
            })
                .then((res) => {
                    // panggil emit show-edit-success milik App.vue yang disimpan dalam <router-link>
                    this.$emit("showEditSuccess");
                    this.$router.push({name: 'DashboardPostsList'});
                })
                .catch((error) => {
                    // masukkan setiap error validasi ke dalam property errors
                    this.errors = error.response.data.errors
                    // jika session habis maka lakukan logout pada userz
                    if (error.response.status === 401) {
                        // panggil update-sidebar di router-view milik parent nya yaitu App.vue, jadi property loggedIn punya parent adalah true karena kita panggil $emit maka dia akan jadi false
                        this.$emit('updateSidebar');
                        localStorage.removeItem('authenticated');
                        this.$router.push({ name: 'Login' });
                    // lindungi edit post dari nonauthor
                    } else if (error.response.status === 403) {
                        this.$router.push({name: 'Home'})
                    };
                })
        },



        // detail post  
        read_detail_post() {
            // kirimkan slug yg didapatkan dari props
            axios.get('/api/posts/' + this.slug)
                .then((response) => {
                    // console.log(response.data.data);
                    this.fields = response.data.data;
                    // console.log(this.post);
                    // untuk menampilkan image
                    this.url = "/" + response.data.data.image;
                })
                .catch((error) => {
                    // fitur melakukan logout otomatis terhadap user jika SESSION_LIFETIME sudah habis
                    // status === 401 berarti UNAUTHORIZED yang berarti user belum login
                    // Jadi aku mengatur CONSTANTA SESSSION_LIFETIME di .env laravel menjadi 1 menit, berarti jika user login lalu user tidak melakukan apa apa dalam waktu 1 menit lalu aku melakukan reload maka harusnya session nya habis lalu user harus kembali ke halaman login
                    if (error.response.status === 401) {
                        // panggil update-sidebar di router-view milik parent nya yaitu App.vue, jadi property loggedIn punya parent adalah true karena kita panggil $emit maka dia akan jadi false
                        this.$emit('updateSidebar');
                        localStorage.removeItem('authenticated');
                        this.$router.push({ name: 'Login' });
                    // jika detail post gagal diambil karena aku reload halaman
                    } else if (error.response.status === 404) {
                        this.$router.push({ name: 'DashboardPostsList' });
                    // lindungi edit post dari bukan penulis postingan
                    } else if (error.response.status === 403) {
                        this.$router.push({name: 'Home'})
                    };

                });
        },

        // ambil semua kategori untuk membuat fitur pilih kategori
        read_semua_kategori() {
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
                    this.$router.push({ name: 'Login' });
                };
            });
        }
    },

    // method yang otomatis dijalankan
    mounted() {
        // panggil method read_detail_post yang berada diluar
        this.read_detail_post(),
        this.read_semua_kategori()
    },
}
</script>
  
<style scoped>
input[type=file]::file-selector-button {
    margin-right: 20px;
    border: none;
    background: #084cdf;
    padding: 10px 20px;
    border-radius: 10px;
    color: #fff;
    cursor: pointer;
    transition: background .2s ease-in-out;
}

input[type=file]::file-selector-button:hover {
    background: #0d45a5;
}

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

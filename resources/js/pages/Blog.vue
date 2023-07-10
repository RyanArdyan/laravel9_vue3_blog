<template>
    <h2 class="header-title">All Blog Posts</h2>
    <div class="searchbar">
        <form>
            <!-- fitur filter postingan berdasarkan pencarian -->
            <!-- panggil property title dan watch title, jadi setiap perubahan v-model="title" maka panggil watch title -->
            <input v-model="title" type="text" placeholder="Search..." />
        </form>
    </div>
    <div class="categories">
        <ul>
            <!-- fitur filter postingan berdasarkan kategori -->
            <li v-for="category in categories" :key="category.id">
                <a @click="filterByCategory(category.name)" href="javascript:void(0)">{{ category.name }}</a>
            </li>
        </ul>
    </div>
    <section class="cards-blog latest-blog">
        <div v-for="(post, index) in posts" :key="post.id" class="card-blog-content">
            <img :src="post.image" alt="" />
            <p>
                {{ post.created_at  }}
                <span style="float: right">{{ post.user }}</span>
            </p>
            <h4 style="font-weight: bolder">
                <router-link :to="{
                    name: 'SingleBlog', 
                    params: {slug: post.slug},
                }">{{ post.title }}</router-link>
            </h4>
        </div> 
    </section>

    <!-- jika panjang postingan tidak ada karena kosong maka -->
    <h3 v-if="!posts.length">Tidak ditemukan.</h3>
</template>

<script>
export default {
    emits: ['updateSidebar'],
    data() {
        return {
            posts: [],
            categories: [],
            // fitur filter postingan berdasarkan pencarian
            // string karena value pencarian pasti dalam bentuk string
            title: ''
        }
    },

    // awasi perubahan
    watch: {
        // fitur filter postingan berdasarkan pencarian
        // kapanpun v-model="title" berubah, fungsi ini akan berjalan
        title() {
            axios.get('/api/posts', {
                // kirimkan input name search berisi this.title misalnya postingan 1
                params: {
                    search: this.title,
                },
            })
            .then((response) => {
                this.posts = response.data.data;
            })
            .catch((error) => {
                console.log(error);
                // fitur melakukan logout otomatis terhadap user jika SESSION_LIFETIME sudah habis
                // status === 401 berarti UNAUTHORIZED yang berarti user belum login
                // Jadi aku mengatur CONSTANTA SESSSION_LIFETIME di .env laravel menjadi 1 menit, berarti jika user login lalu user tidak melakukan apa apa dalam waktu 1 menit lalu aku melakukan reload maka harusnya session nya habis lalu user harus kembali ke halaman login
                if (error.response.status === 401) {
                    // panggil update-sidebar di router-view milik parent nya yaitu App.vue, jadi property loggedIn punya parent adalah true karena kita panggil $emit maka dia akan jadi false
                    this.$emit('updateSidebar');
                    localStorage.removeItem('authenticated');
                    // fitur jika user adalah admon maka tampilkan dashboard
                    localStorage.removeItem('apakah_admin');
                    this.$router.push({name: 'Login'});
                };
            });
        }
    },

    methods: {
        read_beberapa_posts() {
            axios.get('/api/posts')
            .then((response) => {
                this.posts = response.data.data;
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
                    this.$router.push({name: 'Login'});
                };
            });
        },
        // filter postingan berdasarkan kategori
        filterByCategory(name) {
            // {params} artinya anggaplah aku mengirim input name kategori yang berisi nilai handphone
            axios.get('/api/posts', {
                params: {
                    category: name
                }
            })
            .then((response) => {
                this.posts = response.data.data;
            })
            .catch((error) => {
                console.log(error);
            })
        }
    },

    // when the component is mounted
    mounted() {
        this.read_beberapa_posts();
        this.read_semua_kategori();
    }
}
</script>

<style>
h3 {
    font-size: 30px;
    text-align: center;
    margin: 50px 0;
    color: #fff; 
}
</style>
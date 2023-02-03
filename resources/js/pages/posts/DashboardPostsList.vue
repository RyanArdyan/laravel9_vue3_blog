<template>
    <div class="categories-list">
        <h1>Posts List</h1>
        <!-- <success message -->
        <div class="success-msg" v-if="success">
            <i class="fa fa-check"></i>
            Post deleted successfully
        </div>
        <div class="success-msg" v-if="editSuccess">
            <i class="fa fa-check"></i>
            Post edited successfully
        </div>
        <!-- attr :key diperlukan agar pengulangan nya tidak error -->
        <div v-for="(post, index) in beberapa_posts" :key="post.id" class="item">
            <span>{{ index + 1 }}</span>
            <p>{{ post.title  }}</p>
            <div>
                <router-link :to="{name: 'EditPosts', params: {slug: post.slug}}" class="edit-link">Edit</router-link>
                <input @click="destroy(post.slug)" type="button" class="delete-btn" value="delete">
            </div>

        </div>
        <div class="index-categories">
            <router-link :to="{ name: 'CreatePosts' }">Create post<span>&#8594;</span></router-link>
        </div>
    </div>
</template>

<script>
export default {
    // notifikasi postingan berhasil diperbarui
    props: ['editSuccess'],
    // Buat property
    data() {
        return {
            beberapa_posts: [],
            // notifikasi postingan berhasil dihapus
            success: false,
        }
    },
    methods: {
        read_beberapa_posts() {
            axios.get('/api/dashboard-posts')
                .then((response) => {
                    this.beberapa_posts = response.data.data;
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
                })
        },
        destroy(slug) {
            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: "Anda tidak akan dapat mengembalikan ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.delete(`/api/posts/${slug}`)
                        .then(() => {
                        Swal.fire(
                            'Dihapus!',
                            'Your file has been deleted.',
                            'success'
                        );
                            
                            // reload beberapa postingna
                            this.read_beberapa_posts();
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
                });


            
        }
    },
    // jika compoenent berhasil dipasang maka jalankan fungsi berikut
    mounted() {
        this.read_beberapa_posts();
    }
}
</script>

<style scoped >
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
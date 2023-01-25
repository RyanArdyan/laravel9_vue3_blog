<template>
    <!-- header -->
    <header class="header">
        <div class="header-text">
            <h1>Alphayo Blog</h1>
            <h4>Home of verified news...</h4>
        </div>
        <div class="overlay"></div>
    </header>

    <h2 class="header-title">Latest Blog Posts</h2>
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
</template>

<script>
export default {
    emits: ['updateSidebar'],
    data() {
        return {
            posts: []
        }
    },

    // when the component is mounted
    mounted() {
        axios.get('/api/home-posts')
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
    }
}
</script>
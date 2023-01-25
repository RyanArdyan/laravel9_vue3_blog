<template>
    <section class="single-blog-post">
        <h1>{{ post.title }}</h1>

        <p class="time-and-author">
            {{ post.created_at }}
            <span>Written By {{ post.user }}</span>
        </p>

        <div class="single-blog-post-ContentImage" data-aos="fade-left">
            <img :src="`/${post.image}`" alt="" />
        </div>

        <div class="about-text">
            <p>
                {{ post.body }}
            </p>
        </div>
    </section>
    <section class="recommended">
        <p>Related</p>
        <div class="recommended-cards">
            <a href="">
                <div class="recommended-card">
                    <img src="/images/pic5.jpg" alt="" loading="lazy" />
                    <h4>
                        12 Health Benefits Of Pomegranate Fruit
                    </h4>
                </div>
            </a>
            <a href="">
                <div class="recommended-card">
                    <img src="/images/pushups.jpg" alt="" loading="lazy" />
                    <h4>
                        The Truth About Pushups
                    </h4>
                </div>
            </a>
            <a href="">
                <div class="recommended-card">
                    <img src="/images/smoothies.jpg" alt="" loading="lazy" />
                    <h4>
                        Healthy Smoothies
                    </h4>
                </div>
            </a>

        </div>
    </section>
</template>

<script>
export default {
    // emits: ['updateSidebar'],
    props: ['slug'],
    data() {
        return {
            post: {}
        }
    },
    mounted() {
        axios.get('/api/posts/' + this.slug)
            .then((response) => {
                this.post = response.data.data;
                // console.log(this.post);
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
}
</script>
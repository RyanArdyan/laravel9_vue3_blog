<template>
    <div id="wrapper">
        <!-- tambahkan .showOverlay jika property overlayVisibility true -->
        <!-- value default overlayVisibily adalah false -->
        <div class="sidebar" :class="{showOverlay: overlayVisibility}">
            <span class="closeButton" @click="hideOverlay()">&times;</span>
            <p class="brand-title"><a href="#">Tiko Blog</a></p>

            <div class="side-links">
                <ul>
                    <!-- panggil router yang bernama home -->
                    <li>
                        <router-link @click="hideOverlay()" :to="{name: 'Home'}">Home</router-link>
                    </li>
                    <li>
                        <router-link @click="hideOverlay()" :to="{name: 'Blog'}">Blog</router-link>
                    </li>
                    <li>
                        <router-link @click="hideOverlay()" :to="{name: 'About'}">About</router-link>
                    </li>
                    <li>
                        <router-link @click="hideOverlay()" :to="{name: 'Contact'}">Contact</router-link>
                    </li>
                    <!-- jika loggedIn berisi false atau user belum login maka tampilkan register -->
                    <li v-if="!loggedIn">
                        <router-link @click="hideOverlay()" :to="{name: 'Register'}">Register</router-link>
                    </li>
                    <li v-if="!loggedIn">
                        <router-link @click="hideOverlay()" :to="{name: 'Login'}">Login</router-link>
                    </li>
                    <!-- jika property loggedIn berisi true maka tampilkan dashboard -->
                    <!-- jika user sudah login dan user adalah admin maka tampilkan menu dashboard -->
                    <li v-if="apakah_admin">
                        <router-link @click="hideOverlay()" :to="{name: 'Dashboard'}">Dashboard</router-link>
                    </li>
                </ul>
            </div>

            <!-- sidebar footer -->
            <footer class="sidebar-footer">
                <div>
                    <a href=""><i class="fab fa-facebook-f"></i></a>
                    <a href=""><i class="fab fa-instagram"></i></a>
                    <a href=""><i class="fab fa-twitter"></i></a>
                </div>

                <small>&copy 2021 Alphayo Blog</small>
            </footer>
        </div>
        <!-- Menu Button -->
        <div class="menuButton" @click="ShowOverlay()">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>
        <!-- main -->
        <main class="container">
            <!-- render/membuat komponen tergantung pada halaman yang dikunjungi -->
            <!-- component adalah view kecil atau child dari App.vue -->
			<!-- @update-sidebar berfungsi untuk menangkap emit updateSidebar milik anaknya yaitu login dan register --> 
            <router-view @method-apakah-admin="methodApakahAdmin" @user-logout="userLogout()" @update-sidebar="updateSidebar()" @show-edit-success="showEditSuccess()" :editSuccess="editSuccess" :key="$route.path"></router-view>
        </main>

        <!-- Main footer -->
        <footer class="main-footer">
            <div>
                <a href=""><i class="fab fa-facebook-f"></i></a>
                <a href=""><i class="fab fa-instagram"></i></a>
                <a href=""><i class="fab fa-twitter"></i></a>
            </div>
            <small>&copy; 2021 Alphayo Blog</small>
        </footer>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                // buat property
                overlayVisibility: false,
                loggedIn: false,
                editSuccess: false,
                // fitur jika user sudah logn dan adalah admin maka tampilkan menu dashboard
                apakah_admin: false
            };
        },

        methods: {
            // buat method
            ShowOverlay() {
                // panggil property overlayVisibility, yg tadinya false, akan menjadi true
                this.overlayVisibility = true
            },
            hideOverlay() {
                this.overlayVisibility = false;
            },
            // fitur menghilangkan menu login dan registrasi ketika user sudah login
            updateSidebar() {
             // jadi nanti dari false ke true dan sebaliknya
                this.loggedIn = !this.loggedIn;
                this.apakah_admin = !this.apakah_admin;
            },
            userLogout() {
                this.loggedIn = false;
                this.apakah_admin = false;
            },
            showEditSuccess() {
                this.editSuccess = true;
                setInterval(() => {
                    this.editSuccess = false
                }, 3000);
            },
            // fitur jika user sudah logn dan adalah admin maka tampilkan menu dashboard
            methodApakahAdmin() {
                this.apakah_admin = true;
            },
            jika_belum_login() {
                axios.get('/api/user')
                    .then((response) => {
                        // console.log(response);
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
            }
        },

        mounted() {
            // jika user sudah login maka pastinya ada 'authenticatd' di localStorage karena aku membuat localStorage.setItem('authenticated', 'true') di child nya yaitu Login.vue, jadi aku bisa mendapatkan nya menggunakan localStorage.getItem
            if (localStorage.getItem('authenticated')) {
                this.loggedIn = true;
            } else if(!localStorage.getItem('authenticated')) {
                this.loggedIn = false;
            };

            if (localStorage.getItem('apakah_admin')) {
                this.apakah_admin = true;
            } else if (!localStorage.getItem('apakah_admin')) {
                this.apakah_admin = false;
            };

            // fitur jika user belum login maka hilangkan menu dashbaord dan tampilkan menu registrasi dan login
            this.jika_belum_login();
        }
    };
</script>

<!-- Ketika sebuah <style>tag memiliki scopedatribut, CSS-nya akan berlaku hanya untuk elemen komponen saat ini. -->
<style scoped>
    /*  fitur toggle menu sidebar  */
    .showOverlay {
        width: 100%;
        z-index: 5;
    }
</style>
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
                    <!-- jika loggedIn berisi true maka tampilkan dashboard -->
                    <li v-if="loggedIn">
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
            <router-view @user-logout="userLogout()" @update-sidebar="updateSidebar()"></router-view>
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
                loggedIn: false
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
            },
            userLogout() {
                this.loggedIn = false;
            }
        },

        mounted() {
            // jika user sudah login maka pastinya ada 'authenticatd' di localStorage karena aku membuat localStorage.setItem('authenticated', 'true') di child nya yaitu Login.vue, jadi aku bisa mendapatkan nya menggunakan localStorage.getItem
            if (localStorage.getItem('authenticated')) {
                this.loggedIn = true;
            } else {
                this.loggedIn = false;
            };
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LaVue Blog</title>
	
	{{-- font awesome dari cdnjs.com --}}
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
    />

    {{-- sweetalert --}}
    <link href="//cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    {{-- di dapatkan dari menu Dasar/Bunder Aset di laravel --}}
    @vite('resources/js/app.js')
</head>
<body>
    
    {{-- jadi div#app akan berisi kode-kode milik App.vue yang berada di resources/js --}}
    <div id="app"></div>

    {{-- sweetalert2 --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
</body>
</html>
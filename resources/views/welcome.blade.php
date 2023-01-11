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

    {{-- di dapatkan dari menu Dasar/Bunder Aset di laravel --}}
    @vite('resources/js/app.js')
</head>
<body>
    
    {{-- jadi div#app akan berisi kode-kode milik App.vue yang berada di resources/js --}}
    <div id="app"></div>

</body>
</html>
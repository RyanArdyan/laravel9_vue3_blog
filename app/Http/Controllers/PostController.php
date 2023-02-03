<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;

class PostController extends Controller
{
    public function index(Request $request)
    {
        // di component blog jika aku click kategori laptop maka tampilkan postingan yang berkategori laptop
        // jika ada input name kategori
        if ($request->category) {
            $detail_kategori = Category::where('name', $request->category)->first();
            // 1 kategori punya banyak postingan karena relasi hasMany
            // collection berarti banyak
            return PostResource::collection($detail_kategori->posts()->latest()->get());
        // fitur filter postingan berdasarkan pencarian
        // jika ada yang mengirim input name pencarian
        } else if($request->search) {
            return PostResource::collection(Post::where('title', 'LIKE', '%' . $request->search . '%')
                ->orWhere('body', 'like', '%' . $request->search . '%')->latest()->get());
        } else {
            return PostResource::collection(Post::latest()->get());
        };

    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'file' => 'required | image',
            'body' => 'required',
            'category_id' => 'required'
        ], [
            'category_id' => 'Kategori harus dipilih.'
        ]);

        $title = $request->title;
        $category_id = $request->category_id;

        // jika tidak ada postingan
        if (!Post::count()) {
            $postId = 1;
        } else {
            $postId = Post::latest()->first()->id + 1;
        }

        // $title = my title
        // $slug = my-title-1
        $slug = Str::slug($title, '-') . '-' . $postId;
        $user_id = auth()->user()->id;
        $body = $request->input('body');
        $imagePath = 'storage/' . $request->file('file')->store('postsImages', 'public');

        // create and save post
        $post = new Post();
        $post->title = $title;
        $post->category_id = $category_id;
        $post->slug = $slug;
        $post->user_id = $user_id;
        $post->body = $body;
        $post->imagePath = $imagePath;
        $post->save();
    }

    public function show(Post $post)
    {
        // lindungi Post Edit dari nonauthor
        // jika yang login bukan penulis postingan
        if (auth()->user()->id !== $post->user_id) {
        // Kode status respons Terlarang HTTP 403 menunjukkan bahwa server memahami permintaan tersebut tetapi menolak untuk mengotorisasinya.
        // Otorisasi adalah proses dimana server menentukan apakah klien memiliki izin untuk menggunakan sumber daya atau mengakses file.
            // kirim status 403 ke front end
            return abort(403);
        };
        return new PostResource($post);
    }

    public function update(Request $request, Post $post) {
        // jika yang login bukan penulis postingan
        if (auth()->user()->id !== $post->user_id) {
            return abort(403);
        };

        // validasi input2x formulir
        $request->validate([
            'title' => ['required'],
            'file' => ['nullable', 'image'],
            'body' => ['required'],
            'category_id' => ['required']
        ]);

        $title = $request->title;
        $category_id = $request->category_id;

        $slug = Str::slug($title, '-') . '-' . $post->id;
        $body = $request->input('body');

        // jika ada file atau jika user mengupload file
        if ($request->file('file')) {
            // pecah gambar agar aku bisa mendapatkan nama gambarnya saja
            $pecah_gambar = explode('/', $post->imagePath);
            $nama_gambar = $pecah_gambar[2];
            // hapus gambar lama
            Storage::delete("public/postsImages/$nama_gambar");
            // simpan di folder public, nama filenya adalah storage/postsImages/sfasdl.jpg
            $imagePath = 'storage/' . $request->file('file')->store('postsImages', 'public');
            $post->imagePath = $imagePath;
        };

        // create and save post
        $post->title = $title;
        $post->category_id = $category_id;
        $post->slug = $slug;
        $post->body = $body;
        return $post->save();
    }

    public function destroy(Post $post)
    {
        // jika yang login bukan penulis postingan
        if (auth()->user()->id !== $post->user_id) {
            // kasi error di console.log status 403 
            return abort(403);
        };

        // hapus gambar
        // pecah gambar agar aku bisa mendapatkan nama gambarnya saja
        $pecah_gambar = explode('/', $post->imagePath);
        $nama_gambar = $pecah_gambar[2];
        // hapus gambar lama
        Storage::delete("public/postsImages/$nama_gambar");

        // hapus detail postingan
        $post->delete();
        return response()->json('Postingan berhasil dihapus');
    }
}

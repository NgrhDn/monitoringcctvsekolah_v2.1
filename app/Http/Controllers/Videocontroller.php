<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;

class Videocontroller extends Controller
{
     // Method untuk menampilkan halaman form input video
     public function create()
     {
         return view('videos.create');
     }

      // Method untuk menyimpan data video ke database
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'link' => 'required|url'
        ]);

        Video::create([
            'title' => $request->title,
            'link' => $request->link
        ]);

        return redirect()->route('videos.index')->with('success', 'Video berhasil ditambahkan.');
    }

     // Method untuk menampilkan video di dashboard
     public function index()
     {
         $videos = Video::all();
         return view('videos.index', compact('videos'));
     }
}

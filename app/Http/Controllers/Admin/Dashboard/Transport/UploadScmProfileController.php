<?php

namespace App\Http\Controllers\Admin\Dashboard\Transport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadScmProfileController extends Controller
{
    // === UPLOAD FOTO ===
    public function upload(Request $request, $folder)
    {
        $request->validate([
            'image' => 'required|mimes:jpg,jpeg,png|max:5120' // max 5MB
        ]);

        $file = $request->file('image');

        [$width, $height] = getimagesize($file);

        $maxWidth  = 2000;
        $maxHeight = 2000;

        if ($width > $maxWidth || $height > $maxHeight) {
            return response()->json([
                'error' => "Resolusi terlalu besar. Maksimal {$maxWidth}x{$maxHeight}px"
            ], 422);
        }

        $path = public_path("upload/$folder");
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());


        $file->move($path, $filename);

        return response()->json([
            'message'  => 'Upload berhasil',
            'filename' => $filename,
            'resolution' => "{$width}x{$height}",
            'url'      => url("upload/$folder/$filename"),
        ]);
    }

    public function delete($folder, $filename)
    {
        $path = public_path("upload/$folder/$filename");

        if (!file_exists($path)) {
            return response()->json(['error' => 'File tidak ditemukan'], 404);
        }

        unlink($path);

        return response()->json(['message' => 'File berhasil dihapus']);
    }

    // === LIST FOTO DALAM FOLDER ===
    public function list($folder)
    {
        $path = public_path("upload/$folder");

        if (!file_exists($path)) {
            return response()->json([]);
        }

        $files = array_values(array_diff(scandir($path), ['.', '..']));

        $data = array_map(function ($file) use ($folder) {
            return [
                'filename' => $file,
                'url' => url("upload/$folder/$file"),
            ];
        }, $files);

        return response()->json($data);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{
    public function index()
    {
        $rawFiles = Storage::disk('s3')->allFiles();
        $files = [];

        foreach ($rawFiles as $filePath) {
            $files[] = [
                'name' => basename($filePath),
                'path' => $filePath,
                'size' => $this->formatBytes(Storage::disk('s3')->size($filePath)),
                'type' => Storage::disk('s3')->mimeType($filePath),
                'last_modified' => date('d/m/Y H:i', Storage::disk('s3')->lastModified($filePath)),
            ];
        }

        return view('index', compact('files'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:10240',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $file = $request->file('file');
        $fileName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
            . '.' . $file->getClientOriginalExtension();
        $path = Storage::disk('s3')->putFileAs('uploads', $file, $fileName);
        return back()->withSuccess('Fichier envoyé avec succès !')->with('path', $path);
    }

    public function download($path)
    {
        if (!Storage::disk('s3')->exists($path)) {
            return back()->withErrors('Fichier introuvable');
        }

        $url = Storage::disk('s3')->temporaryUrl(
            $path,
            now()->addMinutes(5),
            [
                'ResponseContentDisposition' => 'attachment; filename="' . basename($path) . '"'
            ]
        );
        return redirect()->away($url);
    }

    public function destroy($path)
    {
        if (!Storage::disk('s3')->exists($path)) {
            return back()->withErrors('Fichier introuvable');
        }

        Storage::disk('s3')->delete($path);

        return back()->withSuccess('Fichier supprimé de S3.');
    }

    private function formatBytes($bytes, $precision = 2) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon S3 Cloud Drive</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-50 font-sans text-gray-800 antialiased">

<div class="max-w-6xl mx-auto px-4 py-8">

    <!-- En-tête -->
    <header class="flex items-center justify-between mb-8 pb-4 border-b border-gray-200">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                📁 Mon S3 Drive
            </h1>
            <p class="text-sm text-gray-500">Stockage sécurisé sur Amazon Web Services</p>
        </div>
        <span class="text-xs bg-blue-100 text-blue-800 font-medium px-2.5 py-0.5 rounded-full">AWS S3 Connecté</span>
    </header>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded shadow-xs">
            {{ session('success') }}
        </div>
    @endif
    @if(session('errors'))
        <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-red-500 text-red-800 rounded shadow-xs">
            {{ session('errors') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="md:col-span-1">
            <div class="bg-white p-6 rounded-xl shadow-xs border border-gray-100 sticky top-6">
                <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4">Ajouter un fichier</h2>

                <form action="{{ route('drive.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-500 transition-colors cursor-pointer group relative">
                        <input type="file" name="file" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="this.form.submit()">
                        <div class="space-y-2">
                            <span class="text-2xl block group-hover:scale-110 transition-transform">📤</span>
                            <p class="text-xs font-medium text-gray-600">Cliquez pour parcourir</p>
                            <p class="text-[10px] text-gray-400">Max : 10 Mo</p>
                        </div>
                    </div>
                    @error('file')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </form>
            </div>
        </div>

        <div class="md:col-span-3">
            <div class="bg-white rounded-xl shadow-xs border border-gray-100 overflow-hidden">

                @if(count($files) === 0)
                    <!-- État vide -->
                    <div class="p-12 text-center">
                        <span class="text-4xl block mb-2">☁️</span>
                        <p class="text-gray-500 font-medium">Votre drive est vide pour le moment.</p>
                        <p class="text-xs text-gray-400 mt-1">Déposez votre premier document pour commencer.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                            <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="p-4">Nom</th>
                                <th class="p-4">Type</th>
                                <th class="p-4">Taille</th>
                                <th class="p-4">Modifié le</th>
                                <th class="p-4 text-right">Actions</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                            @foreach($files as $file)
                                <tr class="hover:bg-gray-50/70 transition-colors">
                                    <td class="p-4 font-medium text-gray-900 max-w-xs truncate">
                                        {{ $file['name'] }}
                                    </td>
                                    <td class="p-4">
                                                <span class="text-xs bg-gray-100 px-2 py-1 rounded-sm text-gray-600 font-mono">
                                                    {{ Str::limit($file['type'], 20) }}
                                                </span>
                                    </td>
                                    <td class="p-4 text-gray-500 text-xs">
                                        {{ $file['size'] }}
                                    </td>
                                    <td class="p-4 text-gray-500 text-xs">
                                        {{ $file['last_modified'] }}
                                    </td>
                                    <td class="p-4 text-right space-x-2 whitespace-nowrap">
                                        <a href="{{ route('drive.download', $file['path']) }}" class="inline-flex items-center text-xs bg-blue-50 text-blue-600 hover:bg-blue-100 font-medium px-2.5 py-1.5 rounded transition-colors">
                                            ⬇️ Télécharger
                                        </a>
                                        <form action="{{ route('drive.destroy', $file['path']) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Supprimer définitivement ce fichier ?')" class="inline-flex items-center text-xs bg-rose-50 text-rose-600 hover:bg-rose-100 font-medium px-2.5 py-1.5 rounded transition-colors">
                                                🗑️
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
</body>
</html>

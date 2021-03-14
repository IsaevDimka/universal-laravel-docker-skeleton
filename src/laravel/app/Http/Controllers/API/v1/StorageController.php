<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\API\ApiController;

use App\Http\Resources\StorageResource;
use App\Models\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage as StorageFacade;
use Illuminate\Support\Str;

class StorageController extends ApiController
{
    public function index(Request $request)
    {
        $queryBuilder = Storage::query();
        $items = StorageResource::items($queryBuilder->paginate($request->get('limit')));
        return api()->ok(null, compact('items'));
    }

    public function store(Request $request)
    {
        try {
            throw_unless($request->hasFile('file'), \RuntimeException::class, 'File is required', 422);

            $file = $request->file('file');
            $type = $file->getClientOriginalExtension();
            $filename = Str::uuid() . '.' . $type;
            $size = $file->getSize();
            // put file
            $path = Storage::PUBLIC_PATH . $filename;
            StorageFacade::url($file->storeAs(Storage::STORAGE_PATH, $filename));

            $payload = [
                'filename' => $filename,
                'type' => $type,
                'path' => $path,
                'size' => $size,
                'user_id' => optional(auth())->id() ?? null,
            ];
            // create storage item
            $storage = Storage::withTrashed()->create($payload);
            return api()->ok('File upload success', StorageResource::make($storage), compact('payload'));
        } catch (\Throwable $exception) {
            return api()->validation('Error upload file. Please try again', [
                'message' => (string) $exception->getMessage(),
                'code' => (int) $exception->getCode(),
            ]);
        }
    }

    public function show(int $storage_id)
    {
        $storage = Storage::withTrashed()->findOrFail($storage_id);
        return api()->ok(null, StorageResource::make($storage));
    }

    public function update(Request $request, int $storage_id)
    {
        $payload = $request->all();
        $storage = Storage::withTrashed()->findOrFail($storage_id);
        return api()->ok(__('api.update'), StorageResource::make($storage), compact('payload'));
    }

    public function destroy(int $storage_id)
    {
        $storage = Storage::withoutTrashed()->findOrFail($storage_id);
        $storage->delete();
        return api()->ok(__('api.restore'), StorageResource::make($storage));
    }

    public function restore(int $storage_id)
    {
        $storage = Storage::onlyTrashed()->findOrFail($storage_id);
        $storage->restore();
        return api()->ok(__('api.restore'), StorageResource::make($storage));
    }

    public function delete(int $storage_id)
    {
        $storage = Storage::withTrashed()->findOrFail($storage_id);
        $storage->forceDelete();
        return api()->ok(__('api.delete'), compact('storage_id'));
    }
}

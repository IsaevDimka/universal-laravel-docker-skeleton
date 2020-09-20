<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\API\ApiController;

use App\Http\Resources\StorageResource;
use App\Models\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StorageController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return api()->ok('', StorageResource::collection(Storage::all()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try{
            $type = $request->file->getClientOriginalExtension();
            $filename = Str::uuid().'.'.$type;
            $size = $request->file->getSize();
            // put file
            $path = Storage::PUBLIC_PATH.$filename;
            \Storage::url($request->file->storeAs(Storage::STORAGE_PATH, $filename));

            // create storage item
            $storage = Storage::withTrashed()->create([
                'filename' => $filename,
                'type'     => $type,
                'path'     => $path,
                'size'     => $size,
                'user_id'  => auth()->check() ? auth()->id() : null,
            ]);
            return api()->ok('File upload success', StorageResource::make($storage));
        } catch(\Throwable $exception)
        {
            return api()->error('Error upload file. Please try again', [
                'message' => (string) $exception->getMessage(),
                'code'    => (int) $exception->getCode(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Storage  $storage
     * @return \Illuminate\Http\Response
     */
    public function show(Storage $storage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Storage  $storage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Storage $storage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Storage  $storage
     * @return \Illuminate\Http\Response
     */
    public function destroy(Storage $storage)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFileRequest;
use App\Http\Resources\FileResource;
use App\Models\Comment;
use App\Models\File;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileController extends Controller
{
    /**
     * Store all Models with a hasMany relationship that can be stored as files.
     *
     * @var array
     */
    private array $hasManyModels = [
        Comment::class,
        Task::class
    ];

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreFileRequest $request
     * @return JsonResponse
     */
    public function store(StoreFileRequest $request)
    {
        $validated_data = $request->validated();

        $conditions = $this->getModelConditions($validated_data);

        $file_name = File::where($conditions)->pluck('name_uploaded')->first();

        $path = $this->storeFile($validated_data, $file_name);

        if(Arr::exists($conditions, 'name')) {
            $file = File::updateOrCreate(
                [
                    'fileable_type' => $validated_data['fileable_type'],
                    'fileable_id' => $validated_data['fileable_id'],
                    'name' => $validated_data['file']->getClientOriginalName()
                ],
                [
                    'size' => $validated_data['file']->getSize(),
                    'mime_type' => $validated_data['file']->getMimeType(),
                    'name' => $validated_data['file']->getClientOriginalName(),
                    'name_uploaded' => substr($path, strpos($path, '/') + 1),
                    'path' => $path
                ]
            );
        } else {
            $file = File::updateOrCreate(
                [
                    'fileable_type' => $validated_data['fileable_type'],
                    'fileable_id' => $validated_data['fileable_id'],
                ],
                [
                    'size' => $validated_data['file']->getSize(),
                    'mime_type' => $validated_data['file']->getMimeType(),
                    'name' => $validated_data['file']->getClientOriginalName(),
                    'name_uploaded' => substr($path, strpos($path, '/') + 1),
                    'path' => $path
                ]
            );
        }

        $status = $file->save();

        return response()->json([
            'status' => $status,
            'file' => new FileResource($file)
        ]);
    }

    /**
     * Remove the file from the database and the disk.
     *
     * @param  File $file
     * @return JsonResponse
     */
    public function destroy(File $file)
    {
        if (!Storage::disk('public')->exists($file->path)) {
            return response()->json([
                'error' => 'The file was not found on the disk'
            ]);
        }

        $status = File::destroy($file->id);

        Storage::disk('public')->delete($file->path);

        return response()->json([
            'status' => $status
        ]);
    }

    /**
     * @param Request $request
     * @return BinaryFileResponse
     */
    public function download(Request $request): BinaryFileResponse
    {
        $path = '/storage/' . $request->input('path');

        return response()->download(public_path($path));
    }

    /**
     * Get the Model conditions since hasMany and hasOne relationships have different requirements.
     *
     * @param array $data
     * @return array
     */
    private function getModelConditions(array $data)
    {
        $conditions = [
            'fileable_type' => $data['fileable_type'],
            'fileable_id' => $data['fileable_id']
        ];

        if (in_array($data['fileable_type'], $this->hasManyModels, true)) {
            $conditions['name'] = $data['file']->getClientOriginalName();
        }

        return $conditions;
    }

    /**
     * Store the file on the disk and overwrite if it already exists.
     *
     * @param $file
     * @param $name
     * @return string
     */
    private function storeFile($file, $name)
    {
        $file_relative_path = $file['directory'] . '/' . $name;

        if(isset($name) && Storage::disk('public')->exists($file_relative_path)) {
            Storage::disk('public')->delete($file_relative_path);
            $path = Storage::disk('public')->putFileAs($file['directory'], $file['file'], $name);
        } else {
            $path = Storage::disk('public')->putFile($file['directory'], $file['file']);
        }

        return $path;
    }
}

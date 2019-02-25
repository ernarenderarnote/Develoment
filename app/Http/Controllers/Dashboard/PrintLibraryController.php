<?php

namespace App\Http\Controllers\Dashboard;

use Gate;
use FractalManager;

use Illuminate\Http\Request;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\File\UploadFileFormRequest;
use App\Transformers\File\ImageFileFullTransformer;
use App\Models\File;

class PrintLibraryController extends Controller
{
    use Traits\LibraryTrait;

    /**
     * Search files
     */
    public function search(Request $request)
    {
        $search = filter_var($request->get('search'), FILTER_SANITIZE_STRING);

        $filesQuery = auth()->user()->printFiles()
            ->orderBy('file_updated_at', 'desc')
            ->search($search);

        $paginator = $filesQuery->paginate(8);

        return response()->api([
            'files' => FractalManager::serializePaginator($paginator, new ImageFileFullTransformer)
        ]);
    }

    /**
     * Create file
     */
    public function uploadFile(UploadFileFormRequest $request)
    {
        $uploadedFile = $request->file('file');

        $file = File::create([
            'file' => $uploadedFile,
            'type' => File::TYPE_PRINT_FILE
        ]);

        if ( ! $file ) {
            return abort(500, trans('messages.file_cannot_be_uploaded'));
        }

        return response()->api(trans('messages.file_uploaded'), [
            'file' => $file->transformFull()
        ]);
    }
}

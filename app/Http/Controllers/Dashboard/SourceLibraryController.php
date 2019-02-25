<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

use App\Http\Requests\Dashboard\File\UploadSourceFileFormRequest;
use App\Transformers\File\FileFullTransformer;
use App\Models\FileAttachment;

class SourceLibraryController extends Controller
{
    use Traits\LibraryTrait;

    /**
     * Search files
     */
    public function search(Request $request)
    {
        $search = filter_var($request->get('search'), FILTER_SANITIZE_STRING);

        $filesQuery = auth()->user()->sourceFiles()
            ->orderBy('file_updated_at', 'desc')
            ->search($search);

        return response()->api([
            'files' => $this->serializePaginator($filesQuery, new FileFullTransformer, null, 8)
        ]);
    }

    /**
     * Create file
     */
    public function uploadFile(UploadSourceFileFormRequest $request)
    {
        $uploadedFile = $request->file('file');

        $file = FileAttachment::create([
            'file' => $uploadedFile,
            'type' => FileAttachment::TYPE_SOURCE_FILE
        ]);

        if ( ! $file ) {
            return abort(500, trans('messages.file_cannot_be_uploaded'));
        }

        return response()->api(trans('messages.file_uploaded'), [
            'file' => $file->transformFull()
        ]);
    }
}

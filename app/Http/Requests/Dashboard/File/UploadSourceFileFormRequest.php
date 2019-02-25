<?php

namespace App\Http\Requests\Dashboard\File;

use App\Http\Requests\Request;

class UploadSourceFileFormRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function messages()
	{
		return [
			'file.mimetypes' => trans('validation.mimetypes_source_file')
		];
	}

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $sourceMimes = [

            // TODO: this is bad, but php cannot get mime type of .eps
            // at least on my ubuntu
            'application/octet-stream'
        ];

        // PSD
        $sourceMimes = array_merge($sourceMimes, [
            'image/photoshop',
            'image/x-photoshop',
            'image/psd',
            'application/photoshop',
            'image/vnd.adobe.photoshop',
            'pplication/psd',
            'z-application/zz-winassoc-psd'
        ]);

        // EPS
        $sourceMimes = array_merge($sourceMimes, [
            'application/postscript',
            'application/eps',
            'application/x-eps',
            'image/eps',
            'image/x-eps'
        ]);

        // AI
        $sourceMimes = array_merge($sourceMimes, [
            'application/illustrator',
            'application/pdf' // for some .ai files we for some reason receive pdf mime
        ]);

        // PNG
        $sourceMimes = array_merge($sourceMimes, [
            'image/png'
        ]);

        return [
            'file' => 'file|required|max:40960|mimetypes:'.implode(',',$sourceMimes)
        ];
    }
}

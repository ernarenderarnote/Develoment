<?php

namespace App\Http\Controllers\Api\StoreApi;

use DB;
use Input;
use DingoRoute;
use Request as RequestFacade;
use Dingo\Api\Http\Request as DingoRequest;
use Illuminate\Http\Request;

use App\Http\Requests\Dashboard\File\UploadFileFormRequest;
use App\Http\Requests\Dashboard\File\UploadSourceFileFormRequest;

/**
 * Files
 *
 * @Resource("Files", uri="/library", requestHeaders={
 *      "Authorization": "Bearer Ik6nj6HrKiJwVwgMfGOUPOz5Wa6ZuZns1kRli16sZC4YdigLtjJJlzDKdFZt"
 * })
*/
class PrintLibraryController extends StoreApiController
{
    /**
     * Print library files list
     *
     * Get current user's files list. Could be used with name filter
     *
     * @Get("/prints/search?page={page}&search={search}")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request(identifier="/library/prints/search?page=1&search=filename"),
     *      @Response(200, body={"status":200,"isError":false,"message":null,"data":{"files":{"data":{{"id":428,"type":"print_file","typeName":"Print File","name":"68673932ff389c2610a8dac117a5e386.jpg","size":48260,"updated":"2016-11-09T18:10:33+00:00","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/428\/medium\/68673932ff389c2610a8dac117a5e386.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/428\/original\/68673932ff389c2610a8dac117a5e386.jpg","dimensions":{"width":736,"height":1104}},{"id":406,"type":"print_file","typeName":"Print File","name":"ladies_v_front_bro_science_empire_lifts_back_web.jpg","size":2319519,"updated":"2016-10-24T13:36:29+00:00","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/406\/medium\/ladies_v_front_bro_science_empire_lifts_back_web.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/406\/original\/ladies_v_front_bro_science_empire_lifts_back_web.jpg","dimensions":{"width":1900,"height":2745}},{"id":388,"type":"print_file","typeName":"Print File","name":"ladies_v_front_bro_science_empire_lifts_back_web.jpg","size":171749,"updated":"2016-10-19T20:03:46+00:00","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/388\/medium\/ladies_v_front_bro_science_empire_lifts_back_web.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/388\/original\/ladies_v_front_bro_science_empire_lifts_back_web.jpg","dimensions":{"width":427,"height":617}},{"id":360,"type":"print_file","typeName":"Print File","name":"stars.jpg","size":2080226,"updated":"2016-10-18T19:20:43+00:00","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/360\/medium\/stars.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/360\/original\/stars.jpg","dimensions":{"width":5000,"height":3500}},{"id":214,"type":"print_file","typeName":"Print File","name":"big5-2-God-is-in-Nature.jpg","size":12821358,"updated":"2016-10-11T14:45:39+00:00","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/214\/medium\/big5-2-God-is-in-Nature.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/214\/original\/big5-2-God-is-in-Nature.jpg","dimensions":{"width":8512,"height":5664}},{"id":128,"type":"print_file","typeName":"Print File","name":"flash.png","size":77700,"updated":"2016-10-07T17:35:37+00:00","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/128\/medium\/flash.png","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/128\/original\/flash.png","dimensions":{"width":1024,"height":1024}},{"id":127,"type":"print_file","typeName":"Print File","name":"ghost-busters.png","size":390127,"updated":"2016-10-07T17:35:33+00:00","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/127\/medium\/ghost-busters.png","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/127\/original\/ghost-busters.png","dimensions":{"width":1152,"height":998}}},"meta":{"pagination":{"total":7,"count":7,"per_page":8,"current_page":1,"total_pages":1,"links":{}}}}}})
     * })
     * @Parameters({
     *      @Parameter("page", type="integer", required=false, description="Pagination page", default=1),
     *      @Parameter("search", type="string", required=false, description="Search name filter")
     * })
     */
    public function search(Request $request)
    {
        return app('App\Http\Controllers\Dashboard\PrintLibraryController')
            ->search($request);
    }

    /**
     * Upload print library file
     *
     * @Post("/prints/upload")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request(identifier="/library/prints/upload"),
     *      @Response(200, body={"status":200,"isError":false,"message":"File uploaded","data":{"file":{"id":503,"type":"print_file","typeName":"Print File","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/503\/original\/legos-star-wars-418950-1181x787.jpg","name":"legos-star-wars-418950-1181x787.jpg","size":946272,"updated":"2016-12-23T21:59:42+00:00"}}}),
     *      @Response(422, body={"status":422,"isError":true,"message":"422 Unprocessable Entity","validationErrors":{"file":{"The file must be an image."}},"data":{}})
     * })
     * @Parameters({
     *      @Parameter("file", type="file", required=true, description="File")
     * })
     */
    public function uploadFile(UploadFileFormRequest $request)
    {
        return app('App\Http\Controllers\Dashboard\PrintLibraryController')
            ->uploadFile($request);
    }

    /**
     * Source library files list
     *
     * Get current user's files list. Could be used with name filter
     *
     * @Get("/sources/search?page={page}&search={search}")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request(identifier="/library/sources/search?page=1&search=filename"),
     *      @Response(200, body={"status":200,"isError":false,"message":null,"data":{"files":{"data":{{"id":428,"type":"print_file","typeName":"Print File","name":"68673932ff389c2610a8dac117a5e386.jpg","size":48260,"updated":"2016-11-09T18:10:33+00:00","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/428\/medium\/68673932ff389c2610a8dac117a5e386.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/428\/original\/68673932ff389c2610a8dac117a5e386.jpg","dimensions":{"width":736,"height":1104}},{"id":406,"type":"print_file","typeName":"Print File","name":"ladies_v_front_bro_science_empire_lifts_back_web.jpg","size":2319519,"updated":"2016-10-24T13:36:29+00:00","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/406\/medium\/ladies_v_front_bro_science_empire_lifts_back_web.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/406\/original\/ladies_v_front_bro_science_empire_lifts_back_web.jpg","dimensions":{"width":1900,"height":2745}},{"id":388,"type":"print_file","typeName":"Print File","name":"ladies_v_front_bro_science_empire_lifts_back_web.jpg","size":171749,"updated":"2016-10-19T20:03:46+00:00","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/388\/medium\/ladies_v_front_bro_science_empire_lifts_back_web.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/388\/original\/ladies_v_front_bro_science_empire_lifts_back_web.jpg","dimensions":{"width":427,"height":617}},{"id":360,"type":"print_file","typeName":"Print File","name":"stars.jpg","size":2080226,"updated":"2016-10-18T19:20:43+00:00","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/360\/medium\/stars.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/360\/original\/stars.jpg","dimensions":{"width":5000,"height":3500}},{"id":214,"type":"print_file","typeName":"Print File","name":"big5-2-God-is-in-Nature.jpg","size":12821358,"updated":"2016-10-11T14:45:39+00:00","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/214\/medium\/big5-2-God-is-in-Nature.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/214\/original\/big5-2-God-is-in-Nature.jpg","dimensions":{"width":8512,"height":5664}},{"id":128,"type":"print_file","typeName":"Print File","name":"flash.png","size":77700,"updated":"2016-10-07T17:35:37+00:00","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/128\/medium\/flash.png","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/128\/original\/flash.png","dimensions":{"width":1024,"height":1024}},{"id":127,"type":"print_file","typeName":"Print File","name":"ghost-busters.png","size":390127,"updated":"2016-10-07T17:35:33+00:00","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/127\/medium\/ghost-busters.png","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/127\/original\/ghost-busters.png","dimensions":{"width":1152,"height":998}}},"meta":{"pagination":{"total":7,"count":7,"per_page":8,"current_page":1,"total_pages":1,"links":{}}}}}})
     * })
     * @Parameters({
     *      @Parameter("page", type="integer", required=false, description="Pagination page", default=1),
     *      @Parameter("search", type="string", required=false, description="Search name filter")
     * })
     */
    public function searchSource(Request $request)
    {
        return app('App\Http\Controllers\Dashboard\SourceLibraryController')
            ->search($request);
    }

    /**
     * Upload source library file
     *
     * @Post("/sources/upload")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request(identifier="/library/sources/upload"),
     *      @Response(200, body={"status":200,"isError":false,"message":"File uploaded","data":{"file":{"id":504,"type":"source_file","typeName":"Source file","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/FileAttachment\/files\/000\/000\/504\/original\/Sharket -user inbox.psd","name":"Sharket -user inbox.psd","size":3214838,"updated":"2016-12-23T22:09:11+00:00"}}}),
     *      @Response(422, body={"status":422,"isError":true,"message":"422 Unprocessable Entity","validationErrors":{"file":{"The file must be a file of type: PSD, EPS or AI."}},"data":{}})
     * })
     * @Parameters({
     *      @Parameter("file", type="binary", required=true, description="File")
     * })
     */
    public function uploadSourceFile(UploadSourceFileFormRequest $request)
    {
        return app('App\Http\Controllers\Dashboard\SourceLibraryController')
            ->uploadFile($request);
    }

    /**
     * Get file by it's ID
     *
     * @Get("/{file_id}")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request(identifier="/library/{file_id}"),
     *      @Response(200, body={"status":200,"isError":false,"message":null,"data":{"file":{"id":514,"type":"print_file","typeName":"Print File","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/514\/original\/243312.jpg","name":"243312.jpg","size":54309,"updated":"2017-01-13T00:51:27+00:00","policy":{"allowed":{"show":true,"delete":true},"denied":{"show":false,"delete":false}}}}})
     * })
     * @Parameters({
     *      @Parameter("file_id", type="integer", required=true, description="File ID")
     * })
     */
    public function getFile(Request $request, $file_id)
    {
        return app('App\Http\Controllers\Dashboard\PrintLibraryController')
            ->getFile($request, $file_id);
    }

    /**
     * Download file by it's ID
     *
     * @Get("/{file_id}/download")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request(identifier="/library/{file_id}/download"),
     *      @Response(200, body={"Binary file content"})
     * })
     * @Parameters({
     *      @Parameter("file_id", type="integer", required=true, description="File ID")
     * })
     */
    public function downloadFile(Request $request, $file_id)
    {
        return app('App\Http\Controllers\Dashboard\PrintLibraryController')
            ->downloadFile($request, $file_id);
    }

    /**
     * Delete file by it's ID
     *
     * @Post("/{file_id}/delete")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request(identifier="/library/{file_id}/delete"),
     *      @Response(200, body={"status":200,"isError":false,"message":"File has been deleted","data":{}})
     * })
     * @Parameters({
     *      @Parameter("file_id", type="integer", required=true, description="File ID")
     * })
     */
    public function deleteFile(Request $request, $file_id)
    {
        return app('App\Http\Controllers\Dashboard\PrintLibraryController')
            ->deleteFile($request, $file_id);
    }
}
